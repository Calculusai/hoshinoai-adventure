<?php
/**
 * 角色详情管理功能
 * 包含冒险团角色的详细信息管理功能
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 创建或更新角色详情
 * 
 * @param array $details_data 详情数据
 * @return int|bool 成功返回详情ID，失败返回false
 */
function hoshinoai_create_or_update_character_details($details_data) {
    global $wpdb;
    
    // 检查必要字段
    if (empty($details_data['member_id']) || empty($details_data['user_id']) || empty($details_data['team_id'])) {
        return false;
    }
    
    // 检查角色详情是否已存在
    $existing_details = hoshinoai_get_character_details_by_member_id($details_data['member_id']);
    
    // 添加更新时间
    $details_data['updated_at'] = current_time('mysql');
    
    if ($existing_details) {
        // 更新现有记录
        $result = $wpdb->update(
            $wpdb->prefix . 'hoshinoai_adventure_character_details',
            $details_data,
            array('member_id' => $details_data['member_id'])
        );
        
        return $result !== false ? $existing_details->id : false;
    } else {
        // 添加创建时间
        $details_data['created_at'] = current_time('mysql');
        
        // 插入新记录
        $result = $wpdb->insert(
            $wpdb->prefix . 'hoshinoai_adventure_character_details',
            $details_data
        );
        
        return $result !== false ? $wpdb->insert_id : false;
    }
}

/**
 * 获取角色详情
 * 
 * @param int $details_id 详情ID
 * @return object|bool 成功返回详情信息，失败返回false
 */
function hoshinoai_get_character_details($details_id) {
    global $wpdb;
    
    if (empty($details_id)) {
        return false;
    }
    
    $details = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_character_details WHERE id = %d",
            $details_id
        )
    );
    
    return $details;
}

/**
 * 通过成员ID获取角色详情
 * 
 * @param int $member_id 成员ID
 * @return object|bool 成功返回详情信息，失败返回false
 */
function hoshinoai_get_character_details_by_member_id($member_id) {
    global $wpdb;
    
    if (empty($member_id)) {
        return false;
    }
    
    $details = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}hoshinoai_adventure_character_details WHERE member_id = %d",
            $member_id
        )
    );
    
    return $details;
}

/**
 * 通过团队ID和用户ID获取角色详情
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return object|bool 成功返回详情信息，失败返回false
 */
function hoshinoai_get_character_details_by_team_user($team_id, $user_id) {
    global $wpdb;
    
    if (empty($team_id) || empty($user_id)) {
        return false;
    }
    
    // 先获取成员信息
    $member = hoshinoai_get_member($team_id, $user_id);
    
    if (!$member) {
        return false;
    }
    
    // 通过成员ID获取详情
    return hoshinoai_get_character_details_by_member_id($member->id);
}

/**
 * 计算属性值修正
 * 
 * @param int $attribute_value 属性值
 * @return int 属性修正值
 */
function hoshinoai_calculate_attribute_modifier($attribute_value) {
    return floor(($attribute_value - 10) / 2);
}

/**
 * 获取属性修正值显示文本
 * 
 * @param int $modifier 修正值
 * @return string 显示文本
 */
function hoshinoai_get_modifier_text($modifier) {
    if ($modifier > 0) {
        return '+' . $modifier;
    } else {
        return (string) $modifier;
    }
}

/**
 * AJAX处理创建或更新角色详情请求
 */
function hoshinoai_ajax_update_character_details() {
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
    
    // 检查角色详情功能是否启用
    $enable_character_details = hoshinoai_adventure('enable_character_details', true);
    
    if (!$enable_character_details) {
        wp_send_json_error('角色详情功能已被禁用');
    }
    
    // 准备详情数据
    $details_data = array(
        'member_id' => $member->id,
        'user_id' => $user_id,
        'team_id' => $team_id,
    );
    
    // 属性值
    $attributes = array('strength', 'dexterity', 'constitution', 'intelligence', 'wisdom', 'charisma');
    
    foreach ($attributes as $attribute) {
        if (isset($_POST[$attribute])) {
            $value = intval($_POST[$attribute]);
            // 限制属性值范围
            $details_data[$attribute] = max(1, min(30, $value));
        }
    }
    
    // 背景故事、外观描述和性格特点
    if (isset($_POST['background'])) {
        $details_data['background'] = sanitize_textarea_field($_POST['background']);
    }
    
    if (isset($_POST['appearance'])) {
        $details_data['appearance'] = sanitize_textarea_field($_POST['appearance']);
    }
    
    if (isset($_POST['personality'])) {
        $details_data['personality'] = sanitize_textarea_field($_POST['personality']);
    }
    
    // 创建或更新详情
    $result = hoshinoai_create_or_update_character_details($details_data);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => '角色详情已更新',
            'details_id' => $result,
        ));
    } else {
        wp_send_json_error('角色详情更新失败');
    }
}
add_action('wp_ajax_hoshinoai_ajax_update_character_details', 'hoshinoai_ajax_update_character_details');

/**
 * 获取预设职业选项
 * 
 * @return array 职业选项
 */
function hoshinoai_get_character_classes() {
    $default_classes = array(
        '战士', '法师', '盗贼', '牧师', '游侠', '德鲁伊', '圣骑士', '术士', '武僧', '野蛮人', '诗人', '术士'
    );
    
    $custom_classes = hoshinoai_adventure('character_classes', array());
    
    return !empty($custom_classes) ? $custom_classes : $default_classes;
}

/**
 * 获取角色详情表单
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return string 表单HTML
 */
function hoshinoai_get_character_details_form($team_id, $user_id) {
    // 获取角色详情
    $details = hoshinoai_get_character_details_by_team_user($team_id, $user_id);
    
    // 准备表单字段
    $fields = array(
        'strength' => array(
            'label' => '力量',
            'value' => $details ? $details->strength : 10,
            'type' => 'number',
            'min' => 1,
            'max' => 30,
        ),
        'dexterity' => array(
            'label' => '敏捷',
            'value' => $details ? $details->dexterity : 10,
            'type' => 'number',
            'min' => 1,
            'max' => 30,
        ),
        'constitution' => array(
            'label' => '体质',
            'value' => $details ? $details->constitution : 10,
            'type' => 'number',
            'min' => 1,
            'max' => 30,
        ),
        'intelligence' => array(
            'label' => '智力',
            'value' => $details ? $details->intelligence : 10,
            'type' => 'number',
            'min' => 1,
            'max' => 30,
        ),
        'wisdom' => array(
            'label' => '感知',
            'value' => $details ? $details->wisdom : 10,
            'type' => 'number',
            'min' => 1,
            'max' => 30,
        ),
        'charisma' => array(
            'label' => '魅力',
            'value' => $details ? $details->charisma : 10,
            'type' => 'number',
            'min' => 1,
            'max' => 30,
        ),
        'background' => array(
            'label' => '背景故事',
            'value' => $details ? $details->background : '',
            'type' => 'textarea',
            'rows' => 5,
        ),
        'appearance' => array(
            'label' => '外观描述',
            'value' => $details ? $details->appearance : '',
            'type' => 'textarea',
            'rows' => 3,
        ),
        'personality' => array(
            'label' => '性格特点',
            'value' => $details ? $details->personality : '',
            'type' => 'textarea',
            'rows' => 3,
        ),
    );
    
    // 构建表单HTML
    $html = '<form id="character-details-form" class="form-group">';
    $html .= '<input type="hidden" name="action" value="hoshinoai_ajax_update_character_details">';
    $html .= '<input type="hidden" name="team_id" value="' . esc_attr($team_id) . '">';
    $html .= '<input type="hidden" name="nonce" value="' . wp_create_nonce('hoshinoai_adventure_nonce') . '">';
    
    // 添加属性值字段
    $html .= '<div class="zib-widget mb20"><div class="title-h-left"><b>角色属性</b></div>';
    $html .= '<div class="row gutters-10">';
    
    $attribute_fields = array('strength', 'dexterity', 'constitution', 'intelligence', 'wisdom', 'charisma');
    
    foreach ($attribute_fields as $field_name) {
        $field = $fields[$field_name];
        $modifier = hoshinoai_calculate_attribute_modifier($field['value']);
        $modifier_text = hoshinoai_get_modifier_text($modifier);
        
        $html .= '<div class="col-sm-6 col-md-4">';
        $html .= '<div class="form-group">';
        $html .= '<label for="' . esc_attr($field_name) . '">' . esc_html($field['label']) . ' <small class="ml6">(' . esc_html($modifier_text) . ')</small></label>';
        $html .= '<input type="number" id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" class="form-control" value="' . esc_attr($field['value']) . '" min="' . esc_attr($field['min']) . '" max="' . esc_attr($field['max']) . '">';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div></div>';
    
    // 添加背景故事字段
    $html .= '<div class="zib-widget mb20"><div class="title-h-left"><b>角色背景</b></div>';
    
    foreach (array('background', 'appearance', 'personality') as $field_name) {
        $field = $fields[$field_name];
        
        $html .= '<div class="form-group">';
        $html .= '<label for="' . esc_attr($field_name) . '">' . esc_html($field['label']) . '</label>';
        $html .= '<textarea id="' . esc_attr($field_name) . '" name="' . esc_attr($field_name) . '" class="form-control" rows="' . esc_attr($field['rows']) . '">' . esc_textarea($field['value']) . '</textarea>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    // 添加提交按钮
    $html .= '<div class="text-center mt20">';
    $html .= '<button type="button" id="update-character-details-btn" class="but c-blue"><i class="fa fa-save mr10"></i>保存角色详情</button>';
    $html .= '</div>';
    $html .= '</form>';
    
    // 添加JavaScript
    $html .= '<script>
        jQuery(function($) {
            $("#update-character-details-btn").on("click", function() {
                var $button = $(this);
                var $form = $("#character-details-form");
                var formData = $form.serialize();
                
                $button.html(\'<i class="loading mr10"></i>保存中...\').attr("disabled", true);
                
                $.ajax({
                    url: "/wp-admin/admin-ajax.php",
                    type: "POST",
                    data: $form.serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            notyf("角色详情已保存", "success");
                        } else {
                            notyf(response.data, "danger");
                        }
                        $button.html(\'<i class="fa fa-save mr10"></i>保存角色详情\').attr("disabled", false);
                    },
                    error: function(xhr, status, error) {
                        console.log("AJAX错误状态:", status);
                        console.log("AJAX错误信息:", error);
                        console.log("服务器响应:", xhr.responseText);
                        notyf("网络错误，请稍后再试: " + status, "danger");
                        $button.html(\'<i class="fa fa-save mr10"></i>保存角色详情\').attr("disabled", false);
                    }
                });
            });
            
            // 属性值变化时更新修正值显示
            $("input[type=number]").on("change", function() {
                var $input = $(this);
                var value = parseInt($input.val());
                var modifier = Math.floor((value - 10) / 2);
                var modifierText = modifier > 0 ? "+" + modifier : modifier;
                
                $input.closest(".form-group").find("small").text("(" + modifierText + ")");
            });
        });
    </script>';
    
    return $html;
}

/**
 * 获取角色详情显示
 * 
 * @param int $team_id 团队ID
 * @param int $user_id 用户ID
 * @return string 详情HTML
 */
function hoshinoai_get_character_details_display($team_id, $user_id) {
    // 获取成员和角色详情
    $member = hoshinoai_get_member($team_id, $user_id);
    $details = hoshinoai_get_character_details_by_team_user($team_id, $user_id);
    
    if (!$member) {
        return '<div class="text-center">找不到角色信息</div>';
    }
    
    // 获取用户信息
    $user = get_userdata($user_id);
    
    // 构建显示HTML
    $html = '<div class="zib-widget">';
    
    // 角色基本信息
    $html .= '<div class="text-center mb20">';
    $html .= '<div class="avatar-img mt10 mb10">' . zib_get_data_avatar($user_id) . '</div>';
    
    if ($member->character_name) {
        $html .= '<h4 class="mt10">' . esc_html($member->character_name) . '</h4>';
    } else {
        $html .= '<h4 class="mt10">' . esc_html($user->display_name) . '</h4>';
    }
    
    if ($member->character_class && $member->character_level) {
        $html .= '<div class="muted-color">等级 ' . esc_html($member->character_level) . ' 的 ' . esc_html($member->character_class) . '</div>';
    } elseif ($member->character_class) {
        $html .= '<div class="muted-color">' . esc_html($member->character_class) . '</div>';
    } elseif ($member->character_level) {
        $html .= '<div class="muted-color">等级 ' . esc_html($member->character_level) . '</div>';
    }
    
    $html .= '</div>';
    
    // 如果没有详情数据，显示简单信息
    if (!$details) {
        $html .= '<div class="text-center muted-color mt20 mb20">该角色尚未填写详情信息</div>';
        $html .= '</div>';
        return $html;
    }
    
    // 属性值
    $html .= '<div class="mb20"><div class="title-h-left"><b>角色属性</b></div>';
    $html .= '<div class="row gutters-5">';
    
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
        
        $html .= '<div class="col-4 col-sm-4 col-md-4 mb10">';
        $html .= '<div class="text-center">';
        $html .= '<div class="em09">' . esc_html($label) . '</div>';
        $html .= '<div class="em12 font-bold">' . esc_html($value) . ' <span class="em09 muted-color">(' . esc_html($modifier_text) . ')</span></div>';
        $html .= '</div>';
        $html .= '</div>';
    }
    
    $html .= '</div></div>';
    
    // 背景信息
    if ($details->background || $details->appearance || $details->personality) {
        $html .= '<div class="mb10"><div class="title-h-left"><b>角色背景</b></div>';
        
        if ($details->background) {
            $html .= '<div class="mb15">';
            $html .= '<div class="em09 muted-2-color mb6">背景故事</div>';
            $html .= '<div class="text-wrap">' . wpautop(esc_html($details->background)) . '</div>';
            $html .= '</div>';
        }
        
        if ($details->appearance) {
            $html .= '<div class="mb15">';
            $html .= '<div class="em09 muted-2-color mb6">外观描述</div>';
            $html .= '<div class="text-wrap">' . wpautop(esc_html($details->appearance)) . '</div>';
            $html .= '</div>';
        }
        
        if ($details->personality) {
            $html .= '<div class="mb15">';
            $html .= '<div class="em09 muted-2-color mb6">性格特点</div>';
            $html .= '<div class="text-wrap">' . wpautop(esc_html($details->personality)) . '</div>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
} 