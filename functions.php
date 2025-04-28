<?php
/**
 * 主要功能函数
 * 包含冒险团的核心功能
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}
/**
 * @description: 用户中心侧边栏-按钮组3
 * @param {*}
 * @return {*}
 */
function zib_user_center_page_sidebar_button_3($con)
{

    $buttons = apply_filters('zib_user_center_page_sidebar_button_3_args', $buttons);

    $buttons_html = '';
    foreach ($buttons as $but) {
        if ($but['html']) {
            $buttons_html .= $but['html'];
        } elseif ($but['icon']) {
            $buttons_html .= '<item class="icon-but-' . $but['tab'] . '" data-onclick="[data-target=\'#user-tab-' . $but['tab'] . '\']" ><div class="em16">' . $but['icon'] . '</div><div class="px12 muted-color mt3">' . $but['name'] . '</div></item>';
        }
    }

    $con .= $buttons_html ? '<div class="zib-widget padding-6"><div class="padding-6 ml3">跑团专属</div><div class="flex ac hh text-center icon-but-box user-icon-but-box">' . $buttons_html . '</div></div>' : '';
    return $con;
}
// 检查是否有其他地方使用了不同的过滤器名称
add_filter('user_center_page_sidebar', 'zib_user_center_page_sidebar_button_3',50);

// 添加用户中心侧边栏菜单
function hoshinoai_adventure_sidebar_button($buttons) {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        return $buttons;
    }
    
    // 添加冒险团按钮
    $buttons[] = array(
        'html' => '',
        'icon' => '<svg t="1745770511029" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1538" width="200" height="200"><path d="M1012.834891 615.676012L913.943925 191.401869c-6.380062-22.330218-25.520249-47.850467-51.040498-54.230529L419.489097 0h-15.950156c-19.140187 0-38.280374 6.380062-51.040499 19.140187l-319.003115 296.672897c-15.950156 15.950156-25.520249 47.850467-22.330218 66.990654l105.271028 452.984424c6.380062 22.330218 25.520249 47.850467 51.040499 54.23053l414.704049 130.791277c6.380062 3.190031 12.760125 3.190031 19.140187 3.190031 19.140187 0 38.280374-6.380062 51.040499-19.140187l341.333333-315.813084c15.950156-22.330218 25.520249-51.040498 19.140187-73.370717z m-545.495327-513.595015l306.242991 92.510903c12.760125 3.190031 12.760125 9.570093 0 12.760125l-159.501558 35.090343c-12.760125 3.190031-28.71028-3.190031-38.280374-9.570094l-111.65109-121.221184c-9.570093-9.570093-9.570093-12.760125 3.190031-9.570093z m191.401869 545.495327c3.190031 12.760125-3.190031 19.140187-15.950156 15.950156l-328.573208-102.080997c-12.760125-3.190031-15.950156-12.760125-6.380063-22.330218l252.012461-232.872274c9.570093-9.570093 19.140187-6.380062 22.330218 6.380062l76.560748 334.953271z m-542.305296-296.672897l267.962617-248.82243c9.570093-9.570093 22.330218-6.380062 31.900311 0l133.981309 143.551402c9.570093 9.570093 6.380062 22.330218 0 31.900311l-267.962617 248.82243c-9.570093 9.570093-22.330218 6.380062-31.900312 0l-133.981308-143.551402c-9.570093-9.570093-9.570093-25.520249 0-31.900311z m63.800623 392.373832l-70.180685-309.433022c-3.190031-12.760125 3.190031-15.950156 9.570093-6.380063l111.651091 121.221184c9.570093 9.570093 12.760125 25.520249 9.570093 38.280374l-47.850467 156.311527c-3.190031 12.760125-9.570093 12.760125-12.760125 0z m392.373832 185.021806L224.897196 819.838006c-9.570093-3.190031-15.950156-12.760125-15.950156-22.330218v-6.380062l57.420561-188.211838c3.190031-9.570093 12.760125-15.950156 22.330218-15.950156h6.380063l347.713395 108.461059c12.760125 3.190031 19.140187 15.950156 15.950156 28.710281l-57.420561 188.211838c-3.190031 9.570093-12.760125 15.950156-25.520249 15.950155h-3.190031z m309.433022-255.202492l-232.872274 216.922119c-9.570093 9.570093-12.760125 6.380062-9.570094-6.380063l47.850467-156.311526c3.190031-12.760125 15.950156-25.520249 28.710281-25.520249l159.501557-35.090343c12.760125-6.380062 12.760125-3.190031 6.380063 6.380062z m25.520249-47.850467l-191.401869 44.660436c-12.760125 3.190031-25.520249-3.190031-28.710281-15.950156l-82.94081-354.093458c-3.190031-12.760125 3.190031-25.520249 15.950156-28.71028l191.401869-44.660436c12.760125-3.190031 25.520249 3.190031 28.710281 15.950156l82.94081 354.093458c3.190031 15.950156-3.190031 25.520249-15.950156 28.71028z" fill="#d4237a" p-id="1539"></path></svg>',
        'name' => '我的冒险团',
        'tab'  => 'adventure', // 添加tab属性，确保与主选项卡的标识一致
        'mobile_show' => true,
        'url' => zib_get_user_center_url('adventure'),
    );
    
    return $buttons;
}
add_filter('zib_user_center_page_sidebar_button_3_args', 'hoshinoai_adventure_sidebar_button');

// 添加用户中心主选项卡
function hoshinoai_adventure_main_tab_nav($tabs) {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        return $tabs;
    }
    
    // 添加冒险团选项卡
    $tabs['adventure'] = array(
        'name' => '我的冒险团',
        'icon' => 'fa fa-users',
        'loader' => true
    );
    
    return $tabs;
}
add_filter('user_ctnter_main_tabs_array', 'hoshinoai_adventure_main_tab_nav', 20);

/**
 * 获取冒险团AJAX加载的JavaScript脚本
 * 
 * @return string JavaScript脚本HTML
 */
function hoshinoai_get_adventure_ajax_script() {
    $nonce = wp_create_nonce('hoshinoai_adventure_nonce');
    
    $script = '<script>
        jQuery(function($) {
            // 团队项目点击处理
            $(".team-item-link").on("click", function() {
                var $this = $(this);
                var teamId = $this.data("team-id");
                
                // 设置活动状态
                $(".team-item-link").removeClass("active");
                $this.addClass("active");
                
                // 显示加载中
                $(".col-lg-8").html(\'<div class="text-center" style="padding: 40px;"><i class="loading"></i><div class="muted-2-color mt10">加载中...</div></div>\');
                
                // 通过AJAX加载团队详情
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_get_team_details",
                        team_id: teamId,
                        nonce: "' . $nonce . '"
                    },
                    success: function(response) {
                        if (response.success) {
                            $(".col-lg-8").html(response.data.html);
                            // 更新浏览器历史记录，但不刷新页面
                            var newUrl = updateQueryParam(window.location.href, "team_id", teamId);
                            history.pushState(null, "", newUrl);
                        } else {
                            $(".col-lg-8").html(\'<div class="zib-widget text-center" style="padding: 40px;"><i class="fa fa-exclamation-circle c-red em3x mb20"></i><div>加载失败: \' + response.data + \'</div></div>\');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误:", status, error);
                        $(".col-lg-8").html(\'<div class="zib-widget text-center" style="padding: 40px;"><i class="fa fa-exclamation-circle c-red em3x mb20"></i><div>加载失败，请稍后再试</div></div>\');
                    }
                });
            });
            
            // 辅助函数：更新URL参数
            function updateQueryParam(uri, key, value) {
                var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                var separator = uri.indexOf("?") !== -1 ? "&" : "?";
                if (uri.match(re)) {
                    return uri.replace(re, "$1" + key + "=" + value + "$2");
                } else {
                    return uri + separator + key + "=" + value;
                }
            }
            
            // 如果URL中有team_id参数，自动触发点击
            var urlParams = new URLSearchParams(window.location.search);
            var teamId = urlParams.get("team_id");
            if (teamId) {
                $(".team-item-link[data-team-id=\'" + teamId + "\']").trigger("click");
            }
        });
    </script>';
    
    return $script;
}

// 修改用户中心冒险团选项卡内容
function hoshinoai_adventure_tab_content($content) {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        return $content;
    }
    
    // 获取当前用户ID
    $user_id = get_current_user_id();
    
    // 初始化内容
    $html = '';
    
    // 构建HTML
    $html .= '<div class="row gutters-10">';
    
    // 左侧团队列表和管理
    $html .= '<div class="col-lg-4">';
    $html .= hoshinoai_get_teams_list_html($user_id);
    $html .= '</div>';
    
    // 右侧团队详情
    $html .= '<div class="col-lg-8">';
    
    // 初始加载时，只显示提示信息，除非URL中包含team_id参数
    if (!empty($_GET['team_id']) && !wp_doing_ajax()) {
        $team_id = intval($_GET['team_id']);
        $html .= hoshinoai_get_team_detail_html($team_id, $user_id);
    } else {
        $html .= '<div class="zib-widget text-center" style="padding: 60px 20px;">';
        $html .= '<i class="em3x opacity5" style="margin-bottom: 15px;"><svg t="1745770511029" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1538" width="200" height="200"><path d="M1012.834891 615.676012L913.943925 191.401869c-6.380062-22.330218-25.520249-47.850467-51.040498-54.230529L419.489097 0h-15.950156c-19.140187 0-38.280374 6.380062-51.040499 19.140187l-319.003115 296.672897c-15.950156 15.950156-25.520249 47.850467-22.330218 66.990654l105.271028 452.984424c6.380062 22.330218 25.520249 47.850467 51.040499 54.23053l414.704049 130.791277c6.380062 3.190031 12.760125 3.190031 19.140187 3.190031 19.140187 0 38.280374-6.380062 51.040499-19.140187l341.333333-315.813084c15.950156-22.330218 25.520249-51.040498 19.140187-73.370717z m-545.495327-513.595015l306.242991 92.510903c12.760125 3.190031 12.760125 9.570093 0 12.760125l-159.501558 35.090343c-12.760125 3.190031-28.71028-3.190031-38.280374-9.570094l-111.65109-121.221184c-9.570093-9.570093-9.570093-12.760125 3.190031-9.570093z m191.401869 545.495327c3.190031 12.760125-3.190031 19.140187-15.950156 15.950156l-328.573208-102.080997c-12.760125-3.190031-15.950156-12.760125-6.380063-22.330218l252.012461-232.872274c9.570093-9.570093 19.140187-6.380062 22.330218 6.380062l76.560748 334.953271z m-542.305296-296.672897l267.962617-248.82243c9.570093-9.570093 22.330218-6.380062 31.900311 0l133.981309 143.551402c9.570093 9.570093 6.380062 22.330218 0 31.900311l-267.962617 248.82243c-9.570093 9.570093-22.330218 6.380062-31.900312 0l-133.981308-143.551402c-9.570093-9.570093-9.570093-25.520249 0-31.900311z m63.800623 392.373832l-70.180685-309.433022c-3.190031-12.760125 3.190031-15.950156 9.570093-6.380063l111.651091 121.221184c9.570093 9.570093 12.760125 25.520249 9.570093 38.280374l-47.850467 156.311527c-3.190031 12.760125-9.570093 12.760125-12.760125 0z m392.373832 185.021806L224.897196 819.838006c-9.570093-3.190031-15.950156-12.760125-15.950156-22.330218v-6.380062l57.420561-188.211838c3.190031-9.570093 12.760125-15.950156 22.330218-15.950156h6.380063l347.713395 108.461059c12.760125 3.190031 19.140187 15.950156 15.950156 28.710281l-57.420561 188.211838c-3.190031 9.570093-12.760125 15.950156-25.520249 15.950155h-3.190031z m309.433022-255.202492l-232.872274 216.922119c-9.570093 9.570093-12.760125 6.380062-9.570094-6.380063l47.850467-156.311526c3.190031-12.760125 15.950156-25.520249 28.710281-25.520249l159.501557-35.090343c12.760125-6.380062 12.760125-3.190031 6.380063 6.380062z m25.520249-47.850467l-191.401869 44.660436c-12.760125 3.190031-25.520249-3.190031-28.710281-15.950156l-82.94081-354.093458c-3.190031-12.760125 3.190031-25.520249 15.950156-28.71028l191.401869-44.660436c12.760125-3.190031 25.520249 3.190031 28.710281 15.950156l82.94081 354.093458c3.190031 15.950156-3.190031 25.520249-15.950156 28.71028z" fill="#d4237a" p-id="1539"></path></svg></i>';
        $html .= '<div class="font-bold">选择一个冒险团查看详情</div>';
        $html .= '<div class="muted-2-color em09">或者创建一个新的冒险团开始您的旅程</div>';
        $html .= '<div class="muted-2-color em09">注意！请不要随意创建</div>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    $html .= '</div>';
    
    // 添加JavaScript
    $html .= hoshinoai_get_adventure_ajax_script();
    
    return $html;
}
add_filter('main_user_tab_content_adventure', 'hoshinoai_adventure_tab_content', 20);

/**
 * 获取团队列表HTML
 *
 * @param int $user_id 用户ID
 * @return string HTML
 */
function hoshinoai_get_teams_list_html($user_id) {
    // 初始化内容
    $html = '';
    
    // 获取创建按钮
    $create_btn = hoshinoai_get_create_team_button();
    
    // 获取用户创建和加入的团队
    $created_teams = hoshinoai_get_user_created_teams($user_id);
    $joined_teams = hoshinoai_get_user_joined_teams($user_id);
    
    // 构建团队列表HTML
    $html .= '<div class="zib-widget">';
    $html .= '<div class="title-h-left">';
    $html .= '<b>我的冒险团</b>';
    $html .= '<div class="pull-right">' . $create_btn . '</div>';
    $html .= '</div>';
    
    // 如果没有团队，显示提示信息
    if (empty($joined_teams)) {
        $html .= '<div class="text-center muted-color padding-h10" style="padding: 30px 0;">';
        $html .= '<i class="fa fa-exclamation-circle em2x" style="opacity: .3;margin-bottom: 10px;"></i>';
        $html .= '<div>您暂未加入任何冒险团</div>';
        $html .= '<div class="mt10">' . $create_btn . '</div>';
        $html .= '</div>';
    } else {
        // 显示团队列表
        $html .= '<div class="list-group theme-box">';
        
        foreach ($joined_teams as $team) {
            $is_leader = ($team->leader_id == $user_id);
            $is_vice_leader = ($team->vice_leader_id == $user_id);
            $active = (!empty($_GET['team_id']) && $_GET['team_id'] == $team->id) ? ' active' : '';
            
            $html .= '<a href="javascript:;" data-team-id="' . esc_attr($team->id) . '" class="team-item-link list-group-item' . $active . '">';
            $html .= '<div class="flex ac">';
            
            // 团队名称
            $html .= '<div class="flex1 ellipsis mr10">';
            $html .= '<div class="text-ellipsis font-bold">' . esc_html($team->name) . '</div>';
            
            // 角色标签
            if ($is_leader) {
                $html .= '<div class="badg badg-sm c-red">团长</div>';
            } elseif ($is_vice_leader) {
                $html .= '<div class="badg badg-sm c-blue">副团长</div>';
            } else {
                $html .= '<div class="badg badg-sm c-green">成员</div>';
            }
            
            $html .= '</div>';
            
            // 成员数量
            $member_count = count(hoshinoai_get_team_members($team->id));
            $html .= '<div class="em09 muted-2-color"><i class="fa fa-user mr3"></i>' . $member_count . '</div>';
            
            $html .= '</div>';
            $html .= '</a>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    // 添加公开团队列表
    $html .= hoshinoai_get_public_teams_list_html($user_id);
    
    return $html;
}

/**
 * 获取创建冒险团的按钮
 *
 * @return string 按钮HTML
 */
function hoshinoai_get_create_team_button() {
    // 安全检查
    if (!is_user_logged_in()) {
        return '';
    }
    
    // 构建按钮
    $args = array(
        'tag' => 'a',
        'class' => 'but c-blue but-sm',
        'text' => '<i class="fa fa-plus mr10"></i>创建冒险团',
        'data_class' => 'modal-mini',
        'query_arg' => array(
            'action' => 'hoshinoai_create_team_modal'
        )
    );
    
    return zib_get_refresh_modal_link($args);
} 
/**
 * 获取公开团队列表HTML
 *
 * @param int $user_id 用户ID
 * @return string HTML
 */
function hoshinoai_get_public_teams_list_html($user_id) {
    // 初始化内容
    $html = '';
    
    // 获取公开团队
    $public_teams = hoshinoai_get_public_teams(10);
    
    // 过滤掉用户已加入的团队
    $joined_teams = hoshinoai_get_user_joined_teams($user_id);
    $joined_team_ids = array();
    
    foreach ($joined_teams as $team) {
        $joined_team_ids[] = $team->id;
    }
    
    $filtered_teams = array();
    foreach ($public_teams as $team) {
        if (!in_array($team->id, $joined_team_ids)) {
            $filtered_teams[] = $team;
        }
    }
    
    // 如果没有可加入的公开团队，不显示此区块
    if (empty($filtered_teams)) {
        return $html;
    }
    
    // 构建公开团队列表HTML
    $html .= '<div class="zib-widget mt20">';
    $html .= '<div class="title-h-left">';
    $html .= '<b>发现冒险团</b>';
    $html .= '</div>';
    
    $html .= '<div class="list-group theme-box">';
    
    foreach ($filtered_teams as $team) {
        $html .= '<div class="list-group-item">';
        $html .= '<div class="flex ac jsb">';
        
        // 团队名称
        $html .= '<div class="flex1 mr10">';
        $html .= '<div class="text-ellipsis font-bold">' . esc_html($team->name) . '</div>';
        $html .= '<div class="em09 muted-2-color text-ellipsis">' . esc_html(wp_trim_words($team->description, 20)) . '</div>';
        $html .= '</div>';
        
        // 加入按钮
        $join_args = array(
            'tag' => 'a',
            'class' => 'but c-blue but-sm',
            'text' => '加入',
            'data_class' => 'modal-mini',
            'query_arg' => array(
                'action' => 'hoshinoai_join_team_modal',
                'team_id' => $team->id
            )
        );
        $html .= zib_get_refresh_modal_link($join_args);
        
        $html .= '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * 获取团队详情页面HTML
 *
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return string HTML
 */
function hoshinoai_get_team_detail_html($team_id, $user_id) {
    // 获取团队信息
    $team = hoshinoai_get_team($team_id);
    
    // 如果团队不存在或已解散，显示错误信息
    if (!$team || $team->status !== 'active') {
        return '<div class="zib-widget text-center" style="padding: 40px 20px;">';
        return '<i class="fa fa-exclamation-circle em3x c-red opacity5" style="margin-bottom: 15px;"></i>';
        return '<div class="font-bold">该冒险团不存在或已解散</div>';
        return '</div>';
    }
    
    // 检查用户是否是团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        // 用户不是成员，显示加入按钮
        $html = '<div class="zib-widget">';
        $html .= '<div class="text-center padding-h10" style="padding: 40px 0;">';
        $html .= '<div class="mb20">';
        $html .= '<div class="font-bold mb10">' . esc_html($team->name) . '</div>';
        $html .= '<div class="muted-2-color em09">' . esc_html($team->description) . '</div>';
        $html .= '</div>';
        
        // 加入按钮
        $join_args = array(
            'tag' => 'a',
            'class' => 'but c-blue',
            'text' => '<i class="fa fa-plus-circle mr10"></i>加入该冒险团',
            'data_class' => 'modal-mini',
            'query_arg' => array(
                'action' => 'hoshinoai_join_team_modal',
                'team_id' => $team_id
            )
        );
        $html .= zib_get_refresh_modal_link($join_args);
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    // 用户是成员，显示团队详情
    $html = '';
    
    // 团队信息头部
    $html .= '<div class="zib-widget">';
    $html .= '<div class="title-h-left">';
    $html .= '<b>' . esc_html($team->name) . '</b>';
    
    // 团队管理按钮
    if (hoshinoai_can_manage_team($team_id, $user_id)) {
        $manage_args = array(
            'tag' => 'a',
            'class' => 'but c-blue but-sm',
            'text' => '<i class="fa fa-cog mr6"></i>团队管理',
            'data_class' => 'modal-mini',
            'query_arg' => array(
                'action' => 'hoshinoai_team_manage_modal',
                'team_id' => $team_id
            )
        );
        $html .= '<div class="pull-right">' . zib_get_refresh_modal_link($manage_args) . '</div>';
    }
    
    $html .= '</div>';
    
    // 团队描述
    if (!empty($team->description)) {
        $html .= '<div class="em09 muted-2-color mb10 padding-10">' . esc_html($team->description) . '</div>';
    }
    
    // 我的角色信息
    $html .= '<div class="mb20 mt10">';
    $html .= '<div class="title-h-left"><b>我的角色</b>';
    
    // 编辑角色按钮
    $edit_character_args = array(
        'tag' => 'a',
        'class' => 'but c-blue but-sm',
        'text' => '<i class="fa fa-edit mr6"></i>编辑',
        'data_class' => 'modal-mini',
        'query_arg' => array(
            'action' => 'hoshinoai_edit_character_modal',
            'team_id' => $team_id
        )
    );
    $html .= '<div class="pull-right">' . zib_get_refresh_modal_link($edit_character_args) . '</div>';
    
    $html .= '</div>';
    
    // 角色信息卡片
    $html .= '<div class="card-pill flex ac">';
    
    // 用户头像
    $html .= '<div class="mr10 avatar-mini">' . zib_get_data_avatar($user_id) . '</div>';
    
    $html .= '<div class="flex1">';
    
    // 角色名称，如果没有设置则显示用户名
    if (!empty($member->character_name)) {
        $html .= '<div class="font-bold mb6">' . esc_html($member->character_name) . '</div>';
    } else {
        $user_data = get_userdata($user_id);
        $html .= '<div class="font-bold mb6">' . esc_html($user_data->display_name) . '</div>';
    }
    
    // 角色职业和等级
    $character_info = array();
    
    if (!empty($member->character_class)) {
        $character_info[] = esc_html($member->character_class);
    }
    
    if (!empty($member->character_level)) {
        $character_info[] = '等级 ' . esc_html($member->character_level);
    }
    
    if (!empty($character_info)) {
        $html .= '<div class="em09 muted-2-color">' . implode(' · ', $character_info) . '</div>';
    } else {
        $html .= '<div class="em09 muted-2-color">未设置角色信息</div>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    $html .= '</div>';
    
    // 团队成员
    $html .= '<div class="mt20">';
    $html .= '<div class="title-h-left"><b>团队成员</b>';
    
    // 团长或副团长可以添加成员
    if (hoshinoai_can_manage_team($team_id, $user_id)) {
        $add_member_args = array(
            'tag' => 'a',
            'class' => 'but c-blue but-sm',
            'text' => '<i class="fa fa-plus mr6"></i>添加',
            'data_class' => 'modal-mini',
            'query_arg' => array(
                'action' => 'hoshinoai_add_member_modal',
                'team_id' => $team_id
            )
        );
        $html .= '<div class="pull-right">' . zib_get_refresh_modal_link($add_member_args) . '</div>';
    }
    
    $html .= '</div>';
    
    // 如果是团长或副团长，显示待处理的入团申请
    if (hoshinoai_can_manage_team($team_id, $user_id)) {
        $pending_requests = hoshinoai_get_team_join_requests($team_id);
        
        if (!empty($pending_requests)) {
            $html .= '<div class="mb20 mt10 zib-widget padding-10">';
            $html .= '<div class="title-h-left c-yellow"><i class="fa fa-bell mr6"></i><b>待处理入团申请</b></div>';
            $html .= '<div class="list-group">';
            
            foreach ($pending_requests as $request) {
                $request_user = get_userdata($request->user_id);
                
                if (!$request_user) {
                    continue;
                }
                
                $html .= '<div class="list-group-item flex ac jsb">';
                
                // 申请者信息
                $html .= '<div class="flex ac">';
                $html .= '<div class="avatar-img avatar-mini mr10">' . zib_get_data_avatar($request->user_id) . '</div>';
                $html .= '<div>';
                $html .= '<div class="font-bold">' . esc_html($request_user->display_name) . '</div>';
                $html .= '<div class="em09 muted-2-color">申请时间: ' . date('Y-m-d H:i', strtotime($request->request_date)) . '</div>';
                $html .= '</div>';
                $html .= '</div>';
                
                // 操作按钮
                $html .= '<div class="flex ac">';
                
                // 批准按钮
                $html .= '<button type="button" class="but but-sm c-green process-join-request mr6" data-request-id="' . $request->id . '" data-action="approve">';
                $html .= '<i class="fa fa-check mr6"></i>批准';
                $html .= '</button>';
                
                // 拒绝按钮
                $html .= '<button type="button" class="but but-sm c-red process-join-request" data-request-id="' . $request->id . '" data-action="reject">';
                $html .= '<i class="fa fa-close mr6"></i>拒绝';
                $html .= '</button>';
                
                $html .= '</div>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
            $html .= '</div>';
            
            // 添加处理申请的JavaScript
            $html .= '<script>
                jQuery(function($) {
                    $(".process-join-request").on("click", function() {
                        var $btn = $(this);
                        var requestId = $btn.data("request-id");
                        var actionType = $btn.data("action");
                        
                        if (confirm("确认" + (actionType === "approve" ? "批准" : "拒绝") + "该申请？")) {
                            $btn.html(\'<i class="loading mr6"></i>处理中...\').attr("disabled", true);
                            
                            $.ajax({
                                url: "/wp-admin/admin-ajax.php",
                                type: "POST",
                                data: {
                                    action: "hoshinoai_ajax_process_join_request",
                                    nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                                    request_id: requestId,
                                    request_action: actionType
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.success) {
                                        notyf(response.data.message, "success");
                                        // 刷新页面
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        notyf(response.data, "danger");
                                        $btn.html(actionType === "approve" ? \'<i class="fa fa-check mr6"></i>批准\' : \'<i class="fa fa-close mr6"></i>拒绝\').attr("disabled", false);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.log("AJAX错误:", status, error);
                                    console.log("服务器响应:", xhr.responseText);
                                    notyf("网络错误，请稍后再试", "danger");
                                    $btn.html(actionType === "approve" ? \'<i class="fa fa-check mr6"></i>批准\' : \'<i class="fa fa-close mr6"></i>拒绝\').attr("disabled", false);
                                }
                            });
                        }
                    });
                });
            </script>';
        }
    }
    
    // 获取所有成员
    $members = hoshinoai_get_team_members($team_id);
    
    if (empty($members)) {
        $html .= '<div class="text-center muted-color padding-h10">还没有团队成员</div>';
    } else {
        $html .= '<div class="row gutters-5">';
        
        foreach ($members as $member_item) {
            $member_user_id = $member_item->user_id;
            $member_user = get_userdata($member_user_id);
            
            if (!$member_user) {
                continue;
            }
            
            $html .= '<div class="col-6 col-sm-4 col-md-3 mb10">';
            $html .= '<div class="zib-widget padding-10">';
            
            // 成员角色标识
            if ($member_item->role === 'leader') {
                $role_badge = '<div class="badg c-red">团长</div>';
            } elseif ($member_item->role === 'vice_leader') {
                $role_badge = '<div class="badg c-blue">副团长</div>';
            } else {
                $role_badge = '<div class="badg c-green">成员</div>';
            }
            
            // 用户信息
            $html .= '<div class="flex ac jsb mb6">';
            $html .= '<div class="avatar-img avatar-mini">' . zib_get_data_avatar($member_user_id) . '</div>';
            $html .= $role_badge;
            $html .= '</div>';
            
            // 角色名称，如果没有设置则显示用户名
            if (!empty($member_item->character_name)) {
                $html .= '<div class="font-bold mt6 text-center">' . esc_html($member_item->character_name) . '</div>';
            } else {
                $html .= '<div class="font-bold mt6 text-center">' . esc_html($member_user->display_name) . '</div>';
            }
            
            // 角色信息
            $member_info = array();
            
            if (!empty($member_item->character_class)) {
                $member_info[] = esc_html($member_item->character_class);
            }
            
            if (!empty($member_item->character_level)) {
                $member_info[] = 'Lv.' . esc_html($member_item->character_level);
            }
            
            if (!empty($member_info)) {
                $html .= '<div class="em09 muted-2-color text-center">' . implode(' · ', $member_info) . '</div>';
            }
            
            // 查看详情按钮
            $view_details_args = array(
                'tag' => 'a',
                'class' => 'but c-blue but-sm but-block mt6',
                'text' => '查看',
                'data_class' => 'modal-mini',
                'query_arg' => array(
                    'action' => 'hoshinoai_view_character_modal',
                    'team_id' => $team_id,
                    'user_id' => $member_user_id
                )
            );
            $html .= zib_get_refresh_modal_link($view_details_args);
            
            // 添加管理按钮（如果有权限）
            if (hoshinoai_can_manage_team($team_id, $user_id) && $member_user_id != $user_id && $member_item->role !== 'leader') {
                $manage_member_args = array(
                    'tag' => 'a',
                    'class' => 'but c-yellow but-sm but-block mt6',
                    'text' => '管理',
                    'data_class' => 'modal-mini',
                    'query_arg' => array(
                        'action' => 'hoshinoai_manage_member_modal',
                        'team_id' => $team_id,
                        'user_id' => $member_user_id
                    )
                );
                $html .= zib_get_refresh_modal_link($manage_member_args);
            }
            
            $html .= '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    // 角色详情功能（如果启用）
    $enable_character_details = hoshinoai_adventure('enable_character_details', true);
    
    if ($enable_character_details) {
        $html .= '<div class="zib-widget mt20">';
        $html .= '<div class="title-h-left"><b>角色详情</b></div>';
        
        $details = hoshinoai_get_character_details_by_team_user($team_id, $user_id);
        
        if (!$details) {
            // 未设置角色详情，显示表单
            $html .= hoshinoai_get_character_details_form($team_id, $user_id);
        } else {
            // 已设置角色详情，显示详情和编辑按钮
            $html .= '<div class="text-right mb10">';
            $html .= '<a href="javascript:;" class="toggle-details-form but c-blue but-sm"><i class="fa fa-edit mr6"></i>编辑角色详情</a>';
            $html .= '</div>';
            
            $html .= '<div class="character-details-display">';
            $html .= hoshinoai_get_character_details_display($team_id, $user_id);
            $html .= '</div>';
            
            $html .= '<div class="character-details-form" style="display: none;">';
            $html .= hoshinoai_get_character_details_form($team_id, $user_id);
            $html .= '</div>';
            
            // 添加JavaScript切换表单和显示
            $html .= '<script>
                jQuery(function($) {
                    $(".toggle-details-form").on("click", function() {
                        $(".character-details-display, .character-details-form").toggle();
                        var $btn = $(this);
                        if ($(".character-details-form").is(":visible")) {
                            $btn.html("<i class=\"fa fa-eye mr6\"></i>查看角色详情");
                        } else {
                            $btn.html("<i class=\"fa fa-edit mr6\"></i>编辑角色详情");
                        }
                    });
                });
            </script>';
        }
        
        $html .= '</div>';
    }
    
    // 如果不是团长，显示退出团队按钮
    if ($team->leader_id != $user_id) {
        $html .= '<div class="text-center mt20">';
        $leave_args = array(
            'tag' => 'a',
            'class' => 'but c-red',
            'text' => '<i class="fa fa-sign-out mr10"></i>退出冒险团',
            'data_class' => 'modal-mini',
            'query_arg' => array(
                'action' => 'hoshinoai_leave_team_modal',
                'team_id' => $team_id
            )
        );
        $html .= zib_get_refresh_modal_link($leave_args);
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * 创建冒险团模态框
 */
function hoshinoai_create_team_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    $user_id = get_current_user_id();
    
    // 检查用户创建的团队数量是否超过限制
    $max_teams = hoshinoai_adventure('max_teams_per_user', 3);
    $user_teams = hoshinoai_get_user_created_teams($user_id);
    
    if (count($user_teams) >= $max_teams) {
        $content = '<div class="text-center">';
        $content .= '<i class="fa fa-exclamation-circle c-yellow em3x mb20"></i>';
        $content .= '<div class="em2x mb10">创建限制</div>';
        $content .= '<div class="muted-2-color">您已达到可创建的冒险团数量上限（' . $max_teams . '个）</div>';
        $content .= '</div>';
        
        echo zib_get_modal_colorful_header('c-yellow', '<i class="fa fa-exclamation-circle"></i>', '创建冒险团');
        echo '<div class="modal-body">' . $content . '</div>';
        exit;
    }
    
    // 表单HTML
    $content = '<form id="create-team-form">';
    $content .= '<input type="hidden" name="action" value="hoshinoai_ajax_create_team">';
    $content .= '<input type="hidden" name="nonce" value="' . wp_create_nonce('hoshinoai_adventure_nonce') . '">';
    
    $content .= '<div class="form-group">';
    $content .= '<label for="team_name">冒险团名称</label>';
    $content .= '<input type="text" id="team_name" name="team_name" class="form-control" required>';
    $content .= '</div>';
    
    $content .= '<div class="form-group">';
    $content .= '<label for="team_description">冒险团描述</label>';
    $content .= '<textarea id="team_description" name="team_description" class="form-control" rows="3"></textarea>';
    $content .= '</div>';
    
    $content .= '<div class="form-group text-center">';
    $content .= '<button type="submit" class="but c-blue">创建冒险团</button>';
    $content .= '</div>';
    $content .= '</form>';
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            $("#create-team-form").on("submit", function(e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $form.find("button[type=submit]");
                
                $btn.html(\'<i class="loading mr10"></i>创建中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: $form.serializeArray(),
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 打印出表单数据便于调试
                        console.log("发送的表单数据:", $form.serialize());
                        var formData = {};
                        $.each($form.serializeArray(), function(i, field) {
                            formData[field.name] = field.value;
                        });
                        console.log("表单数据对象:", formData);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("冒险团创建成功", "success");
                            setTimeout(function() {
                                // 使用已有的AJAX函数加载团队详情，而不是刷新页面
                                $.ajax({
                                    url: "/wp-admin/admin-ajax.php",
                                    type: "POST",
                                    data: {
                                        action: "hoshinoai_ajax_get_team_details",
                                        team_id: response.data.team_id,
                                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '"
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            $(".col-lg-8").html(response.data.html);
                                            
                                            // 更新URL
                                            var newUrl = window.location.pathname + "?page=adventure&tab=adventure&team_id=" + response.data.team_id;
                                            history.pushState(null, "", newUrl);
                                            
                                            // 刷新左侧团队列表
                                            location.reload();
                                        }
                                    }
                                });
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html("创建冒险团").attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html("创建冒险团").attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-plus-circle"></i>', '创建冒险团');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_create_team_modal', 'hoshinoai_create_team_modal');

/**
 * 编辑角色信息模态框
 */
function hoshinoai_edit_character_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    $user_id = get_current_user_id();
    
    // 验证必要参数
    if (empty($_REQUEST['team_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    
    // 检查是否为团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        echo '<div class="text-center">您不是该团队成员</div>';
        exit;
    }
    
    // 表单HTML
    $content = '<form id="edit-character-form">';
    $content .= '<input type="hidden" name="action" value="hoshinoai_ajax_update_character">';
    $content .= '<input type="hidden" name="nonce" value="' . wp_create_nonce('hoshinoai_adventure_nonce') . '">';
    $content .= '<input type="hidden" name="team_id" value="' . esc_attr($team_id) . '">';
    
    $content .= '<div class="form-group">';
    $content .= '<label for="character_name">角色名称</label>';
    $content .= '<input type="text" id="character_name" name="character_name" class="form-control" value="' . esc_attr($member->character_name) . '">';
    $content .= '</div>';
    
    // 职业输入/选择
    $content .= '<div class="form-group">';
    $content .= '<label for="character_class">角色职业</label>';
    
    // 检查是否允许自定义职业
    $allow_custom_class = hoshinoai_adventure('allow_custom_class', true);
    
    if ($allow_custom_class) {
        // 允许自定义职业，使用文本输入框
        $content .= '<input type="text" id="character_class" name="character_class" class="form-control" value="' . esc_attr($member->character_class) . '">';
    } else {
        // 不允许自定义职业，使用下拉选择框
        $character_classes = hoshinoai_get_character_classes();
        
        $content .= '<select id="character_class" name="character_class" class="form-control">';
        $content .= '<option value="">请选择职业</option>';
        
        foreach ($character_classes as $class) {
            $selected = ($member->character_class === $class) ? ' selected' : '';
            $content .= '<option value="' . esc_attr($class) . '"' . $selected . '>' . esc_html($class) . '</option>';
        }
        
        $content .= '</select>';
    }
    
    $content .= '</div>';
    
    // 等级输入
    $content .= '<div class="form-group">';
    $content .= '<label for="character_level">角色等级</label>';
    $content .= '<input type="number" id="character_level" name="character_level" class="form-control" min="1" max="20" value="' . esc_attr($member->character_level) . '">';
    $content .= '</div>';
    
    $content .= '<div class="form-group text-center">';
    $content .= '<button type="submit" class="but c-blue">保存角色信息</button>';
    $content .= '</div>';
    $content .= '</form>';
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            $("#edit-character-form").on("submit", function(e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $form.find("button[type=submit]");
                
                $btn.html(\'<i class="loading mr10"></i>保存中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: $form.serializeArray(),
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 打印出表单数据便于调试
                        console.log("发送的表单数据:", $form.serialize());
                        var formData = {};
                        $.each($form.serializeArray(), function(i, field) {
                            formData[field.name] = field.value;
                        });
                        console.log("表单数据对象:", formData);
                    },
                    success: function(response) {
                        console.log("服务器响应:", response);
                        if (response.success) {
                            notyf("角色信息已更新", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html("保存角色信息").attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html("保存角色信息").attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-user-edit"></i>', '编辑角色信息');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_edit_character_modal', 'hoshinoai_edit_character_modal');

/**
 * 添加成员模态框
 */
function hoshinoai_add_member_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查是否有权限管理团队
    if (!hoshinoai_can_manage_team($team_id, $user_id)) {
        echo '<div class="text-center">您没有权限执行此操作</div>';
        exit;
    }
    
    // 检查团队成员数量是否达到上限
    $max_members = hoshinoai_adventure('max_team_members', 20);
    $team_members = hoshinoai_get_team_members($team_id);
    
    if (count($team_members) >= $max_members) {
        $content = '<div class="text-center">';
        $content .= '<i class="fa fa-exclamation-circle c-yellow em3x mb20"></i>';
        $content .= '<div class="em2x mb10">成员限制</div>';
        $content .= '<div class="muted-2-color">团队成员数量已达上限（' . $max_members . '人）</div>';
        $content .= '</div>';
        
        echo zib_get_modal_colorful_header('c-yellow', '<i class="fa fa-exclamation-circle"></i>', '添加成员');
        echo '<div class="modal-body">' . $content . '</div>';
        exit;
    }
    
    // 表单HTML
    $content = '<form id="add-member-form">';
    $content .= '<input type="hidden" name="action" value="hoshinoai_ajax_add_member">';
    $content .= '<input type="hidden" name="nonce" value="' . wp_create_nonce('hoshinoai_adventure_nonce') . '">';
    $content .= '<input type="hidden" name="team_id" value="' . esc_attr($team_id) . '">';
    
    $content .= '<div class="form-group">';
    $content .= '<label for="username">用户名或邮箱</label>';
    $content .= '<input type="text" id="username" name="username" class="form-control" required>';
    $content .= '<div class="em09 muted-2-color mt6">输入用户的登录名或邮箱地址</div>';
    $content .= '</div>';
    
    $content .= '<div class="form-group text-center">';
    $content .= '<button type="submit" class="but c-blue">添加成员</button>';
    $content .= '</div>';
    $content .= '</form>';
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            $("#add-member-form").on("submit", function(e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $form.find("button[type=submit]");
                
                $btn.html(\'<i class="loading mr10"></i>添加中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: $form.serializeArray(),
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 打印出表单数据便于调试
                        console.log("发送的表单数据:", $form.serialize());
                        var formData = {};
                        $.each($form.serializeArray(), function(i, field) {
                            formData[field.name] = field.value;
                        });
                        console.log("表单数据对象:", formData);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("成员添加成功", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html("添加成员").attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html("添加成员").attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-user-plus"></i>', '添加成员');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_add_member_modal', 'hoshinoai_add_member_modal');

/**
 * 查看角色详情模态框
 */
function hoshinoai_view_character_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id']) || empty($_REQUEST['user_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    $target_user_id = intval($_REQUEST['user_id']);
    $current_user_id = get_current_user_id();
    
    // 检查当前用户是否是团队成员
    $current_member = hoshinoai_get_member($team_id, $current_user_id);
    
    if (!$current_member) {
        echo '<div class="text-center">您不是该团队成员</div>';
        exit;
    }
    
    // 获取目标成员信息
    $target_member = hoshinoai_get_member($team_id, $target_user_id);
    
    if (!$target_member) {
        echo '<div class="text-center">找不到该成员</div>';
        exit;
    }
    
    // 获取目标用户信息
    $target_user = get_userdata($target_user_id);
    
    if (!$target_user) {
        echo '<div class="text-center">找不到该用户</div>';
        exit;
    }
    
    // 构建HTML
    $content = '<div class="text-center mb20">';
    $content .= '<div class="avatar-img">' . zib_get_data_avatar($target_user_id, 80) . '</div>';
    
    // 显示角色名，如果没有设置则显示用户名
    if (!empty($target_member->character_name)) {
        $content .= '<h4 class="mt10">' . esc_html($target_member->character_name) . '</h4>';
    } else {
        $content .= '<h4 class="mt10">' . esc_html($target_user->display_name) . '</h4>';
    }
    
    // 显示角色职业和等级
    $character_info = array();
    
    if (!empty($target_member->character_class)) {
        $character_info[] = esc_html($target_member->character_class);
    }
    
    if (!empty($target_member->character_level)) {
        $character_info[] = '等级 ' . esc_html($target_member->character_level);
    }
    
    if (!empty($character_info)) {
        $content .= '<div class="muted-color">' . implode(' · ', $character_info) . '</div>';
    }
    
    // 显示团队角色
    if ($target_member->role === 'leader') {
        $content .= '<div class="badg c-red mt6">团长</div>';
    } elseif ($target_member->role === 'vice_leader') {
        $content .= '<div class="badg c-blue mt6">副团长</div>';
    } else {
        $content .= '<div class="badg c-green mt6">成员</div>';
    }
    
    $content .= '</div>';
    
    // 显示角色详情
    $enable_character_details = hoshinoai_adventure('enable_character_details', true);
    
    if ($enable_character_details) {
        $details = hoshinoai_get_character_details_by_team_user($team_id, $target_user_id);
        
        if ($details) {
            // 属性值
            $content .= '<div class="mb20"><div class="title-h-left"><b>角色属性</b></div>';
            $content .= '<div class="row gutters-5">';
            
            $attributes = array(
                'strength' => '力量',
                'dexterity' => '敏捷',
                'constitution' => '体质',
                'intelligence' => '智力',
                'wisdom' => '感知',
                'charisma' => '魅力',
            );
            
            foreach ($attributes as $attribute => $label) {
                $value = $details->$attribute;
                $modifier = hoshinoai_calculate_attribute_modifier($value);
                $modifier_text = hoshinoai_get_modifier_text($modifier);
                
                $content .= '<div class="col-4 col-sm-4 mb10">';
                $content .= '<div class="text-center">';
                $content .= '<div class="em09">' . esc_html($label) . '</div>';
                $content .= '<div class="em12 font-bold">' . esc_html($value) . ' <span class="em09 muted-color">(' . esc_html($modifier_text) . ')</span></div>';
                $content .= '</div>';
                $content .= '</div>';
            }
            
            $content .= '</div></div>';
            
            // 背景信息
            if ($details->background || $details->appearance || $details->personality) {
                $content .= '<div class="mb10"><div class="title-h-left"><b>角色背景</b></div>';
                
                if ($details->background) {
                    $content .= '<div class="mb15">';
                    $content .= '<div class="em09 muted-2-color mb6">背景故事</div>';
                    $content .= '<div class="text-wrap">' . wpautop(esc_html($details->background)) . '</div>';
                    $content .= '</div>';
                }
                
                if ($details->appearance) {
                    $content .= '<div class="mb15">';
                    $content .= '<div class="em09 muted-2-color mb6">外观描述</div>';
                    $content .= '<div class="text-wrap">' . wpautop(esc_html($details->appearance)) . '</div>';
                    $content .= '</div>';
                }
                
                if ($details->personality) {
                    $content .= '<div class="mb15">';
                    $content .= '<div class="em09 muted-2-color mb6">性格特点</div>';
                    $content .= '<div class="text-wrap">' . wpautop(esc_html($details->personality)) . '</div>';
                    $content .= '</div>';
                }
                
                $content .= '</div>';
            }
        } else {
            $content .= '<div class="text-center muted-color mb20">该角色尚未填写详情信息</div>';
        }
    }
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-user"></i>', '角色详情');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_view_character_modal', 'hoshinoai_view_character_modal');

/**
 * 管理成员模态框
 */
function hoshinoai_manage_member_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id']) || empty($_REQUEST['user_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    $target_user_id = intval($_REQUEST['user_id']);
    $current_user_id = get_current_user_id();
    
    // 检查当前用户是否有权限管理团队
    if (!hoshinoai_can_manage_team($team_id, $current_user_id)) {
        echo '<div class="text-center">您没有权限执行此操作</div>';
        exit;
    }
    
    // 获取团队和目标成员信息
    $team = hoshinoai_get_team($team_id);
    $target_member = hoshinoai_get_member($team_id, $target_user_id);
    $target_user = get_userdata($target_user_id);
    
    if (!$team || !$target_member || !$target_user) {
        echo '<div class="text-center">找不到相关信息</div>';
        exit;
    }
    
    // 不能管理团长
    if ($target_member->role === 'leader') {
        echo '<div class="text-center">不能管理团长</div>';
        exit;
    }
    
    // 构建HTML
    $content = '<div class="text-center mb20">';
    $content .= '<div class="avatar-img">' . zib_get_data_avatar($target_user_id, 80) . '</div>';
    
    // 显示角色名，如果没有设置则显示用户名
    if (!empty($target_member->character_name)) {
        $content .= '<h4 class="mt10">' . esc_html($target_member->character_name) . '</h4>';
    } else {
        $content .= '<h4 class="mt10">' . esc_html($target_user->display_name) . '</h4>';
    }
    
    // 显示团队角色
    if ($target_member->role === 'vice_leader') {
        $content .= '<div class="badg c-blue mt6">副团长</div>';
    } else {
        $content .= '<div class="badg c-green mt6">成员</div>';
    }
    
    $content .= '</div>';
    
    // 管理操作
    $content .= '<div class="text-center">';
    
    // 设置/取消副团长（仅团长可操作）
    if ($team->leader_id == $current_user_id) {
        if ($target_member->role === 'vice_leader') {
            // 已是副团长，显示取消按钮
            $content .= '<button type="button" id="cancel-vice-leader-btn" class="but c-yellow mr10" data-id="' . esc_attr($target_user_id) . '"><i class="fa fa-level-down mr6"></i>取消副团长</button>';
        } else {
            // 普通成员，显示设为副团长按钮
            $max_vice_leaders = hoshinoai_adventure('max_vice_leaders', 1);
            
            // 检查副团长数量是否已达到上限
            $vice_leaders_count = 0;
            $members = hoshinoai_get_team_members($team_id);
            
            foreach ($members as $member) {
                if ($member->role === 'vice_leader') {
                    $vice_leaders_count++;
                }
            }
            
            if ($vice_leaders_count < $max_vice_leaders) {
                $content .= '<button type="button" id="set-vice-leader-btn" class="but c-blue mr10" data-id="' . esc_attr($target_user_id) . '"><i class="fa fa-level-up mr6"></i>设为副团长</button>';
            }
        }
        
        // 转让团长按钮
        $content .= '<button type="button" id="transfer-leadership-btn" class="but c-red mr10" data-id="' . esc_attr($target_user_id) . '"><i class="fa fa-crown mr6"></i>转让团长</button>';
    }
    
    // 移除成员按钮
    $content .= '<button type="button" id="remove-member-btn" class="but c-red" data-id="' . esc_attr($target_user_id) . '"><i class="fa fa-user-times mr6"></i>移除成员</button>';
    
    $content .= '</div>';
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            // 设置副团长
            $("#set-vice-leader-btn").on("click", function() {
                var $btn = $(this);
                var userId = $btn.data("id");
                
                if (!confirm("确定要将该成员设为副团长吗？")) {
                    return;
                }
                
                $btn.html(\'<i class="loading mr6"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_set_vice_leader",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . ',
                        user_id: userId
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 记录请求数据便于调试
                        console.log("正在发送转让团长请求，团队ID:", ' . $team_id . ', "新团长ID:", userId);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("副团长设置成功", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-level-up mr6"></i>设为副团长\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-level-up mr6"></i>设为副团长\').attr("disabled", false);
                    }
                });
            });
            
            // 取消副团长
            $("#cancel-vice-leader-btn").on("click", function() {
                var $btn = $(this);
                var userId = $btn.data("id");
                
                if (!confirm("确定要取消该成员的副团长职位吗？")) {
                    return;
                }
                
                $btn.html(\'<i class="loading mr6"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_cancel_vice_leader",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . ',
                        user_id: userId
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 打印出表单数据便于调试
                        console.log("发送的表单数据:", $form.serialize());
                        var formData = {};
                        $.each($form.serializeArray(), function(i, field) {
                            formData[field.name] = field.value;
                        });
                        console.log("表单数据对象:", formData);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("已取消副团长职位", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-level-down mr6"></i>取消副团长\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-level-down mr6"></i>取消副团长\').attr("disabled", false);
                    }
                });
            });
            
            // 转让团长
            $("#transfer-leadership-btn").on("click", function() {
                var $btn = $(this);
                var userId = $btn.data("id");
                
                if (!confirm("确定要将团长职位转让给该成员吗？转让后您将变成普通成员。")) {
                    return;
                }
                
                $btn.html(\'<i class="loading mr6"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_transfer_leadership",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . ',
                        new_leader_id: userId
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 记录请求数据便于调试
                        console.log("正在发送转让团长请求，团队ID:", ' . $team_id . ', "新团长ID:", userId);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("团长职位转让成功", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-crown mr6"></i>转让团长\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-crown mr6"></i>转让团长\').attr("disabled", false);
                    }
                });
            });
            
            // 移除成员
            $("#remove-member-btn").on("click", function() {
                var $btn = $(this);
                var userId = $btn.data("id");
                
                if (!confirm("确定要移除该成员吗？")) {
                    return;
                }
                
                $btn.html(\'<i class="loading mr6"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_remove_member",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . ',
                        user_id: userId
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 记录请求数据便于调试
                        console.log("正在发送移除成员请求，团队ID:", ' . $team_id . ', "成员ID:", userId);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("成员已移除", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-user-times mr6"></i>移除成员\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-user-times mr6"></i>移除成员\').attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    
    echo zib_get_modal_colorful_header('jb-yellow', '<i class="fa fa-user-cog"></i>', '成员管理');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_manage_member_modal', 'hoshinoai_manage_member_modal');

/**
 * 退出团队模态框
 */
function hoshinoai_leave_team_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查是否为团队成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        echo '<div class="text-center">您不是该团队成员</div>';
        exit;
    }
    
    // 团长不能退出团队
    $team = hoshinoai_get_team($team_id);
    
    if ($team && $team->leader_id == $user_id) {
        $content = '<div class="text-center">';
        $content .= '<i class="fa fa-exclamation-circle c-yellow em3x mb20"></i>';
        $content .= '<div class="em2x mb10">无法退出</div>';
        $content .= '<div class="muted-2-color">团长不能退出团队，请先转让团长职位或解散团队</div>';
        $content .= '</div>';
        
        echo zib_get_modal_colorful_header('c-yellow', '<i class="fa fa-exclamation-circle"></i>', '退出冒险团');
        echo '<div class="modal-body">' . $content . '</div>';
        exit;
    }
    
    // 确认退出
    $content = '<div class="text-center">';
    $content .= '<i class="fa fa-exclamation-circle c-yellow em3x mb20"></i>';
    $content .= '<div class="em2x mb10">确认退出</div>';
    $content .= '<div class="muted-2-color mb20">您确定要退出该冒险团吗？</div>';
    $content .= '<button type="button" id="leave-team-btn" class="but c-red"><i class="fa fa-sign-out mr10"></i>确认退出</button>';
    $content .= '</div>';
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            $("#leave-team-btn").on("click", function() {
                var $btn = $(this);
                
                $btn.html(\'<i class="loading mr10"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_leave_team",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . '
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 记录请求数据便于调试
                        console.log("正在发送退出团队请求，团队ID:", ' . $team_id . ');
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("已成功退出冒险团", "success");
                            setTimeout(function() {
                                window.location.href = window.location.pathname + "?page=adventure&tab=adventure";
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-sign-out mr10"></i>确认退出\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-sign-out mr10"></i>确认退出\').attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    
    echo zib_get_modal_colorful_header('c-red', '<i class="fa fa-sign-out"></i>', '退出冒险团');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_leave_team_modal', 'hoshinoai_leave_team_modal');

/**
 * 团队管理模态框
 */
function hoshinoai_team_manage_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查是否有权限管理团队
    if (!hoshinoai_can_manage_team($team_id, $user_id)) {
        echo '<div class="text-center">您没有权限执行此操作</div>';
        exit;
    }
    
    // 获取团队信息
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        echo '<div class="text-center">团队不存在或已解散</div>';
        exit;
    }
    
    // 团队信息编辑表单
    $is_leader = ($team->leader_id == $user_id);
    
    $content = '<form id="team-manage-form">';
    $content .= '<input type="hidden" name="action" value="hoshinoai_ajax_update_team">';
    $content .= '<input type="hidden" name="nonce" value="' . wp_create_nonce('hoshinoai_adventure_nonce') . '">';
    $content .= '<input type="hidden" name="team_id" value="' . esc_attr($team_id) . '">';
    
    $content .= '<div class="form-group">';
    $content .= '<label for="team_name">冒险团名称</label>';
    $content .= '<input type="text" id="team_name" name="team_name" class="form-control" value="' . esc_attr($team->name) . '" required>';
    $content .= '</div>';
    
    $content .= '<div class="form-group">';
    $content .= '<label for="team_description">冒险团描述</label>';
    $content .= '<textarea id="team_description" name="team_description" class="form-control" rows="3">' . esc_textarea($team->description) . '</textarea>';
    $content .= '</div>';
    
    $content .= '<div class="form-group text-center">';
    $content .= '<button type="submit" class="but c-blue">保存修改</button>';
    $content .= '</div>';
    $content .= '</form>';
    
    // 如果是团长，显示解散团队按钮
    if ($is_leader) {
        $content .= '<hr class="mt20 mb20">';
        $content .= '<div class="text-center">';
        $content .= '<button type="button" id="dissolve-team-btn" class="but c-red"><i class="fa fa-trash mr10"></i>解散冒险团</button>';
        $content .= '</div>';
    }
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            // 更新团队信息
            $("#team-manage-form").on("submit", function(e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $form.find("button[type=submit]");
                
                $btn.html(\'<i class="loading mr10"></i>保存中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: $form.serializeArray(),
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 打印出表单数据便于调试
                        console.log("发送的表单数据:", $form.serialize());
                        var formData = {};
                        $.each($form.serializeArray(), function(i, field) {
                            formData[field.name] = field.value;
                        });
                        console.log("表单数据对象:", formData);
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("团队信息已更新", "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html("保存修改").attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html("保存修改").attr("disabled", false);
                    }
                });
            });
            
            // 解散团队
            $("#dissolve-team-btn").on("click", function() {
                var $btn = $(this);
                
                if (!confirm("确定要解散该冒险团吗？此操作不可撤销！")) {
                    return;
                }
                
                $btn.html(\'<i class="loading mr10"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_dissolve_team",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . '
                    },
                    dataType: "json",
                    beforeSend: function(xhr) {
                        // 记录请求数据便于调试
                        console.log("正在发送解散团队请求，团队ID:", ' . $team_id . ');
                    },
                    success: function(response) {
                        if (response.success) {
                            notyf("冒险团已解散", "success");
                            setTimeout(function() {
                                window.location.href = window.location.pathname + "?page=adventure&tab=adventure";
                            }, 1000);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-trash mr10"></i>解散冒险团\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-trash mr10"></i>解散冒险团\').attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-cog"></i>', '团队管理');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_team_manage_modal', 'hoshinoai_team_manage_modal');

/**
 * 加入团队模态框
 */
function hoshinoai_join_team_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    $user_id = get_current_user_id();
    
    // 获取团队信息
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        echo '<div class="text-center">团队不存在或已解散</div>';
        exit;
    }
    
    // 检查用户是否已是成员
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if ($member) {
        echo '<div class="text-center">您已经是该团队成员</div>';
        exit;
    }
    
    // 检查是否已有未处理的申请
    global $wpdb;
    $pending_request = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_join_requests 
            WHERE team_id = %d AND user_id = %d AND status = 'pending'",
            $team_id,
            $user_id
        )
    );
    
    // 检查团队成员数量是否达到上限
    $max_members = hoshinoai_adventure('max_team_members', 20);
    $team_members = hoshinoai_get_team_members($team_id);
    
    if (count($team_members) >= $max_members) {
        $content = '<div class="text-center">';
        $content .= '<i class="fa fa-exclamation-circle c-yellow em3x mb20"></i>';
        $content .= '<div class="em2x mb10">无法加入</div>';
        $content .= '<div class="muted-2-color">该团队成员数量已达上限（' . $max_members . '人）</div>';
        $content .= '</div>';
        
        echo zib_get_modal_colorful_header('c-yellow', '<i class="fa fa-exclamation-circle"></i>', '加入冒险团');
        echo '<div class="modal-body">' . $content . '</div>';
        exit;
    }
    
    // 团队信息和确认加入
    $content = '<div class="text-center">';
    $content .= '<div class="em2x mb10">' . esc_html($team->name) . '</div>';
    
    if (!empty($team->description)) {
        $content .= '<div class="muted-2-color mb20">' . esc_html($team->description) . '</div>';
    }
    
    $content .= '<div class="mb20">';
    $content .= '<div class="badg badg-sm c-blue mr6">成员: ' . count($team_members) . '/' . $max_members . '</div>';
    
    // 获取团长信息
    $leader = get_userdata($team->leader_id);
    if ($leader) {
        $content .= '<div class="badg badg-sm c-red">团长: ' . esc_html($leader->display_name) . '</div>';
    }
    
    $content .= '</div>';
    
    if ($pending_request) {
        // 已有未处理的申请
        $content .= '<div class="mb10">您已提交加入申请，正在等待审核</div>';
        $content .= '<button type="button" class="but c-yellow" disabled><i class="fa fa-clock-o mr10"></i>申请审核中</button>';
    } else {
        // 未提交申请，显示确认加入按钮
    $content .= '<div class="mb10">您确定要加入该冒险团吗？</div>';
    $content .= '<button type="button" id="join-team-btn" class="but c-blue"><i class="fa fa-plus-circle mr10"></i>确认加入</button>';
    
    // 添加JavaScript
    $content .= '<script>
        jQuery(function($) {
            $("#join-team-btn").on("click", function() {
                var $btn = $(this);
                
                $btn.html(\'<i class="loading mr10"></i>处理中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: {
                        action: "hoshinoai_ajax_join_team",
                        nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                        team_id: ' . $team_id . '
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                                notyf("已成功发送加入申请，请等待团长审核", "success");
                            setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                        } else {
                            notyf(response.data, "danger");
                            $btn.html(\'<i class="fa fa-plus-circle mr10"></i>确认加入\').attr("disabled", false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $btn.html(\'<i class="fa fa-plus-circle mr10"></i>确认加入\').attr("disabled", false);
                    }
                });
            });
        });
    </script>';
    }
    
    $content .= '</div>';
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-users"></i>', '加入冒险团');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_join_team_modal', 'hoshinoai_join_team_modal');

/**
 * 团队详情模态框
 */
function hoshinoai_team_detail_modal() {
    // 检查用户是否登录
    if (!is_user_logged_in()) {
        echo '<div class="text-center">请先登录</div>';
        exit;
    }
    
    // 验证必要参数
    if (empty($_REQUEST['team_id'])) {
        echo '<div class="text-center">参数错误</div>';
        exit;
    }
    
    $team_id = intval($_REQUEST['team_id']);
    
    // 获取团队信息
    $team = hoshinoai_get_team($team_id);
    
    if (!$team || $team->status !== 'active') {
        echo '<div class="text-center">团队不存在或已解散</div>';
        exit;
    }
    
    // 获取成员列表
    $members = hoshinoai_get_team_members($team_id);
    $member_count = count($members);
    
    // 获取团长和副团长信息
    $leader = get_userdata($team->leader_id);
    $vice_leader = null;
    
    if (!empty($team->vice_leader_id)) {
        $vice_leader = get_userdata($team->vice_leader_id);
    }
    
    // 构建HTML
    $content = '<div class="text-center mb20">';
    $content .= '<div class="em2x mb10">' . esc_html($team->name) . '</div>';
    
    if (!empty($team->description)) {
        $content .= '<div class="muted-2-color mb10">' . esc_html($team->description) . '</div>';
    }
    
    $content .= '<div class="badg c-blue">成员: ' . $member_count . '</div>';
    $content .= '</div>';
    
    // 团队成员列表
    $content .= '<div class="mb20">';
    $content .= '<div class="title-h-left"><b>团队成员</b></div>';
    
    if (empty($members)) {
        $content .= '<div class="text-center muted-color padding-h10">还没有团队成员</div>';
    } else {
        $content .= '<div class="list-group theme-box">';
        
        // 先显示团长
        if ($leader) {
            $content .= '<div class="list-group-item">';
            $content .= '<div class="flex ac">';
            $content .= '<div class="avatar-img avatar-mini mr10">' . zib_get_data_avatar($leader->ID) . '</div>';
            $content .= '<div class="flex1 mr10">';
            $content .= '<div class="flex ac jsb">';
            $content .= '<div class="font-bold">' . esc_html($leader->display_name) . '</div>';
            $content .= '<div class="badg c-red">团长</div>';
            $content .= '</div>';
            
            // 查找团长的成员信息
            foreach ($members as $member) {
                if ($member->user_id == $leader->ID) {
                    if (!empty($member->character_name)) {
                        $content .= '<div class="em09 muted-2-color">' . esc_html($member->character_name);
                        
                        if (!empty($member->character_class)) {
                            $content .= ' · ' . esc_html($member->character_class);
                        }
                        
                        if (!empty($member->character_level)) {
                            $content .= ' · 等级 ' . esc_html($member->character_level);
                        }
                        
                        $content .= '</div>';
                    }
                    break;
                }
            }
            
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }
        
        // 再显示副团长
        if ($vice_leader) {
            $content .= '<div class="list-group-item">';
            $content .= '<div class="flex ac">';
            $content .= '<div class="avatar-img avatar-mini mr10">' . zib_get_data_avatar($vice_leader->ID) . '</div>';
            $content .= '<div class="flex1 mr10">';
            $content .= '<div class="flex ac jsb">';
            $content .= '<div class="font-bold">' . esc_html($vice_leader->display_name) . '</div>';
            $content .= '<div class="badg c-blue">副团长</div>';
            $content .= '</div>';
            
            // 查找副团长的成员信息
            foreach ($members as $member) {
                if ($member->user_id == $vice_leader->ID) {
                    if (!empty($member->character_name)) {
                        $content .= '<div class="em09 muted-2-color">' . esc_html($member->character_name);
                        
                        if (!empty($member->character_class)) {
                            $content .= ' · ' . esc_html($member->character_class);
                        }
                        
                        if (!empty($member->character_level)) {
                            $content .= ' · 等级 ' . esc_html($member->character_level);
                        }
                        
                        $content .= '</div>';
                    }
                    break;
                }
            }
            
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }
        
        // 最后显示普通成员
        foreach ($members as $member) {
            // 跳过团长和副团长
            if ($member->user_id == $team->leader_id || ($vice_leader && $member->user_id == $team->vice_leader_id)) {
                continue;
            }
            
            $member_user = get_userdata($member->user_id);
            
            if (!$member_user) {
                continue;
            }
            
            $content .= '<div class="list-group-item">';
            $content .= '<div class="flex ac">';
            $content .= '<div class="avatar-img avatar-mini mr10">' . zib_get_data_avatar($member_user->ID) . '</div>';
            $content .= '<div class="flex1 mr10">';
            $content .= '<div class="flex ac jsb">';
            $content .= '<div class="font-bold">' . esc_html($member_user->display_name) . '</div>';
            $content .= '<div class="badg c-green">成员</div>';
            $content .= '</div>';
            
            if (!empty($member->character_name)) {
                $content .= '<div class="em09 muted-2-color">' . esc_html($member->character_name);
                
                if (!empty($member->character_class)) {
                    $content .= ' · ' . esc_html($member->character_class);
                }
                
                if (!empty($member->character_level)) {
                    $content .= ' · 等级 ' . esc_html($member->character_level);
                }
                
                $content .= '</div>';
            }
            
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }
        
        $content .= '</div>';
    }
    
    $content .= '</div>';
    
    // 加入团队按钮
    $user_id = get_current_user_id();
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        $max_members = hoshinoai_adventure('max_team_members', 20);
        
        if ($member_count < $max_members) {
            $content .= '<div class="text-center">';
            $content .= '<button type="button" id="join-team-btn" class="but c-blue"><i class="fa fa-plus-circle mr10"></i>加入该冒险团</button>';
            $content .= '</div>';
            
            // 添加JavaScript
            $content .= '<script>
                jQuery(function($) {
                    $("#join-team-btn").on("click", function() {
                        var $btn = $(this);
                        
                        $btn.html(\'<i class="loading mr10"></i>处理中...\').attr("disabled", true);
                        
                        $.ajax({
                            url: "/wp-admin/admin-ajax.php",
                            type: "POST",
                            data: {
                                action: "hoshinoai_ajax_join_team",
                                nonce: "' . wp_create_nonce('hoshinoai_adventure_nonce') . '",
                                team_id: ' . $team_id . '
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    notyf("已成功发送加入申请，请等待团长审核", "success");
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    notyf(response.data, "danger");
                                    $btn.html(\'<i class="fa fa-plus-circle mr10"></i>加入该冒险团\').attr("disabled", false);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("AJAX错误状态:", status);
                                console.log("AJAX错误信息:", error);
                                console.log("服务器响应:", xhr.responseText);
                                notyf("网络错误，请稍后再试: " + status, "danger");
                                $btn.html(\'<i class="fa fa-plus-circle mr10"></i>加入该冒险团\').attr("disabled", false);
                            }
                        });
                    });
                });
            </script>';
        } else {
            $content .= '<div class="text-center muted-color">该团队成员已满</div>';
        }
    }
    
    echo zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-users"></i>', '冒险团详情');
    echo '<div class="modal-body">' . $content . '</div>';
    exit;
}
add_action('wp_ajax_hoshinoai_team_detail_modal', 'hoshinoai_team_detail_modal');

/**
 * AJAX处理更新团队信息请求
 */
function hoshinoai_ajax_update_team() {
    // 验证nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hoshinoai_adventure_nonce')) {
        wp_send_json_error('安全验证失败');
    }
    
    // 验证用户是否登录
    if (!is_user_logged_in()) {
        wp_send_json_error('请先登录');
    }
    
    // 验证必要字段
    if (empty($_POST['team_id']) || empty($_POST['team_name'])) {
        wp_send_json_error('缺少必要参数');
    }
    
    $team_id = intval($_POST['team_id']);
    $user_id = get_current_user_id();
    
    // 检查是否有权限管理团队
    if (!hoshinoai_can_manage_team($team_id, $user_id)) {
        wp_send_json_error('您没有权限执行此操作');
    }
    
    // 准备数据
    $team_data = array(
        'name' => sanitize_text_field($_POST['team_name']),
        'description' => isset($_POST['team_description']) ? sanitize_textarea_field($_POST['team_description']) : '',
    );
    
    // 更新团队信息
    $result = hoshinoai_update_team($team_id, $team_data);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => '团队信息已更新',
        ));
    } else {
        wp_send_json_error('团队信息更新失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_update_team', 'hoshinoai_ajax_update_team');

/**
 * AJAX处理取消副团长请求
 */
function hoshinoai_ajax_cancel_vice_leader() {
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
        wp_send_json_error('只有团长可以执行此操作');
    }
    
    // 检查目标用户是否是副团长
    if ($team->vice_leader_id != $target_user_id) {
        wp_send_json_error('该成员不是副团长');
    }
    
    // 开始事务
    global $wpdb;
    $wpdb->query('START TRANSACTION');
    
    // 更新成员角色
    $member_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_members',
        array('role' => 'member'),
        array('team_id' => $team_id, 'user_id' => $target_user_id)
    );
    
    // 更新团队表的副团长字段
    $team_updated = $wpdb->update(
        $wpdb->prefix . 'hoshinoai_adventure_teams',
        array('vice_leader_id' => null),
        array('id' => $team_id)
    );
    
    // 检查是否全部更新成功
    if ($member_updated !== false && $team_updated !== false) {
        $wpdb->query('COMMIT');
        wp_send_json_success(array(
            'message' => '已取消副团长职位',
        ));
    } else {
        $wpdb->query('ROLLBACK');
        wp_send_json_error('操作失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_cancel_vice_leader', 'hoshinoai_ajax_cancel_vice_leader');

/**
 * AJAX处理获取团队详情的请求
 */
function hoshinoai_ajax_get_team_details() {
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
    
    // 获取团队详情HTML
    $html = hoshinoai_get_team_detail_html($team_id, $user_id);
    
    wp_send_json_success(array(
        'html' => $html,
        'team_id' => $team_id
    ));
}
add_action('wp_ajax_hoshinoai_ajax_get_team_details', 'hoshinoai_ajax_get_team_details');

/**
 * 显示用户加入的冒险团及职位信息
 * 
 * @param string $desc 原始描述内容
 * @param int $user_id 用户ID
 * @return string 添加了冒险团信息的描述内容
 */
function hoshinoai_adventure_user_teams_desc($desc, $user_id) {
    // 获取用户加入的所有团队
    $joined_teams = hoshinoai_get_user_joined_teams($user_id);
    
    if (empty($joined_teams)) {
        return $desc; // 如果没有加入任何团队，直接返回原描述
    }
    
    $team_info_html = '';
    
    // 遍历用户加入的所有团队
    foreach ($joined_teams as $team) {
        // 获取用户在该团队中的成员信息
        $member = hoshinoai_get_member($team->id, $user_id);
        
        if (!$member) {
            continue; // 如果没有找到成员信息，跳过
        }
        
        // 确定用户在团队中的职位
        $role_text = '';
        if ($member->role === 'leader') {
            $role_text = '团长';
            $role_class = 'c-red'; // 团长用红色
        } elseif ($member->role === 'vice_leader') {
            $role_text = '副团长';
            $role_class = 'c-blue'; // 副团长用蓝色
        } else {
            $role_text = '成员';
            $role_class = 'c-green'; // 普通成员用绿色
        }
        
        // 构建单个团队信息的HTML
        $team_info_html .= ' <span class="but ' . $role_class . '"><i class="fa fa-users mr6"></i>' . esc_html($team->name) . ' ' . $role_text . '</span>';
    }
    
    // 将团队信息添加到原始描述前面
    return $team_info_html . $desc;
}

// 添加到用户页面头部描述
add_filter('user_page_header_desc', 'hoshinoai_adventure_user_teams_desc', 20, 2);
// 添加到作者身份标识
add_filter('author_header_identity', 'hoshinoai_adventure_user_teams_desc', 20, 2);