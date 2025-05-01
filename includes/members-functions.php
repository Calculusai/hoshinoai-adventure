<?php
/**
 * 成员管理功能
 * 包含冒险团成员的添加、移除、角色查询等功能
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加团队成员
 * 
 * @param array $member_data 成员数据
 * @return int|bool 成功返回成员ID，失败返回false
 */
function hoshinoai_add_team_member($member_data) {
    global $wpdb;
    
    // 检查必要字段
    if (empty($member_data['team_id']) || empty($member_data['user_id'])) {
        return false;
    }
    
    // 检查用户是否已经是该团队活跃成员
    $existing_member = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_members 
            WHERE team_id = %d AND user_id = %d",
            $member_data['team_id'],
            $member_data['user_id']
        )
    );
    
    // 检查是否存在角色详情记录
    $existing_details = false;
    if ($existing_member) {
        $existing_details = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_character_details 
                WHERE member_id = %d",
                $existing_member->id
            )
        );
    }
    
    // 设置默认值
    $member_data = wp_parse_args($member_data, array(
        'role' => 'member',
        'character_name' => null,
        'character_class' => null,
        'character_level' => 1,
        'join_date' => current_time('mysql'),
    ));
    
    // 如果成员已存在但角色详情有记录，可能是之前被移除的成员
    if ($existing_member && $existing_details) {
        // 检查角色详情状态
        if ($existing_details->status === 'dissolved') {
            // 更新角色详情状态为活跃
            $wpdb->update(
                $wpdb->prefix . 'hoshinoai_adventure_character_details',
                array(
                    'status' => 'active',
                    'updated_at' => current_time('mysql')
                ),
                array('member_id' => $existing_member->id)
            );
            
            return $existing_member->id;
        } else if ($existing_details->status === 'active') {
            // 已经是活跃成员
            return false;
        }
    } else if ($existing_member) {
        // 已经是成员但没有角色详情
        return $existing_member->id;
    }
    
    // 插入新成员数据
    $result = $wpdb->insert(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        $member_data
    );
    
    if ($result === false) {
        return false;
    }
    
    return $wpdb->insert_id;
}

/**
 * 移除团队成员
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_remove_team_member($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    // 获取团队信息
    $team = hoshinoai_get_team($team_id);
    
    // 不能移除团长
    if ($team && $team->leader_id == $user_id) {
        return false;
    }
    
    // 如果是副团长，需要同时更新团队表
    if ($team && $team->vice_leader_id == $user_id) {
        $wpdb->update(
            $wpdb->prefix . 'hoshinoai_adventure_teams',
            array('vice_leader_id' => null),
            array('id' => $team_id)
        );
    }
    
    // 获取成员信息
    $member = hoshinoai_get_member($team_id, $user_id);
    if (!$member) {
        return false;
    }
    
    // 开始事务
    $wpdb->query('START TRANSACTION');
    
    // 将角色详情状态改为已解散，而不是删除
    $character_details_updated = true;
    $existing_details = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_character_details 
            WHERE member_id = %d",
            $member->id
        )
    );
    
    if ($existing_details) {
        $character_details_updated = $wpdb->update(
            $wpdb->prefix . 'hoshinoai_adventure_character_details',
            array(
                'status' => 'dissolved',
                'updated_at' => current_time('mysql')
            ),
            array('member_id' => $member->id)
        );
        
        if ($character_details_updated === false) {
            $wpdb->query('ROLLBACK');
            return false;
        }
    }
    
    // 移除成员记录 - 为保持一致性，我们也可以考虑将成员状态改为inactive，但当前表结构没有状态字段
    // 因此暂时还是删除成员记录
    $result = $wpdb->delete(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        array(
            'team_id' => $team_id,
            'user_id' => $user_id,
        )
    );
    
    if ($result === false) {
        $wpdb->query('ROLLBACK');
        return false;
    }
    
    $wpdb->query('COMMIT');
    return true;
}

/**
 * 获取团队成员
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return object|bool 成功返回成员信息，失败返回false
 */
function hoshinoai_get_member($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    $member = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_members 
            WHERE team_id = %d AND user_id = %d",
            $team_id,
            $user_id
        )
    );
    
    return $member;
}

/**
 * 获取团队所有成员
 * 
 * @param int $team_id 团队ID
 * @return array 成员列表
 */
function hoshinoai_get_team_members($team_id) {
    global $wpdb;
    
    if (empty($team_id)) {
        return array();
    }
    
    $members = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_members 
            WHERE team_id = %d
            ORDER BY role DESC, join_date ASC",
            $team_id
        )
    );
    
    return $members ? $members : array();
}

/**
 * 设置副团长
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_set_vice_leader($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        return false;
    }
    
    // 检查是否为团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        return false;
    }
    
    // 不能将团长设为副团长
    if ($team->leader_id == $user_id) {
        return false;
    }
    
    // 如果已有副团长，将其角色改为普通成员
    if ($team->vice_leader_id) {
        $wpdb->update(
            $wpdb->prefix . 'hoshinoai_adventure_members',
            array('role' => 'member'),
            array('team_id' => $team_id, 'user_id' => $team->vice_leader_id)
        );
    }
    
    // 开始事务
    $wpdb->query('START TRANSACTION');
    
    // 更新成员角色
    $member_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        array('role' => 'vice_leader'),
        array('team_id' => $team_id, 'user_id' => $user_id)
    );
    
    // 更新团队表的副团长字段
    $team_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_teams',
        array('vice_leader_id' => $user_id),
        array('id' => $team_id)
    );
    
    // 检查是否全部更新成功
    if ($member_updated !== false && $team_updated !== false) {
        $wpdb->query('COMMIT');
        return true;
    } else {
        $wpdb->query('ROLLBACK');
        return false;
    }
}

/**
 * 更新成员角色信息
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @param array $character_data 角色数据
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_update_character($team_id, $user_id, $character_data) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id) || empty($character_data)) {
        return false;
    }
    
    // 检查是否为团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        return false;
    }
    
    // 更新角色信息
    $result = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        $character_data,
        array('team_id' => $team_id, 'user_id' => $user_id)
    );
    
    return $result !== false;
}

/**
 * 用户退出团队
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_leave_team($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    $team = hoshinoai_get_team($team_id);
    
    // 团长不能退出团队
    if ($team && $team->leader_id == $user_id) {
        return false;
    }
    
    // 使用移除成员功能，逻辑已经在remove_team_member中处理
    return hoshinoai_remove_team_member($team_id, $user_id);
}

/**
 * 申请加入团队
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return int|bool 成功返回申请ID，失败返回false
 */
function hoshinoai_join_team_request($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    // 检查团队是否存在且活跃
    $team = hoshinoai_get_team($team_id);
    if (!$team || $team->status !== 'active') {
        return false;
    }
    
    // 检查用户是否已经是团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    if ($member) {
        return false;
    }
    
    // 检查是否已有未处理的申请
    $existing_request = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_join_requests 
            WHERE team_id = %d AND user_id = %d AND status = 'pending'",
            $team_id,
            $user_id
        )
    );
    
    if ($existing_request) {
        return $existing_request->id;
    }
    
    // 创建新申请
    $result = $wpdb->insert(
        $wpdb->prefix . 'hoshinoai_adventure_join_requests',
        array(
            'team_id' => $team_id,
            'user_id' => $user_id,
            'status' => 'pending',
            'request_date' => current_time('mysql'),
        )
    );
    
    if ($result === false) {
        return false;
    }
    
    return $wpdb->insert_id;
}

/**
 * 处理加入团队申请
 * 
 * @param int $request_id 申请ID
 * @param string $action 处理动作（approve/reject）
 * @param int $processed_by 处理人ID
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_process_join_request($request_id, $action, $processed_by) {
    global $wpdb;
    
    if (empty($request_id) || empty($action) || empty($processed_by)) {
        return false;
    }
    
    // 获取申请信息
    $request = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_join_requests WHERE id = %d",
            $request_id
        )
    );
    
    if (!$request || $request->status !== 'pending') {
        return false;
    }
    
    // 检查处理人是否有权限（团长或副团长）
    $team = hoshinoai_get_team($request->team_id);
    if (!$team || ($team->leader_id != $processed_by && $team->vice_leader_id != $processed_by)) {
        return false;
    }
    
    // 开始事务
    $wpdb->query('START TRANSACTION');
    
    // 检查是否存在已经处理过的同一用户同一团队的申请记录
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $existing_request = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_join_requests 
            WHERE team_id = %d AND user_id = %d AND status = %s AND id != %d",
            $request->team_id,
            $request->user_id,
            $status,
            $request_id
        )
    );
    
    // 如果存在同状态的申请，先删除它
    if ($existing_request) {
        $delete_result = $wpdb->delete(
            $wpdb->prefix . 'hoshinoai_adventure_join_requests',
            array('id' => $existing_request->id)
        );
        
        if ($delete_result === false) {
            $wpdb->query('ROLLBACK');
            return false;
        }
    }
    
    // 更新申请状态
    $request_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_join_requests',
        array(
            'status' => $status,
            'processed_by' => $processed_by,
            'processed_date' => current_time('mysql'),
        ),
        array('id' => $request_id)
    );
    
    if ($request_updated === false) {
        $wpdb->query('ROLLBACK');
        return false;
    }
    
    // 如果是批准，则添加成员
    if ($action === 'approve') {
        $member_data = array(
            'team_id' => $request->team_id,
            'user_id' => $request->user_id,
            'role' => 'member',
        );
        
        $result = hoshinoai_add_team_member($member_data);
        
        if (!$result) {
            $wpdb->query('ROLLBACK');
            return false;
        }
    }
    
    $wpdb->query('COMMIT');
    return true;
}

/**
 * 获取团队的加入申请列表
 * 
 * @param int $team_id 团队ID
 * @param string $status 申请状态（可选，默认为pending）
 * @return array 申请列表
 */
function hoshinoai_get_team_join_requests($team_id, $status = 'pending') {
    global $wpdb;
    
    if (empty($team_id)) {
        return array();
    }
    
    $requests = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT r.*, u.display_name, u.user_email 
            FROM {$wpdb->prefix}hoshinoai_adventure_join_requests AS r
            LEFT JOIN {$wpdb->users} AS u ON r.user_id = u.ID
            WHERE r.team_id = %d AND r.status = %s
            ORDER BY r.request_date DESC",
            $team_id,
            $status
        )
    );
    
    return $requests ? $requests : array();
}

/**
 * AJAX处理申请加入团队请求
 */
function hoshinoai_ajax_join_team() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查团队状态
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        wp_send_json_error('该团队不存在或已解散');
    }
    
    // 检查是否已经是成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if ($member) {
        wp_send_json_error('您已经是该团队成员');
    }
    
    // 检查团队人数是否达到上限
    $max_members = hoshinoai_adventure('max_team_members', 20);
    $team_members = hoshinoai_get_team_members($team_id);
    
    if (count($team_members) >= $max_members) {
        wp_send_json_error('团队成员数量已达上限');
    }
    
    // 提交加入申请
    $result = hoshinoai_join_team_request($team_id, $user_id);
    
    if ($result) {
        // 发送加入申请通知给团长和副团长
        if (function_exists('hoshinoai_send_join_request_notification')) {
            hoshinoai_send_join_request_notification($team_id, $user_id);
        }
        
        wp_send_json_success(array(
            'message' => '已发送加入申请，请等待团长审核',
        ));
    } else {
        wp_send_json_error('申请加入团队失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_join_team', 'hoshinoai_ajax_join_team');

/**
 * AJAX处理审批加入申请请求
 */
function hoshinoai_ajax_process_join_request() {
    // 添加调试日志
    error_log('处理加入申请请求开始: ' . json_encode($_POST));
    
    // 验证nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        error_log('nonce验证失败');
        wp_send_json_error('安全验证失败');
        return;
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        error_log('用户未登录');
        wp_send_json_error('请先登录');
        return;
    }
    
    // 验证必要字段
    if (empty($_POST['request_id']) || empty($_POST['request_action'])) {
        error_log('缺少必要参数: request_id=' . (isset($_POST['request_id']) ? $_POST['request_id'] : 'missing') 
            . ', request_action=' . (isset($_POST['request_action']) ? $_POST['request_action'] : 'missing'));
        wp_send_json_error('缺少必要参数');
        return;
    }
    
    $request_id = intval($_POST['request_id']);
    $action = sanitize_text_field($_POST['request_action']);
    $user_id = get_current_user_id();
    
    error_log("参数: request_id={$request_id}, action={$action}, user_id={$user_id}");
    
    // 验证操作
    if (!in_array($action, array('approve', 'reject'))) {
        error_log('无效的操作: ' . $action);
        wp_send_json_error('无效的操作');
        return;
    }
    
    // 获取申请信息
    global $wpdb;
    $request = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_join_requests WHERE id = %d",
            $request_id
        )
    );
    
    if (!$request) {
        error_log('找不到申请ID: ' . $request_id);
        wp_send_json_error('申请不存在');
        return;
    }
    
    if ($request->status !== 'pending') {
        error_log('申请已被处理，当前状态: ' . $request->status);
        wp_send_json_error('申请已被处理');
        return;
    }
    
    // 检查处理人是否有权限（团长或副团长）
    $team = hoshinoai_get_team($request->team_id);
    if (!$team) {
        error_log('找不到团队: ' . $request->team_id);
        wp_send_json_error('团队不存在');
        return;
    }
    
    if ($team->leader_id != $user_id && $team->vice_leader_id != $user_id) {
        error_log('用户无权限: user_id=' . $user_id . ', leader_id=' . $team->leader_id . ', vice_leader_id=' . $team->vice_leader_id);
        wp_send_json_error('您没有权限处理此申请');
        return;
    }
    
    // 处理申请
    error_log('开始处理申请');
    $result = hoshinoai_process_join_request($request_id, $action, $user_id);
    error_log('处理申请结果: ' . ($result ? 'true' : 'false'));
    
    if ($result) {
        $message = ($action === 'approve') ? '已批准加入申请' : '已拒绝加入申请';
        
        // 发送申请处理结果通知
        if (function_exists('hoshinoai_send_application_result_notification')) {
            hoshinoai_send_application_result_notification($request->team_id, $user_id, $request->user_id, $action === 'approve');
        }
        
        error_log('处理成功，返回: ' . $message);
        wp_send_json_success(array(
            'message' => $message,
            'team_id' => $request->team_id,
            'user_id' => $request->user_id,
            'action' => $action
        ));
    } else {
        error_log('处理申请失败');
        wp_send_json_error('处理申请失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_process_join_request', 'hoshinoai_ajax_process_join_request');

/**
 * AJAX处理添加成员请求
 */
function hoshinoai_ajax_add_member() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id']) || empty($_POST['username'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $username = sanitize_text_field($_POST['username']);
    $user_id = get_current_user_id();
    
    // 检查当前用户是否有权限添加成员
    if (!hoshinoai_can_manage_team($team_id, $user_id)) {
        wp_send_json_error('您没有权限添加成员');
    }
    
    // 查找目标用户
    $target_user = get_user_by('login', $username);
    
    // 如果通过登录名没找到，尝试通过邮箱查找
    if (!$target_user) {
        $target_user = get_user_by('email', $username);
    }
    
    if (!$target_user) {
        wp_send_json_error('找不到该用户');
    }
    
    // 检查团队人数是否达到上限
    $max_members = hoshinoai_adventure('max_team_members', 20);
    $team_members = hoshinoai_get_team_members($team_id);
    
    if (count($team_members) >= $max_members) {
        wp_send_json_error('团队成员数量已达上限');
    }
    
    // 添加成员
    $member_data = array(
        'team_id' => $team_id,
        'user_id' => $target_user->ID,
        'role' => 'member',
    );
    
    $result = hoshinoai_add_team_member($member_data);
    
    if ($result) {
        // 发送邀请通知
        if (function_exists('hoshinoai_send_team_invite_notification')) {
            hoshinoai_send_team_invite_notification($team_id, $user_id, $target_user->ID);
        }
        
        wp_send_json_success(array(
            'message' => '成员添加成功',
            'user_id' => $target_user->ID,
            'username' => $target_user->display_name,
        ));
    } else {
        wp_send_json_error('该用户已经是团队成员');
    }
}
add_action('wp_ajax_hoshinoai_ajax_add_member', 'hoshinoai_ajax_add_member');

/**
 * AJAX处理移除成员请求
 */
function hoshinoai_ajax_remove_member() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id']) || empty($_POST['user_id'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $target_user_id = intval($_POST['user_id']);
    $user_id = get_current_user_id();
    
    // 检查当前用户是否有权限移除成员
    if (!hoshinoai_can_manage_team($team_id, $user_id)) {
        wp_send_json_error('您没有权限移除成员');
    }
    
    // 不能移除自己
    if ($target_user_id == $user_id) {
        wp_send_json_error('不能移除自己');
    }
    
    // 移除成员
    $result = hoshinoai_remove_team_member($team_id, $target_user_id);
    
    if ($result) {
        // 发送成员被移除通知
        if (function_exists('hoshinoai_send_member_leave_notification')) {
            hoshinoai_send_member_leave_notification($team_id, $user_id, $target_user_id, true);
        }
        
        wp_send_json_success(array(
            'message' => '成员已移除',
        ));
    } else {
        wp_send_json_error('成员移除失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_remove_member', 'hoshinoai_ajax_remove_member');

/**
 * AJAX处理设置副团长请求
 */
function hoshinoai_ajax_set_vice_leader() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id']) || empty($_POST['user_id'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $target_user_id = intval($_POST['user_id']);
    $user_id = get_current_user_id();
    
    // 检查当前用户是否是团长
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->leader_id != $user_id) {
        wp_send_json_error('只有团长可以设置副团长');
    }
    
    // 设置副团长
    $result = hoshinoai_set_vice_leader($team_id, $target_user_id);
    
    if ($result) {
        // 发送角色变更通知
        if (function_exists('hoshinoai_send_role_change_notification')) {
            hoshinoai_send_role_change_notification($team_id, $user_id, $target_user_id, 'vice_leader');
        }
        
        wp_send_json_success(array(
            'message' => '副团长设置成功',
        ));
    } else {
        wp_send_json_error('副团长设置失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_set_vice_leader', 'hoshinoai_ajax_set_vice_leader');

/**
 * AJAX处理更新角色信息请求
 */
function hoshinoai_ajax_update_character() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查是否为团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        wp_send_json_error('您不是该团队成员');
    }
    
    // 准备数据
    $character_data = array();
    
    if (isset($_POST['character_name'])) {
        $character_data['character_name'] = sanitize_text_field($_POST['character_name']);
    }
    
    if (isset($_POST['character_class'])) {
        $character_data['character_class'] = sanitize_text_field($_POST['character_class']);
    }
    
    if (isset($_POST['character_level'])) {
        $character_data['character_level'] = intval($_POST['character_level']);
    }
    
    // 更新角色信息
    $result = hoshinoai_update_character($team_id, $user_id, $character_data);
    
    if ($result) {
        // 发送角色信息更新通知
        if (function_exists('hoshinoai_send_character_update_notification')) {
            hoshinoai_send_character_update_notification($team_id, $user_id, $character_data);
        }
        
        wp_send_json_success(array(
            'message' => '角色信息已更新',
        ));
    } else {
        wp_send_json_error('角色信息更新失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_update_character', 'hoshinoai_ajax_update_character');

/**
 * AJAX处理退出团队请求
 */
function hoshinoai_ajax_leave_team() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查是否为团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        wp_send_json_error('您不是该团队成员');
    }
    
    // 团长不能退出团队
    $team = hoshinoai_get_team($team_id);
    
    if ($team && $team->leader_id == $user_id) {
        wp_send_json_error('团长不能退出团队，请先转让团长职位或解散团队');
    }
    
    // 退出团队
    $result = hoshinoai_leave_team($team_id, $user_id);
    
    if ($result) {
        // 发送成员退出通知
        if (function_exists('hoshinoai_send_member_leave_notification')) {
            hoshinoai_send_member_leave_notification($team_id, $user_id, $user_id, false);
        }
        
        wp_send_json_success(array(
            'message' => '您已成功退出该团队',
        ));
    } else {
        wp_send_json_error('退出团队失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_leave_team', 'hoshinoai_ajax_leave_team');