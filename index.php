<?php
/*
Plugin Name: 星野爱冒险团
Plugin URI: https://5era.cn/
Description: 一款用于管理角色扮演游戏冒险团队的WordPress插件
Version: 1.0.0
Author: 星野爱
Author URI: https://5era.cn/
*/
// 定义一个常量，用于存储插件URL
define('hoshinoai_adventure_url', plugins_url('', __FILE__));
// 如果函数hoshinoai_adventure不存在，则定义该函数
if (!function_exists('hoshinoai_adventure')) {
    // 定义一个函数，用于获取插件选项
    function hoshinoai_adventure($option = '', $default = null) {
        // 获取插件选项
        $options = get_option('hoshinoai_adventure');
        // 如果选项存在，则返回选项值，否则返回默认值
        return (isset($options[$option])) ? $options[$option] : $default;
    }
}
// 定义一个函数，用于初始化插件
function hoshinoai_adventure_plugin_init() {
    // 定义需要引入的文件
    $require_once = array(
       '/admin-options.php',
       '/functions.php',
       '/includes/database.php',
       '/includes/teams-functions.php',
       '/includes/members-functions.php',
       '/includes/character-details-functions.php',
    );
    // 遍历文件数组，引入文件
    foreach ( $require_once as $require ) {
        require_once plugin_dir_path( __FILE__ ) . $require;
    }
}
// 在主题设置完成后，调用初始化函数
add_action('after_setup_theme', 'hoshinoai_adventure_plugin_init');

/**
 * 插件激活函数
 * 在插件激活时创建必要的数据表
 */
function hoshinoai_adventure_activation() {
    // 确保database.php已加载
    require_once plugin_dir_path( __FILE__ ) . '/includes/database.php';
    
    // 初始化数据库表
    HoshinoAI_Adventure_Database::init();
    
    // 记录插件版本，用于后续更新时的数据库迁移
    update_option('hoshinoai_adventure_version', '1.0.0');
}
register_activation_hook(__FILE__, 'hoshinoai_adventure_activation');

/**
 * 插件更新检查函数
 * 检查插件版本并执行必要的数据库迁移
 */
function hoshinoai_adventure_version_check() {
    $current_version = get_option('hoshinoai_adventure_version');
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];
    
    // 如果版本不同，执行迁移
    if ($current_version !== $plugin_version) {
        // 确保database.php已加载
        require_once plugin_dir_path( __FILE__ ) . '/includes/database.php';
        
        // 执行数据库迁移
        HoshinoAI_Adventure_Database::migrate($current_version, $plugin_version);
        
        // 更新插件版本
        update_option('hoshinoai_adventure_version', $plugin_version);
    }
}
add_action('plugins_loaded', 'hoshinoai_adventure_version_check');

/**
 * 插件卸载函数
 * 在插件卸载时删除相关数据表和选项
 */
function hoshinoai_adventure_uninstall() {
    // 删除插件选项
    delete_option('hoshinoai_adventure');
    delete_option('hoshinoai_adventure_version');
    
    // 确保database.php已加载
    require_once plugin_dir_path( __FILE__ ) . '/includes/database.php';
    
    // 删除数据表
    HoshinoAI_Adventure_Database::drop_all_tables();
}
register_uninstall_hook(__FILE__, 'hoshinoai_adventure_uninstall'); 