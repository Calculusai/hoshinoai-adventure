<?php
/**
 * 团队管理功能
 * 包含冒险团的创建、解散、查询等功能
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 创建新的冒险团
 * 
 * @param array $team_data 团队数据
 * @return int|bool 成功返回团队ID，失败返回false
 */
function hoshinoai_create_team($team_data) {
    global $wpdb;
    
    // 检查必要字段
    if (empty($team_data['name']) || empty($team_data['leader_id'])) {
        return false;
    }
    
    // 设置默认值
    $team_data = wp_parse_args($team_data, array(
        'description' => '',
        'vice_leader_id' => null,
        'status' => 'active',
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ));
    
    // 先检查是否存在同名活跃团队
    $active_team = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_teams 
            WHERE name = %s AND status = 'active'",
            $team_data['name']
        )
    );
    
    // 如果存在同名活跃团队，则不能创建
    if ($active_team) {
        return false;
    }
    
    // 检查是否存在同名不活跃团队
    $existing_team = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_teams 
            WHERE name = %s AND status != 'active'",
            $team_data['name']
        )
    );
    
    if ($existing_team) {
        // 更新已存在的团队状态为活跃
        $result = $wpdb->update(
            $wpdb->prefix . 'hoshinoai_adventure_teams',
            array(
                'description' => $team_data['description'],
                'leader_id' => $team_data['leader_id'],
                'vice_leader_id' => $team_data['vice_leader_id'],
                'status' => 'active',
                'updated_at' => current_time('mysql'),
            ),
            array('id' => $existing_team->id)
        );
        
        if ($result === false) {
            return false;
        }
        
        // 为团长创建成员记录
        $member_data = array(
            'team_id' => $existing_team->id,
            'user_id' => $team_data['leader_id'],
            'role' => 'leader',
            'join_date' => current_time('mysql'),
        );
        
        hoshinoai_add_team_member($member_data);
        
        return $existing_team->id;
    } else {
        // 插入新团队数据
    $result = $wpdb->insert(
        $wpdb->prefix . 'hoshinoai_adventure_teams',
        $team_data
    );
    
    if ($result === false) {
        return false;
    }
    
    $team_id = $wpdb->insert_id;
    
    // 为团长创建成员记录
    $member_data = array(
        'team_id' => $team_id,
        'user_id' => $team_data['leader_id'],
        'role' => 'leader',
        'join_date' => current_time('mysql'),
    );
    
    hoshinoai_add_team_member($member_data);
    
    return $team_id;
    }
}

/**
 * 获取冒险团信息
 * 
 * @param int $team_id 团队ID
 * @return object|bool 成功返回团队信息，失败返回false
 */
function hoshinoai_get_team($team_id) {
    global $wpdb;
    
    if (empty($team_id)) {
        return false;
    }
    
    $team = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_teams WHERE id = %d",
            $team_id
        )
    );
    
    return $team;
}

/**
 * 更新冒险团信息
 * 
 * @param int $team_id 团队ID
 * @param array $team_data 团队数据
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_update_team($team_id, $team_data) {
    global $wpdb;
    
    if (empty($team_id)) {
        return false;
    }
    
    // 添加更新时间
    $team_data['updated_at'] = current_time('mysql');
    
    // 更新数据
    $result = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_teams',
        $team_data,
        array('id' => $team_id)
    );
    
    return $result !== false;
}

/**
 * 解散冒险团
 * 
 * @param int $team_id 团队ID
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_dissolve_team($team_id) {
    global $wpdb;
    
    if (empty($team_id)) {
        return false;
    }
    
    // 开始事务
    $wpdb->query('START TRANSACTION');
    
    // 更新团队状态为已解散
    $team_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_teams',
        array(
            'status' => 'dissolved',
            'updated_at' => current_time('mysql'),
        ),
        array('id' => $team_id)
    );
    
    // 更新所有团队角色详情状态为已解散
    $details_updated = $wpdb->query(
        $wpdb->prepare(
            "UPDATE {$wpdb->prefix}hoshinoai_adventure_character_details 
            SET status = 'dissolved', updated_at = %s
            WHERE team_id = %d",
            current_time('mysql'),
            $team_id
        )
    );
    
    // 删除所有团队成员记录
    $members_deleted = $wpdb->delete(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        array('team_id' => $team_id)
    );
    
    // 检查是否更新成功
    if ($team_updated !== false) {
        $wpdb->query('COMMIT');
        return true;
    } else {
        $wpdb->query('ROLLBACK');
        return false;
    }
}

/**
 * 获取用户创建的所有冒险团
 * 
 * @param int $user_id 用户ID
 * @return array 冒险团列表
 */
function hoshinoai_get_user_created_teams($user_id) {
    global $wpdb;
    
    if (empty($user_id)) {
        return array();
    }
    
    $teams = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_teams 
            WHERE leader_id = %d AND status = 'active'
            ORDER BY created_at DESC",
            $user_id
        )
    );
    
    return $teams ? $teams : array();
}

/**
 * 获取用户加入的所有冒险团
 * 
 * @param int $user_id 用户ID
 * @return array 冒险团列表
 */
function hoshinoai_get_user_joined_teams($user_id) {
    global $wpdb;
    
    if (empty($user_id)) {
        return array();
    }
    
    $teams = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT t.* FROM {$wpdb->prefix}hoshinoai_adventure_teams AS t
            JOIN {$wpdb->prefix}hoshinoai_adventure_members AS m ON t.id = m.team_id
            WHERE m.user_id = %d AND t.status = 'active'
            ORDER BY t.created_at DESC",
            $user_id
        )
    );
    
    return $teams ? $teams : array();
}

/**
 * 获取所有公开的冒险团
 * 
 * @param int $limit 限制数量
 * @param int $offset 偏移量
 * @return array 冒险团列表
 */
function hoshinoai_get_public_teams($limit = 10, $offset = 0) {
    global $wpdb;
    
    $teams = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_teams 
            WHERE status = 'active'
            ORDER BY created_at DESC
            LIMIT %d OFFSET %d",
            $limit,
            $offset
        )
    );
    
    return $teams ? $teams : array();
}

/**
 * 检查用户是否有权限管理冒险团
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return bool 有权限返回true，否则返回false
 */
function hoshinoai_can_manage_team($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        return false;
    }
    
    // 团长和副团长有管理权限
    return ($team->leader_id == $user_id || $team->vice_leader_id == $user_id);
}

/**
 * 转让团长职位
 * 
 * @param int $team_id 团队ID
 * @param int $new_leader_id 新团长ID
 * @return bool 成功返回true，失败返回false
 */
function hoshinoai_transfer_leadership($team_id, $new_leader_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($new_leader_id)) {
        return false;
    }
    
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        return false;
    }
    
    // 检查新团长是否是团队成员
    $member = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_members 
            WHERE team_id = %d AND user_id = %d",
            $team_id,
            $new_leader_id
        )
    );
    
    if (!$member) {
        return false;
    }
    
    // 开始事务
    $wpdb->query('START TRANSACTION');
    
    // 更新旧团长的角色
    $old_leader_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        array('role' => 'member'),
        array('team_id' => $team_id, 'user_id' => $team->leader_id)
    );
    
    // 更新新团长的角色
    $new_leader_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        array('role' => 'leader'),
        array('team_id' => $team_id, 'user_id' => $new_leader_id)
    );
    
    // 更新团队的团长信息
    $team_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_teams',
        array(
            'leader_id' => $new_leader_id,
            'updated_at' => current_time('mysql'),
        ),
        array('id' => $team_id)
    );
    
    // 检查是否全部更新成功
    if ($old_leader_updated !== false && $new_leader_updated !== false && $team_updated !== false) {
        $wpdb->query('COMMIT');
        return true;
    } else {
        $wpdb->query('ROLLBACK');
        return false;
    }
}

/**
 * AJAX处理创建冒险团请求
 */
function hoshinoai_ajax_create_team() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_name'])) {
        wp_send_json_error('请输入团队名称');
    }
    
    $user_id = get_current_user_id();
    
    // 检查用户创建的团队数量是否超过限制
    $max_teams = hoshinoai_adventure('max_teams_per_user', 3);
    $user_teams = hoshinoai_get_user_created_teams($user_id);
    
    if (count($user_teams) >= $max_teams) {
        wp_send_json_error('您已达到可创建的团队数量上限');
    }
    
    // 准备数据
    $team_data = array(
        'name' => sanitize_text_field($_POST['team_name']),
        'description' => isset($_POST['team_description']) ? sanitize_textarea_field($_POST['team_description']) : '',
        'leader_id' => $user_id,
    );
    
    // 创建团队
    $team_id = hoshinoai_create_team($team_data);
    
    if ($team_id) {
        wp_send_json_success(array(
            'team_id' => $team_id,
            'message' => '冒险团创建成功！',
        ));
    } else {
        wp_send_json_error('冒险团创建失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_create_team', 'hoshinoai_ajax_create_team');

/**
 * AJAX处理解散冒险团请求
 */
function hoshinoai_ajax_dissolve_team() {
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
        wp_send_json_error('团队ID不能为空');
    }
    
    $team_id = intval($_POST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查该用户是否是团长
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->leader_id != $user_id) {
        wp_send_json_error('您没有权限解散此冒险团');
    }
    
    // 解散团队
    $result = hoshinoai_dissolve_team($team_id);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => '冒险团已成功解散',
        ));
    } else {
        wp_send_json_error('冒险团解散失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_dissolve_team', 'hoshinoai_ajax_dissolve_team');

/**
 * AJAX处理团长职位转让请求
 */
function hoshinoai_ajax_transfer_leadership() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id']) || empty($_POST['new_leader_id'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $new_leader_id = intval($_POST['new_leader_id']);
    $user_id = get_current_user_id();
    
    // 检查该用户是否是团长
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->leader_id != $user_id) {
        wp_send_json_error('您没有权限转让团长职位');
    }
    
    // 转让团长职位
    $result = hoshinoai_transfer_leadership($team_id, $new_leader_id);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => '团长职位转让成功',
        ));
    } else {
        wp_send_json_error('团长职位转让失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_transfer_leadership', 'hoshinoai_ajax_transfer_leadership'); 