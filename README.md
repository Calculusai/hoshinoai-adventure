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
- 每个团队最大成员数量
- 每个团队最大副团长数量
- 是否启用角色详情功能
- 是否允许自定义职业
- 预设职业选项
- 角色详情显示的字段
- 高级数据处理选项

## 开发者

- 星野爱
- 网站：www.5era.cn

## 版本

- 版本：1.0.0
- 日期：2025-04-29

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
  - 团长权限转移

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
  - 可开启/关闭数据导出功能

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
| 转移团长权限 | ✅ | ❌ | ❌ |

## 🎮 支持的游戏类型

- 🐉 D&D
- 🎭 自定义角色扮演游戏

## 🔌 安装方法

1. 下载插件压缩包
2. 将插件文件夹上传到`/wp-content/plugins/`目录
3. 在WordPress管理后台启用插件
4. 完成！现在可以在用户中心找到"我的冒险团"功能了

## 📁 项目结构

```
hoshinoai-adventure/
├── index.php              # 插件主入口
├── functions.php          # 主要功能函数
├── admin-options.php      # 管理选项页面
├── style.css              # 样式文件
├── includes/              # 核心功能文件
│   ├── database.php                    # 数据库管理模块
│   ├── teams-functions.php             # 团队管理功能
│   ├── members-functions.php           # 成员管理功能
│   └── character-details-functions.php # 角色详情管理功能
└── README.md              # 文档
```

## 💻 主要功能模块

### 1. 数据库模块 (database.php)

负责数据表的创建、更新和维护：
- 冒险团表 (hoshinoai_adventure_teams)
- 成员表 (hoshinoai_adventure_members)
- 角色详情表 (hoshinoai_adventure_character_details)
- 入团申请表 (hoshinoai_adventure_join_requests)

### 2. 团队管理模块 (teams-functions.php)

处理团队相关的所有操作：
- 创建冒险团
- 获取团队信息
- 更新团队信息
- 解散团队
- 团长权限转移
- 查询用户创建的团队
- 查询用户加入的团队
- 查询公开团队列表

### 3. 成员管理模块 (members-functions.php)

管理团队成员相关功能：
- 添加团队成员
- 移除团队成员
- 获取成员信息
- 获取团队所有成员
- 设置副团长
- 更新角色信息
- 退出团队
- 处理入团申请

### 4. 角色详情模块 (character-details-functions.php)

提供角色详情管理功能：
- 创建或更新角色详情
- 获取角色详情信息
- 计算属性修正值
- 获取角色职业列表
- 角色详情表单生成
- 角色详情显示生成

### 5. 主功能模块 (functions.php)

包含核心功能和用户界面：
- 用户中心集成
- 用户界面渲染
- AJAX交互处理
- 模态框生成
- 团队详情显示
- 角色管理界面

### 6. 管理选项模块 (admin-options.php)

提供后台管理设置页面：
- 基本设置（团队数量限制等）
- 角色设置（职业、详情等）
- 高级设置（数据导出等）

## 📊 数据表结构

### 冒险团表 (hoshinoai_adventure_teams)
存储团队的基本信息

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 团队ID，主键 |
| name | VARCHAR(100) | 团队名称 |
| description | TEXT | 团队描述 |
| leader_id | BIGINT | 团长用户ID |
| vice_leader_id | BIGINT | 副团长用户ID |
| status | VARCHAR(20) | 团队状态：active-活跃，inactive-不活跃 |
| avatar_url | VARCHAR(255) | 团队头像URL |
| is_public | TINYINT | 是否公开：1-公开，0-私密 |
| created_at | DATETIME | 创建时间 |
| updated_at | DATETIME | 更新时间 |

### 成员表 (hoshinoai_adventure_members)
存储团队成员的信息

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 成员ID，主键 |
| team_id | INT | 所属团队ID |
| user_id | BIGINT | 用户ID |
| role | VARCHAR(20) | 角色：leader-团长，vice_leader-副团长，member-普通成员 |
| character_name | VARCHAR(100) | 角色名称 |
| character_class | VARCHAR(50) | 角色职业 |
| character_level | INT | 角色等级 |
| join_date | DATETIME | 加入日期 |
| last_active | DATETIME | 最后活跃时间 |

### 角色详情表 (hoshinoai_adventure_character_details)
存储角色的详细信息

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 详情ID，主键 |
| member_id | INT | 成员ID |
| user_id | BIGINT | 用户ID |
| team_id | INT | 团队ID |
| status | VARCHAR(20) | 状态：active-活跃，inactive-不活跃 |
| strength | INT | 力量属性 |
| dexterity | INT | 敏捷属性 |
| constitution | INT | 体质属性 |
| intelligence | INT | 智力属性 |
| wisdom | INT | 感知属性 |
| charisma | INT | 魅力属性 |
| background | TEXT | 角色背景故事 |
| appearance | TEXT | 角色外貌描述 |
| personality | TEXT | 角色性格特点 |
| created_at | DATETIME | 创建时间 |
| updated_at | DATETIME | 更新时间 |

### 入团申请表 (hoshinoai_adventure_join_requests)
存储加入团队的申请记录

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | INT | 申请ID，主键 |
| team_id | INT | 团队ID |
| user_id | BIGINT | 用户ID |
| request_message | TEXT | 申请留言 |
| status | VARCHAR(20) | 申请状态：pending-待处理，approved-已批准，rejected-已拒绝 |
| request_date | DATETIME | 申请日期 |
| processed_by | BIGINT | 处理人ID |
| processed_date | DATETIME | 处理日期 |