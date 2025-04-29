# 星野爱冒险团系统

一个用于管理角色扮演游戏冒险团队的WordPress插件。

## 主要功能

- 创建和管理冒险团队
- 邀请成员加入团队
- 为角色添加详细信息（名称、职业、等级）
- 支持角色详情（属性值、背景故事、外观描述、性格特点）
- 团队管理（团长、副团长、成员角色）
- 权限控制系统

## 依赖

本插件依赖于子比主题(ZibTheme)的模态框功能，请确保您的WordPress安装中已经安装并激活了子比主题。

## 安装

1. 下载插件压缩包
2. 在WordPress管理员后台，前往"插件" > "安装插件"
3. 点击"上传插件"按钮，选择下载的压缩包并安装
4. 安装完成后，激活插件

## 使用方法

1. 用户可以在用户中心找到"我的冒险团"选项卡
2. 在此页面可以创建新的冒险团，或者查看/加入已有的冒险团
3. 团长和副团长可以邀请新成员加入团队
4. 每个成员可以设置自己的角色信息和角色详情

## 设置

在WordPress管理员后台，前往"设置" > "冒险团设置"，可以进行以下设置：

- 每个用户最多创建的团队数
- 是否启用角色详情功能
- 是否允许自定义职业
- 预设职业选项
- 角色详情显示的字段

## 开发者

- 星野爱
- 网站：www.5era.cn

## 版本

- 版本：1.0.0
- 日期：2024-09-15

<div align="center">

![版本](https://img.shields.io/badge/版本-1.0.0-blue)
![WordPress](https://img.shields.io/badge/平台-WordPress-21759b)
![PHP](https://img.shields.io/badge/语言-PHP-777bb4)
![子比主题](https://img.shields.io/badge/依赖-子比主题-orange)
![状态](https://img.shields.io/badge/状态-已完成-brightgreen)

</div>

## 🚀 功能特性

- **✅ 冒险团基础管理** - 在用户中心创建和管理冒险团
- **✅ 团长与成员系统** - 完整的团队角色管理（团长/副团长/成员）
- **✅ 角色信息记录** - 记录角色名称、职业和等级等基本信息
- **✅ 角色详情功能** - 可选启用的角色属性值、背景故事等高级信息
- **✅ 现代化UI设计** - 专属多巴胺风格界面，提供沉浸式体验
- **✅ AJAX交互** - 无刷新操作体验，实时交互反馈
- **✅ 权限管理** - 基于角色的权限系统，保障数据安全
- **✅ 自动数据表创建** - 插件安装时自动创建所需数据库结构
- **✅ 主动加入团队** - 支持用户主动加入公开的冒险团
- **✅ 灵活管理设置** - 管理后台可调整团队人数、副团长数量等限制

## 🔌 与子比主题集成

插件基于子比主题的用户中心系统构建，使用自身特色UI：

- **👤 用户中心集成** - 无缝对接子比主题的用户中心系统

- **💼 模态框操作** - 使用模态框完成各种操作，不影响主界面

## 📊 已实现功能

所有核心功能已实现：

- **🔄 冒险团管理**
  - 创建冒险团（设置名称和描述）
  - 查看冒险团列表
  - 查看冒险团详情
  - 解散冒险团（团长权限）

- **👥 成员管理**
  - 添加团队成员（支持用户名/邮箱查找）
  - 移除团队成员（团长/副团长权限）
  - 设置副团长（团长权限）
  - 退出团队（成员权限）
  - 主动加入团队（浏览并加入公开团队）

- **🧙‍♂️ 角色系统**
  - 更新角色信息（名称、职业、等级）
  - 角色信息展示
  - 角色详情系统（属性值、背景故事等）
  - 权限管理（基于团队角色）

- **⚙️ 管理员设置**
  - 可调整团队人数限制（默认20人）
  - 可设置副团长数量限制（默认1名）
  - 可开启/关闭角色详情功能
  - 可配置角色详情表单字段
  - 可设置是否允许自定义角色（限制只能使用预设职业）

## 🧩 角色权限系统

团队角色权限系统：

| 权限 | 团长 | 副团长 | 成员 |
|------|:----:|:-----:|:----:|
| 创建和管理冒险团 | ✅ | ❌ | ❌ |
| 修改团队信息 | ✅ | ✅ | ❌ |
| 添加和移除成员 | ✅ | ✅ | ❌ |
| 查看团队信息 | ✅ | ✅ | ✅ |
| 更新自己的角色信息 | ✅ | ✅ | ✅ |
| 设置副团长 | ✅ | ❌ | ❌ |
| 解散团队 | ✅ | ❌ | ❌ |

## 🎮 支持的游戏类型

- 🐉 D&D 5e

## 🔌 安装方法

1. 下载插件压缩包
2. 将插件文件夹上传到`/wp-content/plugins/`目录
3. 在WordPress管理后台启用插件
4. 完成！现在可以在用户中心找到"我的冒险团"功能了

## 📁 项目结构

```
hoshinoai-adventure/
├── assets/                # 静态资源
│   ├── css/               # 样式文件
│   │   └── style.css      # 主样式表
│   └── js/                # JavaScript文件
│       └── adventure.js   # 主脚本文件
├── includes/              # 核心功能文件
│   ├── teams-functions.php        # 团队管理功能（团队创建、解散、查询等）
│   ├── members-functions.php      # 成员管理功能（成员添加、移除、角色变更等）
│   └── character-details-functions.php  # 角色详情管理功能
├── admin-options.php      # 管理选项页面
├── index.php              # 插件主入口
├── functions.php          # 主要功能函数
└── README.md              # 文档
```

## 💻 主要功能实现

插件主要集成到子比主题用户中心，使用以下钩子：

```php
// 添加用户中心侧边栏按钮
add_filter('zib_user_center_page_sidebar_button_1_args', 'hoshinoai_adventure_sidebar_button');

// 挂钩用户中心主选项卡
add_filter('user_ctnter_main_tabs_array', 'hoshinoai_adventure_main_tab_nav',20);

// 挂钩用户中心选项卡内容
add_filter('main_user_tab_content_adventure', 'hoshinoai_adventure_tab_content',20);
// 模态框使用子比函数
// 主要模态框函数列表：
1. zib_modal($args) - 创建基本模态框
2. zib_get_modal_colorful_header($class, $icon, $content, $close_btn) - 创建炫彩头部的模态框
3. zib_get_refresh_modal_link($args) - 生成通过AJAX刷新内容的模态框链接
4. zib_get_blank_modal_link($args) - 创建空白的模态框链接
5. zib_get_blank_modal($args) - 创建空白模态框结构
6. zib_ajax_notice_modal($type, $msg) - 显示AJAX通知模态框
// 模态框使用实例：
/*
// 创建一个刷新模态框链接
$args = array(
    'tag' => 'a',
    'class' => 'btn',
    'text' => '打开模态框',
    'data_class' => 'modal-mini',
    'query_arg' => array(
        'action' => 'your_action_name',
        'id' => $id
    )
);
echo zib_get_refresh_modal_link($args);

// AJAX处理函数示例
function your_action_name() {
    $content = zib_get_modal_colorful_header('jb-blue', '<i class="fa fa-users"></i>', '标题内容');
    $content .= '<div class="modal-body">模态框内容</div>';
    echo $content;
    exit;
}
add_action('wp_ajax_your_action_name', 'your_action_name');
*/
// AJAX处理钩子
add_action('wp_ajax_hoshinoai_ajax_create_team', 'hoshinoai_ajax_create_team');
add_action('wp_ajax_hoshinoai_team_detail_modal', 'hoshinoai_team_detail_modal');
add_action('wp_ajax_hoshinoai_create_team_modal', 'hoshinoai_create_team_modal');
add_action('wp_ajax_hoshinoai_add_member', 'hoshinoai_ajax_add_member');
add_action('wp_ajax_hoshinoai_remove_member', 'hoshinoai_ajax_remove_member');
add_action('wp_ajax_hoshinoai_set_vice_leader', 'hoshinoai_ajax_set_vice_leader');
add_action('wp_ajax_hoshinoai_dissolve_team', 'hoshinoai_ajax_dissolve_team');
add_action('wp_ajax_hoshinoai_leave_team', 'hoshinoai_ajax_leave_team');
add_action('wp_ajax_hoshinoai_update_character', 'hoshinoai_ajax_update_character');
add_action('wp_ajax_hoshinoai_join_team', 'hoshinoai_ajax_join_team');
add_action('wp_ajax_hoshinoai_transfer_leadership', 'hoshinoai_ajax_transfer_leadership');
```

### 核心功能分工

插件使用清晰的功能分离，将不同职责划分到各个文件中：

- **teams-functions.php**：
  - 冒险团的创建与解散
  - 团队基本信息管理
  - 团队查询与列表功能
  - 团队领导权转移处理
  - 团队状态变更

- **members-functions.php**：
  - 成员添加与移除
  - 成员角色变更（团长/副团长/成员）
  - 角色信息管理
  - 成员权限控制
  - 团队加入与退出处理

- **character-details-functions.php**：
  - 角色详情管理
  - 属性值存储与计算
  - 背景故事与外观描述
  - 角色个性特点记录
  - 详情表单渲染与处理

这种分离架构使代码更易于维护，并有助于开发人员快速找到相关功能的实现代码。

## 📊 数据表结构

### 冒险团表 (hoshinoai_adventure_teams)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 主键 |
| name | VARCHAR(100) | 团名 |
| description | TEXT | 描述 |
| leader_id | bigint(20) | 团长ID |
| vice_leader_id | bigint(20) | 副团长ID |
| status | VARCHAR(20) | 状态 |
| created_at | DATETIME | 创建时间 |
| updated_at | DATETIME | 更新时间 |

### 成员表 (hoshinoai_adventure_members)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 主键 |
| team_id | INT | 冒险团ID |
| user_id | bigint(20) | 用户ID |
| role | VARCHAR(20) | 角色类型(团长/副团长/成员) |
| character_name | VARCHAR(100) | 角色名 |
| character_class | VARCHAR(50) | 职业 |
| character_level | INT | 等级 |
| join_date | DATETIME | 加入时间 |

### 角色详情表 (hoshinoai_adventure_character_details)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 主键 |
| member_id | INT | 成员ID |
| user_id | bigint(20) | 用户ID |
| team_id | INT | 团队ID |
| status | VARCHAR(20) | 状态 |
| strength | INT | 力量属性 |
| dexterity | INT | 敏捷属性 |
| constitution | INT | 体质属性 |
| intelligence | INT | 智力属性 |
| wisdom | INT | 感知属性 |
| charisma | INT | 魅力属性 |
| background | TEXT | 背景故事 |
| appearance | TEXT | 外观描述 |
| personality | TEXT | 性格特点 |
| created_at | DATETIME | 创建时间 |
| updated_at | DATETIME | 更新时间 |

## 🤝 技术支持

有任何问题或建议？请联系：[星野爱](1697391069@qq.com)

---

<div align="center">

**🎯 本插件专为5era冒险者公会设计，与子比主题用户中心集成，但拥有专属UI风格**  
当前版本：1.0.0

</div>