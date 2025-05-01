<?php
/**
 * 冒险团通知系统函数
 * 
 * 包含冒险团相关的站内消息和邮件通知功能
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 发送冒险团相关通知
 * 
 * @param int $sender_id 发送者ID
 * @param int $recipient_id 接收者ID
 * @param string $type 通知类型
 * @param string $title 通知标题
 * @param string $content 通知内容
 * @param array $extra_data 额外数据
 * @return bool 是否发送成功
 */
function hoshinoai_send_notification($sender_id, $recipient_id, $type, $title, $content, $extra_data = array()) {
    // 检查参数
    if (!$sender_id || !$recipient_id || !$title || !$content) {
        return false;
    }
    
    // 获取用户信息
    $sender = get_userdata($sender_id);
    $recipient = get_userdata($recipient_id);
    
    if (!$sender || !$recipient) {
        return false;
    }
    
    // 如果接收者和发送者是同一人，且不是系统通知，则不发送
    if ($sender_id == $recipient_id && !isset($extra_data['is_system_notice'])) {
        return false;
    }
    
    // 处理内容（确保为HTML格式）
    $html_content = wpautop($content);
    
    // 发送站内消息
    if (class_exists('ZibMsg')) {
        $msg_args = array(
            'send_user'    => $sender_id,
            'receive_user' => $recipient_id,
            'type'         => 'adventure_' . $type,
            'title'        => $title,
            'content'      => $html_content,
        );
        
        // 如果有额外数据，添加到消息中
        if (!empty($extra_data)) {
            $msg_args['meta'] = $extra_data;
        }
        
        // 添加消息
        ZibMsg::add($msg_args);
    }
    
    // 发送邮件通知
    if (is_email($recipient->user_email)) {
        $blog_name = get_bloginfo('name');
        $mail_title = '[' . $blog_name . '] ' . $title;
        
        // 添加邮件页脚
        $email_footer = '<br><br>------------------<br>';
        $email_footer .= '此邮件由系统自动发送，请勿直接回复。<br>';
        $email_footer .= '如需查看更多信息，请访问 <a href="' . home_url('/user/adventure') . '">冒险团页面</a>';
        
        $html_content .= $email_footer;
        
        // 发送HTML邮件
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail($recipient->user_email, $mail_title, $html_content, $headers);
        
        return true;
    }
    
    return false;
}

/**
 * 发送冒险团创建成功通知
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return bool 是否发送成功
 */
function hoshinoai_send_team_created_notification($team_id, $user_id) {
    $team = hoshinoai_get_team($team_id);
    if (!$team) return false;
    
    $title = '冒险团创建成功';
    $content = '您好，<br>您的冒险团 <strong>' . esc_html($team->name) . '</strong> 已创建成功！<br>您可以邀请更多冒险者加入您的团队。';
    
    return hoshinoai_send_notification($user_id, $user_id, 'team_created', $title, $content, array(
        'team_id' => $team_id,
        'is_system_notice' => true
    ));
}

/**
 * 发送冒险团邀请通知
 * 
 * @param int $team_id 团队ID
 * @param int $sender_id 发送者ID
 * @param int $recipient_id 接收者ID
 * @return bool 是否发送成功
 */
function hoshinoai_send_team_invite_notification($team_id, $sender_id, $recipient_id) {
    $team = hoshinoai_get_team($team_id);
    $sender = get_userdata($sender_id);
    
    if (!$team || !$sender) return false;
    
    $title = '您收到一份冒险团邀请';
    $content = '您好，<br><strong>' . esc_html($sender->display_name) . '</strong> 邀请您加入冒险团 <strong>' . esc_html($team->name) . '</strong>。<br>请前往用户中心的冒险团页面查看详情。';
    
    return hoshinoai_send_notification($sender_id, $recipient_id, 'team_invite', $title, $content, array('team_id' => $team_id));
}

/**
 * 发送加入申请通知
 * 
 * @param int $team_id 团队ID
 * @param int $applicant_id 申请者ID
 * @return bool 是否发送成功
 */
function hoshinoai_send_join_request_notification($team_id, $applicant_id) {
    $team = hoshinoai_get_team($team_id);
    $applicant = get_userdata($applicant_id);
    
    if (!$team || !$applicant) return false;
    
    $title = '有新的冒险团加入申请';
    $content = '您好，<br><strong>' . esc_html($applicant->display_name) . '</strong> 申请加入您的冒险团 <strong>' . esc_html($team->name) . '</strong>。<br>请前往冒险团页面审核申请。';
    
    $success = true;
    
    // 通知团长
    if (!hoshinoai_send_notification($applicant_id, $team->leader_id, 'join_request', $title, $content, array('team_id' => $team_id))) {
        $success = false;
    }
    
    // 如果有副团长也通知
    if (!empty($team->vice_leader_id)) {
        if (!hoshinoai_send_notification($applicant_id, $team->vice_leader_id, 'join_request', $title, $content, array('team_id' => $team_id))) {
            $success = false;
        }
    }
    
    return $success;
}

/**
 * 发送申请处理结果通知
 * 
 * @param int $team_id 团队ID
 * @param int $sender_id 发送者ID
 * @param int $recipient_id 接收者ID
 * @param bool $is_approved 是否批准
 * @return bool 是否发送成功
 */
function hoshinoai_send_application_result_notification($team_id, $sender_id, $recipient_id, $is_approved) {
    $team = hoshinoai_get_team($team_id);
    
    if (!$team) return false;
    
    $result = $is_approved ? '批准' : '拒绝';
    $title = '您的冒险团加入申请已' . $result;
    $content = '您好，<br>您申请加入冒险团 <strong>' . esc_html($team->name) . '</strong> 的请求已被' . $result . '。';
    
    if ($is_approved) {
        $content .= '<br>您现在已成为该冒险团的成员，可以前往冒险团页面查看详情。';
    }
    
    return hoshinoai_send_notification($sender_id, $recipient_id, 'application_result', $title, $content, array(
        'team_id' => $team_id,
        'approved' => $is_approved
    ));
}

/**
 * 发送角色变更通知
 * 
 * @param int $team_id 团队ID
 * @param int $sender_id 发送者ID
 * @param int $recipient_id 接收者ID
 * @param string $new_role 新角色
 * @return bool 是否发送成功
 */
function hoshinoai_send_role_change_notification($team_id, $sender_id, $recipient_id, $new_role) {
    $team = hoshinoai_get_team($team_id);
    $sender = get_userdata($sender_id);
    
    if (!$team || !$sender) return false;
    
    $role_text = '';
    switch ($new_role) {
        case 'leader':
            $role_text = '团长';
            break;
        case 'vice_leader':
            $role_text = '副团长';
            break;
        case 'member':
            $role_text = '普通成员';
            break;
        default:
            $role_text = $new_role;
    }
    
    $title = '您在冒险团中的角色已变更';
    $content = '您好，<br>您在冒险团 <strong>' . esc_html($team->name) . '</strong> 中的角色已变更为：<strong>' . $role_text . '</strong>。';
    
    if ($sender_id != $recipient_id) {
        $content .= '<br>此变更由 <strong>' . esc_html($sender->display_name) . '</strong> 操作。';
    }
    
    return hoshinoai_send_notification($sender_id, $recipient_id, 'role_change', $title, $content, array(
        'team_id' => $team_id,
        'new_role' => $new_role
    ));
}

/**
 * 发送成员退出或被移除通知
 * 
 * @param int $team_id 团队ID
 * @param int $sender_id 发送者ID
 * @param int $recipient_id 接收者ID
 * @param bool $is_removed 是否被移除
 * @return bool 是否发送成功
 */
function hoshinoai_send_member_leave_notification($team_id, $sender_id, $recipient_id, $is_removed = false) {
    $team = hoshinoai_get_team($team_id);
    $sender = get_userdata($sender_id);
    
    if (!$team || !$sender) return false;
    
    if ($sender_id == $recipient_id) {
        $title = '您已成功退出冒险团';
        $content = '您好，<br>您已成功退出冒险团 <strong>' . esc_html($team->name) . '</strong>。';
    } else if ($is_removed) {
        $title = '您已被移出冒险团';
        $content = '您好，<br>您已被 <strong>' . esc_html($sender->display_name) . '</strong> 移出冒险团 <strong>' . esc_html($team->name) . '</strong>。';
    } else {
        $title = '成员已离开冒险团';
        $content = '您好，<br>成员 <strong>' . esc_html(get_userdata($recipient_id)->display_name) . '</strong> 已离开冒险团 <strong>' . esc_html($team->name) . '</strong>。';
        
        // 如果是通知管理员成员离开，则改变接收者为团长
        $original_recipient = $recipient_id;
        $recipient_id = $team->leader_id;
        
        // 发送通知给团长
        return hoshinoai_send_notification($original_recipient, $recipient_id, 'member_leave', $title, $content, array(
            'team_id' => $team_id,
            'is_removed' => false,
            'member_id' => $original_recipient
        ));
    }
    
    return hoshinoai_send_notification($sender_id, $recipient_id, 'member_leave', $title, $content, array(
        'team_id' => $team_id,
        'is_removed' => $is_removed
    ));
}

/**
 * 发送团队解散通知
 * 
 * @param int $team_id 团队ID
 * @param int $leader_id 团长ID
 * @param array $members 成员列表
 * @return bool 是否发送成功
 */
function hoshinoai_send_team_dissolved_notification($team_id, $leader_id, $members) {
    $team = hoshinoai_get_team($team_id);
    $leader = get_userdata($leader_id);
    
    if (!$team || !$leader || !is_array($members)) return false;
    
    $title = '冒险团已解散';
    $content = '您好，<br>您所在的冒险团 <strong>' . esc_html($team->name) . '</strong> 已被团长 <strong>' . esc_html($leader->display_name) . '</strong> 解散。';
    
    $success = true;
    
    foreach ($members as $member) {
        if ($member->user_id != $leader_id) {
            if (!hoshinoai_send_notification($leader_id, $member->user_id, 'team_dissolved', $title, $content, array('team_id' => $team_id))) {
                $success = false;
            }
        }
    }
    
    return $success;
}

/**
 * 发送团长职位转让通知
 * 
 * @param int $team_id 团队ID
 * @param int $old_leader_id 原团长ID
 * @param int $new_leader_id 新团长ID
 * @return bool 是否发送成功
 */
function hoshinoai_send_leadership_transfer_notification($team_id, $old_leader_id, $new_leader_id) {
    $team = hoshinoai_get_team($team_id);
    $old_leader = get_userdata($old_leader_id);
    $new_leader = get_userdata($new_leader_id);
    
    if (!$team || !$old_leader || !$new_leader) return false;
    
    // 通知新团长
    $title_new = '您已成为冒险团团长';
    $content_new = '您好，<br><strong>' . esc_html($old_leader->display_name) . '</strong> 已将冒险团 <strong>' . esc_html($team->name) . '</strong> 的团长职位转让给您。<br>您现在拥有该团队的完全管理权限。';
    
    $success = hoshinoai_send_notification($old_leader_id, $new_leader_id, 'leadership_transfer', $title_new, $content_new, array('team_id' => $team_id));
    
    // 通知原团长
    $title_old = '您已转让冒险团团长职位';
    $content_old = '您好，<br>您已成功将冒险团 <strong>' . esc_html($team->name) . '</strong> 的团长职位转让给 <strong>' . esc_html($new_leader->display_name) . '</strong>。<br>您现在是该团队的普通成员。';
    
    $success = hoshinoai_send_notification($new_leader_id, $old_leader_id, 'leadership_transfer', $title_old, $content_old, array(
        'team_id' => $team_id,
        'is_system_notice' => true
    )) && $success;
    
    // 通知所有其他成员
    $members = hoshinoai_get_team_members($team_id);
    $title_members = '冒险团团长已更换';
    $content_members = '您好，<br>您所在的冒险团 <strong>' . esc_html($team->name) . '</strong> 的团长已由 <strong>' . esc_html($old_leader->display_name) . '</strong> 变更为 <strong>' . esc_html($new_leader->display_name) . '</strong>。';
    
    foreach ($members as $member) {
        if ($member->user_id != $old_leader_id && $member->user_id != $new_leader_id) {
            if (!hoshinoai_send_notification($old_leader_id, $member->user_id, 'leadership_transfer', $title_members, $content_members, array('team_id' => $team_id))) {
                $success = false;
            }
        }
    }
    
    return $success;
}

/**
 * 发送角色信息更新通知
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @param array $character_data 角色数据
 * @return bool 是否发送成功
 */
function hoshinoai_send_character_update_notification($team_id, $user_id, $character_data) {
    $team = hoshinoai_get_team($team_id);
    $user = get_userdata($user_id);
    
    if (!$team || !$user || !is_array($character_data)) return false;
    
    $title = '角色信息已更新';
    $content = '您好，<br>您在冒险团 <strong>' . esc_html($team->name) . '</strong> 中的角色信息已更新：';
    
    $content .= '<ul>';
    if (!empty($character_data['character_name'])) {
        $content .= '<li>角色名称: <strong>' . esc_html($character_data['character_name']) . '</strong></li>';
    }
    if (!empty($character_data['character_class'])) {
        $content .= '<li>职业: <strong>' . esc_html($character_data['character_class']) . '</strong></li>';
    }
    if (!empty($character_data['character_level'])) {
        $content .= '<li>等级: <strong>' . esc_html($character_data['character_level']) . '</strong></li>';
    }
    $content .= '</ul>';
    
    return hoshinoai_send_notification($user_id, $user_id, 'character_update', $title, $content, array(
        'team_id' => $team_id,
        'is_system_notice' => true
    ));
} 