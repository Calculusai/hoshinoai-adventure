<?php
/*
 * @Author: 星野爱
 * @Url: 5era.cn
 * @Date: 2025-04-29
 * @LastEditTime: 2025-04-29
 */

/**
 * 获取插件选项函数
 * 用于快速获取选项，避免重复调用
 */
function hoshinoai_pz($name, $default = false, $subname = '')
{
    // 声明静态变量以加快检索速度
    static $options = null;
    if ($options === null) {
        $options = get_option('hoshinoai_adventure');
    }

    if (isset($options[$name])) {
        if ($subname) {
            return isset($options[$name][$subname]) ? $options[$name][$subname] : $default;
        } else {
            return $options[$name];
        }
    }
    return $default;
}

/**
 * 获取功能版本标记
 * 用于在后台显示不同版本的新功能标记
 */
function hoshinoai_get_new_badge()
{
    return array(
        '1.0' => '<badge style="background: #ff876b;">v1.0</badge>',
        '1.1' => '<badge style="background: #ff876b;">v1.1</badge>',
        '1.2' => '<badge>NEW</badge>',
    );
}

/**
 * 管理员选项设置页面
 * 包含插件的后台设置选项
 */
function hoshinoai_adventure_add_options_page() {
    if (!is_admin()) {
        return;
    }
    
    $prefix = 'hoshinoai_adventure';
    $new_badge = hoshinoai_get_new_badge(); // 获取版本更新标记
    
    // 创建设置页面
    CSF::createOptions($prefix, array(
        'menu_title' => '冒险团设置',
        'menu_slug' => 'hoshinoai-adventure-settings',
        'framework_title' => '星野爱冒险团设置 <small>v1.0.0</small>',
        'menu_parent' => 'options-general.php',
        'menu_type' => 'submenu',
        'show_bar_menu' => false,
        'theme' => 'light',
        'footer_text' => '让游戏体验更加精彩',
    ));
    
    // 插件介绍
    CSF::createSection($prefix, array(
        'id' => 'preface',
        'title' => '插件介绍',
        'icon' => 'fa fa-coffee',
        'fields' => array(
            array(
                'type' => 'submessage',
                'style' => 'success',
                'content' => '<h3 style="color:#4CAF50;">🌟【星野爱冒险团插件】🌟</h3>
                <p>欢迎使用星野爱冒险团插件，这是一款用于角色扮演游戏冒险团管理的WordPress插件。</p>
                <p>如果您有任何建议，都可以访问作者的网站 <a href="https://www.5era.cn/" target="_blank">星野爱</a> 进行反馈。</p>
                <p>作者会不断完善本插件，添加更多优质功能。</p>',
            ),
        )
    ));

    // 基本设置
    CSF::createSection($prefix, array(
        'title' => '基本设置',
        'icon' => 'fa fa-cogs',
        'fields' => array(
            array(
                'type' => 'submessage',
                'style' => 'info',
                'content' => '<h3 style="color:#2196F3;">基础设置项</h3>
                <p>这里可以设置冒险团的基本参数和限制。</p>',
            ),
            array(
                'id' => 'max_teams_per_user',
                'type' => 'number',
                'title' => '每用户最大冒险团数量' . $new_badge['1.0'],
                'desc' => '设置每个用户最多可以创建的冒险团数量',
                'default' => 3,
                'min' => 1,
                'max' => 10,
                'step' => 1,
            ),
            array(
                'id' => 'max_team_members',
                'type' => 'number',
                'title' => '每团队最大成员数量' . $new_badge['1.0'],
                'desc' => '设置每个冒险团最多可以拥有的成员数量',
                'default' => 20,
                'min' => 3,
                'max' => 100,
                'step' => 1,
            ),
            array(
                'id' => 'max_vice_leaders',
                'type' => 'number',
                'title' => '每团队最大副团长数量' . $new_badge['1.0'],
                'desc' => '设置每个冒险团最多可以设置的副团长数量',
                'default' => 1,
                'min' => 0,
                'max' => 5,
                'step' => 1,
            ),
        ),
    ));
    
    // 角色设置
    CSF::createSection($prefix, array(
        'title' => '角色设置',
        'icon' => 'fa fa-user',
        'fields' => array(
            array(
                'type' => 'submessage',
                'style' => 'info',
                'content' => '<h3 style="color:#2196F3;">角色系统设置</h3>
                <p>这里可以配置角色相关的功能和显示选项。</p>',
            ),
            array(
                'id' => 'enable_character_details',
                'type' => 'switcher',
                'title' => '启用角色详情' . $new_badge['1.0'],
                'label' => '开启后用户可以设置角色的详细属性和背景信息',
                'desc' => '是否启用角色详情功能（属性值、背景故事等）',
                'default' => true,
            ),
            array(
                'id' => 'allow_custom_class',
                'type' => 'switcher',
                'title' => '允许自定义职业' . $new_badge['1.0'],
                'label' => '开启后用户可以自由输入职业名称',
                'desc' => '是否允许用户自定义职业，关闭后用户只能选择预设职业',
                'default' => true,
            ),
            array(
                'id' => 'character_classes',
                'type' => 'text',
                'title' => '预设职业' . $new_badge['1.0'],
                'desc' => '预设的角色职业选项，多个选项用英文逗号分隔',
                'default' => '战士,法师,盗贼,牧师,游侠,德鲁伊,圣骑士,术士,武僧,野蛮人,诗人',
                'dependency' => array('allow_custom_class', '!=', '1'),
            ),
            array(
                'id' => 'display_fields',
                'type' => 'checkbox',
                'title' => '角色详情显示字段' . $new_badge['1.0'],
                'desc' => '选择在角色详情中显示哪些字段',
                'options' => array(
                    'attributes' => '属性值',
                    'background' => '背景故事',
                    'appearance' => '外观描述',
                    'personality' => '性格特点',
                ),
                'default' => array('', '', '', ''),
                'dependency' => array('enable_character_details', '==', '1'),
            ),
        ),
    ));
    
    // 高级设置
    CSF::createSection($prefix, array(
        'title' => '高级设置',
        'icon' => 'fa fa-wrench',
        'fields' => array(
            array(
                'type' => 'submessage',
                'style' => 'warning',
                'content' => '<h3 style="color:#FF9800;">高级功能设置</h3>
                <p>这些设置会影响插件的数据处理方式，请谨慎修改。</p>',
            ),
            array(
                'id' => 'enable_data_export',
                'type' => 'switcher',
                'title' => '启用数据导出' . $new_badge['1.2'],
                'label' => '开启后用户可以导出角色数据',
                'desc' => '允许用户导出自己的角色数据',
                'default' => false,
            ),
        ),
    ));
    
    // 数据库管理
    CSF::createSection($prefix, array(
        'title' => '数据库管理',
        'icon' => 'fa fa-database',
        'fields' => array(
            array(
                'type' => 'submessage',
                'style' => 'danger',
                'content' => '<h3 style="color:#F44336;">数据库管理</h3>
                <p>这里的操作会直接影响到插件的数据库，请谨慎操作。</p>
                <p>数据删除后将无法恢复，请确保操作前已备份数据。</p>',
            ),
            array(
                'id' => 'recreate_tables',
                'type' => 'button_set',
                'title' => '重建数据表',
                'desc' => '如果插件数据表出现问题，可以尝试重建数据表（不会删除现有数据）',
                'options' => array(
                    'teams' => '团队表',
                    'members' => '成员表',
                    'details' => '角色详情表',
                    'requests' => '申请表',
                    'all' => '所有表',
                ),
                'multiple' => true,
            ),
            array(
                'id' => 'repair_button',
                'type' => 'content',
                'content' => '<button id="hoshinoai-repair-tables" class="button button-primary">开始重建数据表</button>
                <div id="hoshinoai-repair-result" style="margin-top:10px;"></div>
                <script>
                jQuery(document).ready(function($) {
                    $("#hoshinoai-repair-tables").click(function(e) {
                        e.preventDefault();
                        var tables = $("#hoshinoai_adventure-recreate_tables").val();
                        if(!tables || tables.length === 0) {
                            $("#hoshinoai-repair-result").html("<div class=\'notice notice-error\'><p>请先选择要重建的数据表</p></div>");
                            return;
                        }
                        $("#hoshinoai-repair-result").html("<div class=\'notice notice-info\'><p>正在重建数据表，请稍候...</p></div>");
                        $.ajax({
                            url: ajaxurl,
                            type: "POST",
                            data: {
                                action: "hoshinoai_repair_tables",
                                nonce: "' . wp_create_nonce('hoshinoai_repair_tables') . '",
                                tables: tables
                            },
                            success: function(response) {
                                if(response.success) {
                                    $("#hoshinoai-repair-result").html("<div class=\'notice notice-success\'><p>" + response.data + "</p></div>");
                                } else {
                                    $("#hoshinoai-repair-result").html("<div class=\'notice notice-error\'><p>" + response.data + "</p></div>");
                                }
                            },
                            error: function() {
                                $("#hoshinoai-repair-result").html("<div class=\'notice notice-error\'><p>操作失败，请稍后再试</p></div>");
                            }
                        });
                    });
                });
                </script>',
            ),
        ),
    ));
    
    // 关于插件
    CSF::createSection($prefix, array(
        'title' => '关于插件',
        'icon' => 'fa fa-info-circle',
        'fields' => array(
            array(
                'type' => 'content',
                'content' => '
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h3>星野爱冒险团 v1.0.0</h3>
                        <p>一款用于管理角色扮演游戏冒险团队的WordPress插件</p>
                        <p>作者：<a href="https://www.5era.cn/" target="_blank">星野爱</a></p>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4>插件介绍</h4>
                        <p>星野爱冒险团是一个用于管理角色扮演游戏（如D&D）冒险团队的WordPress插件，它允许用户创建冒险团、邀请成员、设置角色信息等。</p>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4>主要功能</h4>
                        <ul>
                            <li>创建和管理冒险团队</li>
                            <li>邀请成员加入团队</li>
                            <li>为角色添加详细信息（名称、职业、等级）</li>
                            <li>支持角色详情（属性值、背景故事、外观描述、性格特点）</li>
                            <li>团队管理（团长、副团长、成员角色）</li>
                            <li>权限控制系统</li>
                        </ul>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <h4>依赖</h4>
                        <p>本插件依赖于子比主题(ZibTheme)的模态框功能，请确保您的WordPress安装中已经安装并激活了子比主题。</p>
                    </div>
                ',
            ),
        ),
    ));
    
    // 备份与恢复
    CSF::createSection($prefix, array(
        'id' => 'backup',
        'title' => '备份与恢复',
        'icon' => 'fa fa-shield',
        'fields' => array(
            array(
                'type' => 'submessage',
                'style' => 'info',
                'content' => '<h3 style="color:#2196F3;">🔄【备份与恢复】🔄</h3>
                <p>您可以备份当前的插件配置，或者从之前的备份中恢复配置。</p>
                <p>也可以将配置导出与其他站点分享，或导入其他站点的配置。</p>',
            ),
            array(
                'type' => 'backup',
            )
        ),
    ));
}

// 执行添加选项页面
hoshinoai_adventure_add_options_page();