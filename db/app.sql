--
-- 数据库： `binglang`
--
CREATE DATABASE binglang CHARACTER SET utf8;
-- --------------------------------------------------------

use binglang;
--
-- 表的结构 `app_dept`
--

DROP TABLE IF EXISTS `app_dept`;
CREATE TABLE IF NOT EXISTS `app_dept` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `label` varchar(63) NOT NULL COMMENT '名称',
  `pid` int(11) unsigned NOT NULL COMMENT '上级节点',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='部门' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_dept`
--

INSERT INTO `app_dept` (`id`, `label`, `pid`, `enabled`, `update_time`) VALUES
(1, 'FreeAir工作室', 0, b'1', '2020-01-01 09:14:05');

-- --------------------------------------------------------

--
-- 表的结构 `app_dict`
--

DROP TABLE IF EXISTS `app_dict`;
CREATE TABLE IF NOT EXISTS `app_dict` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(11) unsigned NULL DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '类型名',
  `name` varchar(63) NOT NULL COMMENT '键名',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据字典' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_dict`
--

INSERT INTO `app_dict` (`id`, `sort`, `label`, `name`, `enabled`, `update_time`) VALUES
(1, 1, '性别', 'sys_sex', b'1', '2020-01-16 14:54:53'),
(2, 2, '操作类型', 'sys_op_type', b'1', '2020-01-15 09:51:00'),
(3, 3, '党派', 'user_politic', b'1', '2020-01-16 16:19:50');

-- --------------------------------------------------------

--
-- 表的结构 `app_dict_data`
--

DROP TABLE IF EXISTS `app_dict_data`;
CREATE TABLE IF NOT EXISTS `app_dict_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(11) unsigned NULL DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '词条名',
  `name` varchar(63) NOT NULL COMMENT '键名',
  `code` int(11) unsigned NULL DEFAULT NULL COMMENT '键值',
  `enabled` bit(1) NOT NULL,
  `dict_id` int(11) unsigned NOT NULL COMMENT '所属字典id',
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_dict_id` (`dict_id`) USING BTREE,
  CONSTRAINT `fk_dict_id` FOREIGN KEY (`dict_id`) REFERENCES `app_dict` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='数据字典详情' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_dict_data`
--

INSERT INTO `app_dict_data` (`id`, `sort`, `label`, `name`, `code`, `enabled`, `dict_id`, `update_time`) VALUES
(1, 1, '男', 'male', 1, b'1', 1, '2020-01-01 09:14:05'),
(2, 2, '女', 'female', 2, b'1', 1, '2020-01-15 13:26:26'),
(3, 3, '无党派', 'non_politic', 1, b'1', 3, '2020-01-16 15:22:49'),
(4, 4, '共产党员', 'communist_party', 2, b'1', 3, '2020-01-16 15:24:14');

-- --------------------------------------------------------

--
-- 表的结构 `app_job`
--

DROP TABLE IF EXISTS `app_job`;
CREATE TABLE IF NOT EXISTS `app_job` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(31) NOT NULL COMMENT '中文名称',
  `enabled` bit(1) NOT NULL,
  `sort` int(11) unsigned NULL DEFAULT NULL COMMENT '排序',
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='岗位' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_job`
--

INSERT INTO `app_job` (`id`, `label`, `enabled`, `sort`, `update_time`) VALUES
(1, '开发员', b'1', 1, '2020-01-01 09:14:05'),
(2, '测试员', b'1', 2, '2020-01-01 09:14:05');

-- --------------------------------------------------------

--
-- 表的结构 `app_menu`
--

DROP TABLE IF EXISTS `app_menu`;
CREATE TABLE IF NOT EXISTS `app_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` int(11) unsigned NOT NULL COMMENT '菜单-1，按钮-2',
  `name` varchar(63) NULL DEFAULT NULL COMMENT '路由/组件名称',
  `path` varchar(63) NULL DEFAULT NULL COMMENT '路由Path',
  `component` varchar(127) NULL DEFAULT NULL COMMENT '组件懒加载',
  `redirect` varchar(127) NULL DEFAULT NULL COMMENT '重定向',
  `hidden` bit(1) NOT NULL DEFAULT b'0' COMMENT '侧边栏隐藏',
  `alwaysShow` bit(1) NOT NULL DEFAULT b'0' COMMENT '侧边栏显示顶级目录',
  `title` varchar(31) NULL DEFAULT NULL COMMENT '菜单标题',
  `icon` varchar(127) NULL DEFAULT NULL COMMENT '图标',
  `noCache` bit(1) NOT NULL DEFAULT b'1' COMMENT '页面缓存',
  `breadcrumb` bit(1) NOT NULL DEFAULT b'1' COMMENT '面包屑显示',
  `roles` varchar(255) NULL DEFAULT NULL COMMENT '权限',
  `sort` int(11) unsigned NULL DEFAULT NULL COMMENT '排序',
  `pid` int(11) unsigned NOT NULL COMMENT '上级菜单ID',
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uc_name` (`name`),
  KEY `FKqcf9gem97gqa5qjm4d3elcqt5` (`pid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='页面菜单' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_menu`
--

INSERT INTO `app_menu` (`id`, `type`, `name`, `path`, `component`, `redirect`, `hidden`, `alwaysShow`, `title`, `icon`, `noCache`, `breadcrumb`, `roles`, `sort`, `pid`, `update_time`) VALUES
(1, 1, 'Admin', 'admin', '', '', b'0', b'0', '系统管理', 'system', b'1', b'1', 'admin:list', 1, 0, '2020-01-15 21:01:54'),
(2, 1, 'AdminMenu', 'menu', '', '', b'0', b'0', '菜单管理', 'menu', b'1', b'1', 'admin:menu:list', 1, 1, '2020-01-15 21:03:15'),
(3, 1, 'AdminDept', 'dept', '', '', b'0', b'0', '部门管理', 'dept', b'1', b'1', 'admin:dept:list', 2, 1, '2020-01-15 21:04:02');

-- --------------------------------------------------------

--
-- 表的结构 `app_role`
--

DROP TABLE IF EXISTS `app_role`;
CREATE TABLE IF NOT EXISTS `app_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sort` int(11) unsigned NULL DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '名称',
  `name` varchar(63) NOT NULL COMMENT '键名',
  `enabled` bit(1) NOT NULL,
  `remark` varchar(127) NULL DEFAULT NULL COMMENT '备注',
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_role`
--

INSERT INTO `app_role` (`id`, `sort`, `label`, `name`, `enabled`, `remark`, `update_time`) VALUES
(1, 1, '管理组', 'admin_group', b'1', '指派管理员权限', '2020-01-16 08:45:52');

-- --------------------------------------------------------

--
-- 表的结构 `app_roles_menus`
--

DROP TABLE IF EXISTS `app_roles_menus`;
CREATE TABLE IF NOT EXISTS `app_roles_menus` (
  `role_id` int(11) unsigned NOT NULL COMMENT '角色ID',
  `menu_id` int(11) unsigned NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`,`menu_id`) USING BTREE,
  KEY `FKcngg2qadojhi3a651a5adkvbq` (`role_id`) USING BTREE,
  KEY `FKo7wsmlrrxb2osfaoavp46rv2r` (`menu_id`) USING BTREE,
  CONSTRAINT `FKgd3iendaoyh04b95ykqise6qh` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FKt4v0rrweyk393bdgt107vdx0x` FOREIGN KEY (`menu_id`) REFERENCES `app_menu` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色菜单关联' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_roles_menus`
--

INSERT INTO `app_roles_menus` (`role_id`, `menu_id`) VALUES
(1, 1),
(1, 2),
(1, 3);


-- ----------------------------
-- Table structure for app_user_avatar
-- ----------------------------
DROP TABLE IF EXISTS `app_user_avatar`;
CREATE TABLE `app_user_avatar`  (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `real_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '真实文件名',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路径',
  `size` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '大小',
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户头像' ROW_FORMAT = Compact;


-- ----------------------------
-- Table structure for app_user
-- ----------------------------
DROP TABLE IF EXISTS `app_user`;
CREATE TABLE `app_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `employee_number` varchar(15) NULL DEFAULT NULL COMMENT '工号',
  `username` varchar(10) NULL DEFAULT NULL COMMENT '中文名',
  `sex` varchar(3) NULL DEFAULT NULL,
  `identity_document_number` varchar(32) NULL DEFAULT NULL COMMENT '证件号',
  `phone` varchar(15) NOT NULL,
  `email` varchar(40) NULL DEFAULT NULL,
  `enabled` bit(1) NOT NULL,
  `dept_id` int(11) unsigned NULL DEFAULT NULL COMMENT '部门',
  `job_id` int(11) unsigned NULL DEFAULT NULL COMMENT '岗位',
  `last_login` datetime NULL DEFAULT NULL COMMENT '登录日期',
  `ip_address` varchar(63) NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新日期',
  `avatar_id` int(11) unsigned NULL DEFAULT NULL COMMENT '头像',
  `password` varchar(255) NULL DEFAULT NULL,
  `forgotten_password_selector` varchar(255) NULL DEFAULT NULL,
  `forgotten_password_code` varchar(255) NULL DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  CONSTRAINT `uc_email` UNIQUE (`email`) USING BTREE,
  CONSTRAINT `uc_phone` UNIQUE (`phone`) USING BTREE,
  CONSTRAINT `uc_forgotten_password_selector` UNIQUE (`forgotten_password_selector`) USING BTREE,
  INDEX `FK5rwmryny6jthaaxkogownknqp`(`dept_id`) USING BTREE,
  INDEX `FKfftoc2abhot8f2wu6cl9a5iky`(`job_id`) USING BTREE,
  INDEX `FKpq2dhypk2qgt68nauh2by22jb`(`avatar_id`) USING BTREE,
  CONSTRAINT `FK5rwmryny6jthaaxkogownknqp` FOREIGN KEY (`dept_id`) REFERENCES `app_dept` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FKfftoc2abhot8f2wu6cl9a5iky` FOREIGN KEY (`job_id`) REFERENCES `app_job` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FKpq2dhypk2qgt68nauh2by22jb` FOREIGN KEY (`avatar_id`) REFERENCES `app_user_avatar` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户' ROW_FORMAT = Compact;

INSERT INTO `app_user` (`id`, `employee_number`, `username`, `phone`, `enabled`, `sex`, `update_time`) VALUES
(1, '1', '赵钱孙', '18612345678', b'1', '男', '2020-01-16 08:45:52');

-- ----------------------------
-- Table structure for app_user_attribute
-- ----------------------------
DROP TABLE IF EXISTS `app_user_attribute`;
CREATE TABLE `app_user_attribute`  (
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `dict_data_id` int(11) unsigned NOT NULL COMMENT '字典数据ID',
  PRIMARY KEY (`user_id`, `dict_data_id`) USING BTREE,
  INDEX `FK_user_id_dsf`(`user_id`) USING BTREE,
  INDEX `FK_dict_data_id_dsf`(`dict_data_id`) USING BTREE,
  CONSTRAINT `FK_user_id_dsf` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_dict_data_id_dsf` FOREIGN KEY (`dict_data_id`) REFERENCES `app_dict_data` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户字典关联' ROW_FORMAT = Compact;


-- ----------------------------
-- Table structure for app_users_roles
-- ----------------------------
DROP TABLE IF EXISTS `app_users_roles`;
CREATE TABLE `app_users_roles`  (
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `role_id` int(11) unsigned NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`, `role_id`) USING BTREE,
  INDEX `FK_user_id_se6qh`(`user_id`) USING BTREE,
  CONSTRAINT `FK_user_id_se6qh` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FK_role_id_se6qh` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色关联' ROW_FORMAT = Compact;




