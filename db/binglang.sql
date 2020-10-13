--
-- 数据库： `binglang`
--
CREATE DATABASE IF NOT EXISTS `binglang` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `binglang`;

-- --------------------------------------------------------

--
-- 表的结构 `app_dept`
--

DROP TABLE IF EXISTS `app_dept`;
CREATE TABLE IF NOT EXISTS `app_dept` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(63) NOT NULL COMMENT '名称',
  `pid` int(11) UNSIGNED NOT NULL COMMENT '上级节点',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY (`sort`) USING BTREE,
  KEY (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='部门' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_dept`
--

INSERT INTO `app_dept` (`id`, `sort`, `label`, `pid`, `enabled`, `update_time`) VALUES
(1, 1, 'FreeAir工作室', 0, b'1', '2020-01-01 09:14:05'),
(2, 1, '开发组', 1, b'1', '2020-02-06 22:17:00'),
(3, 2, '测试组', 1, b'1', '2020-02-06 22:17:05'),
(7, 1, '测试一组', 3, b'1', '2020-02-09 22:31:19'),
(8, 2, '开发二组', 2, b'1', '2020-02-06 22:17:21'),
(13, 3, '后勤组', 1, b'1', '2020-02-06 22:17:11'),
(15, 1, '后勤一组', 13, b'1', '2020-02-06 22:05:56'),
(17, 1, '开发一组', 2, b'1', '2020-02-06 22:17:16'),
(18, 2, '后勤二组', 13, b'1', '2020-02-06 22:05:52');

-- --------------------------------------------------------

--
-- 表的结构 `app_dict`
--

DROP TABLE IF EXISTS `app_dict`;
CREATE TABLE IF NOT EXISTS `app_dict` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(20) NOT NULL COMMENT '类型名',
  `name` varchar(63) NOT NULL COMMENT '键名',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `key_dict_sort` (`sort`) USING BTREE,
  UNIQUE KEY `uc_dict_label` (`label`) USING BTREE,
  UNIQUE KEY `uc_dict_name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='数据词典' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_dict`
--

INSERT INTO `app_dict` (`id`, `sort`, `label`, `name`, `enabled`, `update_time`) VALUES
(1, 1, '性别', 'sys_sex', b'1', '2020-01-16 14:54:53'),
(2, 2, '操作类型', 'sys_op_type', b'1', '2020-01-15 09:51:00');

-- --------------------------------------------------------

--
-- 表的结构 `app_dict_data`
--

DROP TABLE IF EXISTS `app_dict_data`;
CREATE TABLE IF NOT EXISTS `app_dict_data` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '词条名',
  `name` varchar(255) NOT NULL COMMENT '键名',
  `code` int(11) UNSIGNED DEFAULT NULL COMMENT '键值',
  `enabled` bit(1) NOT NULL,
  `dict_id` int(11) UNSIGNED NOT NULL COMMENT '所属字典id',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `key_dict_data_sort` (`sort`) USING BTREE,
  UNIQUE KEY `uc_dict_data_label` (`label`) USING BTREE,
  UNIQUE KEY `uc_dict_data_name` (`name`) USING BTREE,
  KEY `fk_dict_data_ref_dict_id` (`dict_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='数据词条' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_dict_data`
--

INSERT INTO `app_dict_data` (`id`, `sort`, `label`, `name`, `code`, `enabled`, `dict_id`, `update_time`) VALUES
(1, 1, '男', 'male', 1, b'1', 1, '2020-01-01 09:14:05'),
(2, 2, '女', 'female', 2, b'1', 1, '2020-01-15 13:26:26');

-- --------------------------------------------------------

--
-- 表的结构 `app_job`
--

DROP TABLE IF EXISTS `app_job`;
CREATE TABLE IF NOT EXISTS `app_job` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '中文名称',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='岗位' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_job`
--

INSERT INTO `app_job` (`id`, `sort`, `label`, `enabled`, `update_time`) VALUES
(1, 1, '开发员', b'1', '2020-01-01 09:14:05'),
(2, 2, '测试员', b'1', '2020-01-01 09:14:05'),
(3, 3, '保洁员', b'1', '2020-02-07 20:43:28'),
(4, 5, '采购员', b'1', '2020-02-07 20:45:52'),
(5, 4, '销售员', b'1', '2020-02-07 20:47:43');

-- --------------------------------------------------------

--
-- 表的结构 `app_politic`
--

DROP TABLE IF EXISTS `app_politic`;
CREATE TABLE IF NOT EXISTS `app_politic` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '中文名称',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='政治面貌' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_politic`
--

INSERT INTO `app_politic` (`id`, `sort`, `label`, `enabled`, `update_time`) VALUES
(1, 1, '群众', b'1', '2020-01-01 09:14:05'),
(2, 2, '中共党员', b'1', '2020-01-01 09:14:05');

-- --------------------------------------------------------

--
-- 表的结构 `app_professional_title`
--

DROP TABLE IF EXISTS `app_professional_title`;
CREATE TABLE IF NOT EXISTS `app_professional_title` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '中文名称',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='职称' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_professional_title`
--

INSERT INTO `app_professional_title` (`id`, `sort`, `label`, `enabled`, `update_time`) VALUES
(1, 1, '工程师', b'1', '2020-01-01 09:14:05'),
(2, 2, '助理工程师', b'1', '2020-01-01 09:14:05');

-- --------------------------------------------------------

--
-- 表的结构 `app_menu`
--

DROP TABLE IF EXISTS `app_menu`;
CREATE TABLE IF NOT EXISTS `app_menu` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` int(11) UNSIGNED NOT NULL COMMENT '菜单-1，按钮-2',
  `name` varchar(63) DEFAULT NULL COMMENT '路由/组件名称',
  `path` varchar(63) DEFAULT NULL COMMENT '路由Path',
  `component` varchar(127) DEFAULT NULL COMMENT '组件懒加载',
  `redirect` varchar(127) DEFAULT NULL COMMENT '重定向',
  `hidden` bit(1) NOT NULL DEFAULT b'0' COMMENT '侧边栏隐藏',
  `alwaysShow` bit(1) NOT NULL DEFAULT b'0' COMMENT '侧边栏显示顶级目录',
  `title` varchar(31) NOT NULL COMMENT '菜单标题',
  `icon` varchar(63) DEFAULT NULL COMMENT '图标',
  `noCache` bit(1) NOT NULL DEFAULT b'1' COMMENT '页面缓存',
  `breadcrumb` bit(1) NOT NULL DEFAULT b'1' COMMENT '面包屑显示',
  `roles` varchar(255) DEFAULT NULL COMMENT '权限',
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `pid` int(11) UNSIGNED NOT NULL COMMENT '上级菜单ID',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uc_name` (`name`),
  KEY `key_menu_pid` (`pid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='页面菜单' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_menu`
--

INSERT INTO `app_menu` (`id`, `type`, `name`, `path`, `component`, `redirect`, `hidden`, `alwaysShow`, `title`, `icon`, `noCache`, `breadcrumb`, `roles`, `sort`, `pid`, `update_time`) VALUES
(1, 1, 'Admin', 'admin', 'Layout', 'noRedirect', b'0', b'0', '系统管理', 'system', b'1', b'1', 'admin:get', 1, 0, '2020-02-08 16:56:48'),
(2, 1, 'AdminDept', 'dept', 'admin/dept/index', '', b'0', b'0', '部门管理', 'dept', b'1', b'1', 'dept:get', 1, 1, '2020-02-08 16:06:07'),
(3, 1, 'AdminJob', 'job', 'admin/job/index', '', b'0', b'0', '岗位管理', 'skill', b'1', b'1', 'job:get', 2, 1, '2020-02-08 16:10:10'),
(4, 1, 'AdminUser', 'user', 'admin/user/index', '', b'0', b'0', '用户管理', 'peoples', b'1', b'1', 'user:get', 3, 1, '2020-02-08 16:11:26'),
(5, 1, 'AdminRole', 'role', 'admin/role/index', '', b'0', b'0', '角色管理', 'permission', b'1', b'1', 'role:get', 4, 1, '2020-02-08 16:12:50'),
(6, 1, 'AdminMenu', 'menu', 'admin/menu/index', '', b'0', b'0', '菜单管理', 'menu', b'1', b'1', 'menu:get', 5, 1, '2020-02-08 16:13:49'),
(7, 1, 'AdminDict', 'dict', 'admin/dict/index', '', b'0', b'0', '词典管理', 'dictionary', b'1', b'1', 'dict:get', 6, 1, '2020-02-08 16:15:52'),
(8, 1, 'AdminDictData', 'dict-data', 'admin/dict/dict-data', '', b'0', b'0', '词条管理', 'documentation', b'1', b'1', 'dict-data:get', 7, 1, '2020-02-09 22:53:24');

-- --------------------------------------------------------

--
-- 表的结构 `app_role`
--

DROP TABLE IF EXISTS `app_role`;
CREATE TABLE IF NOT EXISTS `app_role` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sort` int(11) UNSIGNED DEFAULT NULL COMMENT '排序',
  `label` varchar(31) NOT NULL COMMENT '名称',
  `name` varchar(63) NOT NULL COMMENT '键名',
  `enabled` bit(1) NOT NULL,
  `remark` varchar(127) DEFAULT NULL COMMENT '备注',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_role`
--

INSERT INTO `app_role` (`id`, `sort`, `label`, `name`, `enabled`, `remark`, `update_time`) VALUES
(1, 1, '管理组', 'admin_group', b'1', '具有管理员权限', '2020-02-07 21:45:26'),
(2, 2, '访客组', 'guest_group', b'1', '具有访客权限', '2020-02-07 21:45:19'),
(3, 3, '开发组', 'develop_group', b'1', '具有开发员的权限', '2020-02-07 21:44:37'),
(4, 4, '测试组', 'test_group', b'1', '具有测试员权限', '2020-02-07 21:46:06'),
(6, 6, '保障组', 'guard_group', b'1', '', '2020-02-07 21:47:03');

-- --------------------------------------------------------

--
-- 表的结构 `app_roles_menus`
--

DROP TABLE IF EXISTS `app_roles_menus`;
CREATE TABLE IF NOT EXISTS `app_roles_menus` (
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色ID',
  `menu_id` int(11) UNSIGNED NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`,`menu_id`) USING BTREE,
  KEY `fk_roles_menus_ref_role_id` (`role_id`) USING BTREE,
  KEY `fk_roles_menus_ref_menu_id` (`menu_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色菜单关联' ROW_FORMAT=COMPACT;


-- --------------------------------------------------------

--
-- 表的结构 `app_user`
--

DROP TABLE IF EXISTS `app_user`;
CREATE TABLE IF NOT EXISTS `app_user` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sort` int(11) UNSIGNED NOT NULL COMMENT '工号',
  `username` varchar(10) NOT NULL COMMENT '中文名',
  `sex` bit(1) NOT NULL COMMENT '1-女，0-男',
  `identity_document_number` varchar(31) DEFAULT NULL COMMENT '证件号',
  `phone` varchar(15) NOT NULL,
  `email` varchar(63) NOT NULL,
  `enabled` bit(1) NOT NULL,
  `attr_01_id` int(11) UNSIGNED DEFAULT NULL COMMENT '部门',
  `attr_02_id` int(11) UNSIGNED DEFAULT NULL COMMENT '岗位',
  `attr_03_id` int(11) UNSIGNED DEFAULT NULL COMMENT '政治面貌',
  `attr_04_id` int(11) UNSIGNED DEFAULT NULL COMMENT '职称',
  `attr_05_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_06_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_07_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_08_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_09_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_10_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_11_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_12_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_13_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_14_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_15_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_16_id` int(11) UNSIGNED DEFAULT NULL COMMENT '预留',
  `attr_text_01` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_02` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_03` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_04` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_05` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_06` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_07` varchar(63) DEFAULT NULL COMMENT '预留',
  `attr_text_08` varchar(63) DEFAULT NULL COMMENT '预留',  
  `last_login` int(11) unsigned DEFAULT NULL,
  `ip_address` varchar(63) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL COMMENT '更新日期',
  `avatar_id` int(11) UNSIGNED DEFAULT NULL COMMENT '头像',
  `password` varchar(255) NOT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uc_user_sort` (`sort`) USING BTREE,
  UNIQUE KEY `uc_phone` (`phone`) USING BTREE,
  UNIQUE KEY `uc_email` (`email`) USING BTREE,
  UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`) USING BTREE,
  KEY `key_username` (`username`) USING BTREE,
  KEY `fk_user_ref_attr_01_id` (`attr_01_id`) USING BTREE,
  KEY `fk_user_ref_attr_02_id` (`attr_02_id`) USING BTREE,
  KEY `fk_user_ref_attr_03_id` (`attr_03_id`) USING BTREE,
  KEY `fk_user_ref_attr_04_id` (`attr_04_id`) USING BTREE,
  KEY `fk_user_ref_attr_05_id` (`attr_05_id`) USING BTREE,
  KEY `fk_user_ref_attr_06_id` (`attr_06_id`) USING BTREE,
  KEY `fk_user_ref_attr_07_id` (`attr_07_id`) USING BTREE,
  KEY `fk_user_ref_attr_08_id` (`attr_08_id`) USING BTREE,
  KEY `fk_user_ref_attr_09_id` (`attr_09_id`) USING BTREE,
  KEY `fk_user_ref_attr_10_id` (`attr_10_id`) USING BTREE,
  KEY `fk_user_ref_attr_11_id` (`attr_11_id`) USING BTREE,
  KEY `fk_user_ref_attr_12_id` (`attr_12_id`) USING BTREE,
  KEY `fk_user_ref_avatar_id` (`avatar_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `app_user`
--

INSERT INTO `app_user` (`id`, `sort`, `username`, `sex`, `identity_document_number`, `phone`, `email`, `enabled`, `attr_01_id`, `attr_02_id`, `attr_03_id`, `attr_04_id`, `last_login`, `ip_address`, `update_time`, `avatar_id`, `password`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`) VALUES
(1, 1, '小明', b'0', '', '13812345678', '1@1.1', b'1', 2, 1, 1, 1, NULL, NULL, '2020-01-29 20:49:47', NULL, '$argon2i$v=19$m=16384,t=4,p=2$dHZ3VWhZTjlYQkhzaFMyTQ$WAYgS/nXzAKyG3NSESSfaKIgM9ofhIQXddnpT0PscXo', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `app_users_roles`
--

DROP TABLE IF EXISTS `app_users_roles`;
CREATE TABLE IF NOT EXISTS `app_users_roles` (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`,`role_id`) USING BTREE,
  KEY `fk_users_roles_ref_user_id` (`user_id`) USING BTREE,
  KEY `fk_users_roles_ref_role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色关联' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `app_user_avatar`
--

DROP TABLE IF EXISTS `app_user_avatar`;
CREATE TABLE IF NOT EXISTS `app_user_avatar` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `real_name` varchar(255) DEFAULT NULL COMMENT '真实文件名',
  `path` varchar(255) DEFAULT NULL COMMENT '路径',
  `size` varchar(255) DEFAULT NULL COMMENT '大小',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户头像' ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- 表的结构 `app_users_roles`
--

DROP TABLE IF EXISTS `app_login_attempts`;
CREATE TABLE `app_login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `identity` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `app_verification_code` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(15) NOT NULL,
  `code` varchar(5) NULL,
  `created_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `uc_verification_code_phone` (`phone`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 限制导出的表
--

--
-- 限制表 `app_dict_data`
--
ALTER TABLE `app_dict_data`
  ADD CONSTRAINT `fk_dict_data_ref_dict_id` FOREIGN KEY (`dict_id`) REFERENCES `app_dict` (`id`);

--
-- 限制表 `app_roles_menus`
--
ALTER TABLE `app_roles_menus`
  ADD CONSTRAINT `fk_roles_menus_ref_menu_id` FOREIGN KEY (`menu_id`) REFERENCES `app_menu` (`id`),
  ADD CONSTRAINT `fk_roles_menus_ref_role_id` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`);

--
-- 限制表 `app_user`
--
ALTER TABLE `app_user`
  ADD CONSTRAINT `fk_user_ref_avatar_id` FOREIGN KEY (`avatar_id`) REFERENCES `app_user_avatar` (`id`),
  ADD CONSTRAINT `fk_user_ref_attr_01_id` FOREIGN KEY (`attr_01_id`) REFERENCES `app_dept` (`id`),
  ADD CONSTRAINT `fk_user_ref_attr_02_id` FOREIGN KEY (`attr_02_id`) REFERENCES `app_job` (`id`),
  ADD CONSTRAINT `fk_user_ref_attr_03_id` FOREIGN KEY (`attr_03_id`) REFERENCES `app_politic` (`id`),
  ADD CONSTRAINT `fk_user_ref_attr_04_id` FOREIGN KEY (`attr_04_id`) REFERENCES `app_professional_title` (`id`);

--
-- 限制表 `app_users_roles`
--
ALTER TABLE `app_users_roles`
  ADD CONSTRAINT `fk_users_roles_ref_role_id` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`id`),
  ADD CONSTRAINT `fk_users_roles_ref_user_id` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`);

COMMIT;

