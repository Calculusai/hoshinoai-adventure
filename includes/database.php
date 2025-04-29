<?php
/*
 * @Author: 星野爱
 * @Url: 5era.cn
 * @Date: 2025-04-292025-04-29
 * @LastEditTime: 2024-09-18
 * @Description: 数据库处理功能文件，专门管理冒险团插件的数据库操作
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 创建冒险团数据表
 * 包含所有需要的数据表创建函数
 */
class HoshinoAI_Adventure_Database {
    
    /**
     * 初始化数据库
     * 在插件激活时调用
     */
    public static function init() {
        self::create_teams_table();
        self::create_members_table();
        self::create_character_details_table();
        self::create_join_requests_table();
    }
    
    /**
     * 创建团队表
     * 存储冒险团基本信息
     */
    public static function create_teams_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hoshinoai_adventure_teams';
        $charset_collate = $wpdb->get_charset_collate();
        
        // 检查表是否已经存在
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
            $sql = "
                CREATE TABLE $table_name (
                    id int(11) NOT NULL AUTO_INCREMENT COMMENT '团队ID，主键',
                    name varchar(100) NOT NULL COMMENT '团队名称',
                    description text NOT NULL COMMENT '团队描述',
                    leader_id bigint(20) NOT NULL COMMENT '团长用户ID',
                    vice_leader_id bigint(20) DEFAULT NULL COMMENT '副团长用户ID',
                    status varchar(20) NOT NULL DEFAULT 'active' COMMENT '团队状态：active-活跃，inactive-不活跃',
                    avatar_url varchar(255) DEFAULT NULL COMMENT '团队头像URL',
                    is_public tinyint(1) DEFAULT 1 COMMENT '是否公开：1-公开，0-私密',
                    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '创建时间',
                    updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '更新时间',
                    PRIMARY KEY (id)
                ) $charset_collate;
            ";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
    
    /**
     * 创建成员表
     * 存储团队成员信息
     */
    public static function create_members_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hoshinoai_adventure_members';
        $charset_collate = $wpdb->get_charset_collate();
        
        // 检查表是否已经存在
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
            $sql = "
                CREATE TABLE $table_name (
                    id int(11) NOT NULL AUTO_INCREMENT COMMENT '成员ID，主键',
                    team_id int(11) NOT NULL COMMENT '所属团队ID',
                    user_id bigint(20) NOT NULL COMMENT '用户ID',
                    role varchar(20) NOT NULL COMMENT '角色：leader-团长，vice_leader-副团长，member-普通成员',
                    character_name varchar(100) DEFAULT NULL COMMENT '角色名称',
                    character_class varchar(50) DEFAULT NULL COMMENT '角色职业',
                    character_level int(11) DEFAULT 1 COMMENT '角色等级',
                    join_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '加入日期',
                    last_active datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '最后活跃时间',
                    PRIMARY KEY (id),
                    UNIQUE KEY user_team (user_id, team_id) COMMENT '用户在同一团队中只能有一个角色'
                ) $charset_collate;
            ";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
    
    /**
     * 创建角色详情表
     * 存储角色的详细属性和背景
     */
    public static function create_character_details_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hoshinoai_adventure_character_details';
        $charset_collate = $wpdb->get_charset_collate();
        
        // 检查表是否已经存在
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
            $sql = "
                CREATE TABLE $table_name (
                    id int(11) NOT NULL AUTO_INCREMENT COMMENT '角色详情ID，主键',
                    member_id int(11) NOT NULL COMMENT '成员ID，关联members表',
                    user_id bigint(20) NOT NULL COMMENT '用户ID',
                    team_id int(11) NOT NULL COMMENT '团队ID',
                    status varchar(20) NOT NULL DEFAULT 'active' COMMENT '角色状态：active-活跃，inactive-不活跃',
                    strength int(11) DEFAULT 10 COMMENT '力量属性',
                    dexterity int(11) DEFAULT 10 COMMENT '敏捷属性',
                    constitution int(11) DEFAULT 10 COMMENT '体质属性',
                    intelligence int(11) DEFAULT 10 COMMENT '智力属性',
                    wisdom int(11) DEFAULT 10 COMMENT '感知属性',
                    charisma int(11) DEFAULT 10 COMMENT '魅力属性',
                    background text DEFAULT NULL COMMENT '角色背景故事',
                    appearance text DEFAULT NULL COMMENT '角色外貌描述',
                    personality text DEFAULT NULL COMMENT '角色性格特点',
                    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '创建时间',
                    updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '更新时间',
                    PRIMARY KEY (id),
                    UNIQUE KEY member_id (member_id) COMMENT '一个成员只能有一个角色详情',
                    KEY user_team (user_id, team_id) COMMENT '用户团队索引'
                ) $charset_collate;
            ";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
    
    /**
     * 创建入团申请表
     * 存储用户加入团队的申请记录
     */
    public static function create_join_requests_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hoshinoai_adventure_join_requests';
        $charset_collate = $wpdb->get_charset_collate();
        
        // 检查表是否已经存在
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") !== $table_name) {
            $sql = "
                CREATE TABLE $table_name (
                    id int(11) NOT NULL AUTO_INCREMENT COMMENT '申请ID，主键',
                    team_id int(11) NOT NULL COMMENT '团队ID',
                    user_id bigint(20) NOT NULL COMMENT '用户ID',
                    request_message text DEFAULT NULL COMMENT '申请留言',
                    status varchar(20) NOT NULL DEFAULT 'pending' COMMENT '申请状态：pending-待处理，approved-已批准，rejected-已拒绝',
                    request_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT '申请日期',
                    processed_by bigint(20) DEFAULT NULL COMMENT '处理人ID',
                    processed_date datetime DEFAULT NULL COMMENT '处理日期',
                    PRIMARY KEY (id),
                    KEY team_status (team_id, status) COMMENT '按团队和状态查询的索引',
                    UNIQUE KEY user_team_pending (user_id, team_id, status) COMMENT '同一用户对同一团队的特定状态只能有一条记录'
                ) $charset_collate;
            ";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
    
    /**
     * 删除所有插件相关数据表
     * 在插件彻底卸载时调用
     */
    public static function drop_all_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'hoshinoai_adventure_teams',
            $wpdb->prefix . 'hoshinoai_adventure_members',
            $wpdb->prefix . 'hoshinoai_adventure_character_details',
            $wpdb->prefix . 'hoshinoai_adventure_join_requests',
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }
    
    /**
     * 修复数据库表
     * 用于处理数据库表结构变更或错误
     */
    public static function repair_tables($tables = 'all') {
        if ($tables === 'all' || $tables === 'teams' || (is_array($tables) && in_array('teams', $tables))) {
            self::create_teams_table();
        }
        
        if ($tables === 'all' || $tables === 'members' || (is_array($tables) && in_array('members', $tables))) {
            self::create_members_table();
        }
        
        if ($tables === 'all' || $tables === 'details' || (is_array($tables) && in_array('details', $tables))) {
            self::create_character_details_table();
        }
        
        if ($tables === 'all' || $tables === 'requests' || (is_array($tables) && in_array('requests', $tables))) {
            self::create_join_requests_table();
        }
    }
    
    /**
     * 数据库版本迁移
     * 用于处理插件更新时的数据库结构变更
     */
    public static function migrate($old_version, $new_version) {
        // 如果是初次安装，不需要迁移
        if ($old_version === false) {
            return;
        }
        
    }  

}

/**
 * 修复数据表的AJAX处理
 */
function hoshinoai_ajax_repair_tables() {
    // 安全检查
    if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'hoshinoai_repair_tables')) {
        wp_send_json_error('权限验证失败');
    }
    
    // 获取要修复的表
    $tables = $_POST['tables'];
    
    if (!is_array($tables)) {
        $tables = array($tables);
    }
    
    // 修复指定的表
    HoshinoAI_Adventure_Database::repair_tables($tables);
    
    // 生成消息
    $count = count($tables);
    if (in_array('all', $tables)) {
        $count = 4; // 全部4个表
    }
    
    // 构建消息
    $messages = array();
    if (in_array('all', $tables) || in_array('teams', $tables)) {
        $messages[] = '团队表重建完成';
    }
    if (in_array('all', $tables) || in_array('members', $tables)) {
        $messages[] = '成员表重建完成';
    }
    if (in_array('all', $tables) || in_array('details', $tables)) {
        $messages[] = '角色详情表重建完成';
    }
    if (in_array('all', $tables) || in_array('requests', $tables)) {
        $messages[] = '申请表重建完成';
    }
    
    wp_send_json_success('已成功重建 ' . $count . ' 个数据表：<br>' . implode('<br>', $messages));
}
add_action('wp_ajax_hoshinoai_repair_tables', 'hoshinoai_ajax_repair_tables');
