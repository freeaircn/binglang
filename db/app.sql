CREATE DATABASE binglang CHARACTER SET utf8;

--
-- 表的结构 `app_menu`
--
DROP TABLE IF EXISTS `app_menu`;
CREATE TABLE `app_menu`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` int(11) NULL DEFAULT NULL COMMENT '菜单-1，按钮-2',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路由/组件名称',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '路由Path',
  `component` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '组件懒加载',
  `redirect` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '重定向',
  `hidden` bit(1) NULL DEFAULT b'0' COMMENT '侧边栏隐藏',
  `alwaysShow` bit(1) NULL DEFAULT b'0' COMMENT '侧边栏显示顶级目录',
  `title` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单标题',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `noCache` bit(1) NULL DEFAULT b'1' COMMENT '页面缓存',
  `breadcrumb` bit(1) NULL DEFAULT b'1' COMMENT '面包屑显示',
  `roles` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权限',
  `sort` int(11) NULL DEFAULT NULL COMMENT '排序',
  `pid` bigint(20) NOT NULL COMMENT '上级菜单ID',  
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新日期',
  PRIMARY KEY (`id`) USING BTREE,
  CONSTRAINT `uc_name` UNIQUE (`name`),
  INDEX `FKqcf9gem97gqa5qjm4d3elcqt5`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

INSERT INTO `app_menu` VALUES (1, 1, 'SysAdmin', 'sys-admin', 'src/layout', 'noRedirect', b'0', b'0', '系统管理', 'system', b'1', b'1', NULL, 1, 0, '2020-01-01 00:00:00');


--
-- 表的结构 `app_dept`
--
DROP TABLE IF EXISTS `app_dept`;
CREATE TABLE `app_dept`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `pid` bigint(20) NOT NULL COMMENT '上级节点',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

INSERT INTO `app_dept` VALUES (1, 'FreeAir Studio', 0, b'1', '2020-01-01 09:14:05');

--
-- 表的结构 `app_job`
--
-- DROP TABLE IF EXISTS `app_job`;
-- CREATE TABLE `app_job`  (
--   `id` bigint(20) NOT NULL AUTO_INCREMENT,
--   `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '中文名称',
--   `enabled` bit(1) NOT NULL,
--   `sort` bigint(20) NOT NULL,
--   `dept_id` bigint(20) NULL DEFAULT NULL,
--   `update_time` datetime NULL DEFAULT NULL,
--   PRIMARY KEY (`id`) USING BTREE,
--   INDEX `FKmvhj0rogastlctflsxf1d6k3i`(`dept_id`) USING BTREE,
--   CONSTRAINT `FKmvhj0rogastlctflsxf1d6k3i` FOREIGN KEY (`dept_id`) REFERENCES `app_dept` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
-- ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- INSERT INTO `app_job` VALUES (1, '开发员1', b'1', 1, 10, '2020-01-01 09:14:05');
-- INSERT INTO `app_job` VALUES (2, '开发员2', b'1', 2, 10, '2020-01-01 09:14:05');

DROP TABLE IF EXISTS `app_job`;
CREATE TABLE `app_job`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '中文名称',
  `enabled` bit(1) NOT NULL,
  `sort` int(11) NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

INSERT INTO `app_job` VALUES (1, '开发员', b'1', 1, '2020-01-01 09:14:05');
INSERT INTO `app_job` VALUES (2, '测试员', b'1', 2, '2020-01-01 09:14:05');



--
-- 表的结构 `app_dict`
--
CREATE TABLE `app_dict` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NULL DEFAULT NULL COMMENT '排序',
  `label` varchar(50) NULL COMMENT '类型名',
  `name` varchar(50) NULL COMMENT '键名',
  `enabled` bit(1) NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '数据字典' ROW_FORMAT = Compact;

INSERT INTO `app_dict` VALUES (1, 1, '用户性别', 'sex', b'1', '2020-01-01 09:14:05');


CREATE TABLE `app_dict_data` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NULL DEFAULT NULL COMMENT '排序',
  `label` varchar(50) NULL COMMENT '词条名',
  `name` varchar(50) NULL COMMENT '键名',
  `code` int(11) unsigned NULL COMMENT '键值',
  `enabled` bit(1) NOT NULL,
  `dict_id` bigint(11) NOT NULL COMMENT '所属字典id',
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_dict_id`(`dict_id`) USING BTREE,
  CONSTRAINT `fk_dict_id` FOREIGN KEY (`dict_id`) REFERENCES `app_dict` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '数据字典详情' ROW_FORMAT = Compact;

INSERT INTO `app_dict_data` VALUES (1, 1, '男', 'male', 1, b'1', 1, '2020-01-01 09:14:05');
INSERT INTO `app_dict_data` VALUES (2, 2, '女', 'female', 2, b'1', 1, '2020-01-01 09:14:05');


-- ----------------------------
-- Table structure for app_role
-- ----------------------------
DROP TABLE IF EXISTS `app_role`;
CREATE TABLE `app_role`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sort` int(11) NULL DEFAULT NULL COMMENT '排序',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '键名',
  `enabled` bit(1) NOT NULL,
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (1, 1, '管理员', 'role_admin', b'1', '-', '2020-01-01 09:14:05');
INSERT INTO `role` VALUES (2, 2, '访客', 'role_guest', b'1', '-', '2020-01-01 09:14:05');


-- ----------------------------
-- Table structure for app_roles_menus
-- ----------------------------
DROP TABLE IF EXISTS `app_roles_menus`;
CREATE TABLE `app_roles_menus`  (
  `role_id` bigint(20) NOT NULL COMMENT '角色ID',
  `menu_id` bigint(20) NOT NULL COMMENT '菜单ID',
  PRIMARY KEY (`role_id`, `menu_id`) USING BTREE,
  INDEX `FKcngg2qadojhi3a651a5adkvbq`(`role_id`) USING BTREE,
  CONSTRAINT `FKtag324maketmxffly3pdyh193` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FKo7wsmlrrxb2osfaoavp46rv2r` FOREIGN KEY (`menu_id`) REFERENCES `app_menu` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色菜单关联' ROW_FORMAT = Compact;


-- ----------------------------
-- Table structure for app_roles_depts
-- ----------------------------
DROP TABLE IF EXISTS `app_roles_depts`;
CREATE TABLE `app_roles_depts`  (
  `role_id` bigint(20) NOT NULL COMMENT '角色ID',
  `dept_id` bigint(20) NOT NULL COMMENT '部门ID',
  PRIMARY KEY (`role_id`, `dept_id`) USING BTREE,
  INDEX `FKcngg2qadojhi3a651a5adkvbq`(`role_id`) USING BTREE,
  CONSTRAINT `FKtag324maketmxffly3pdyh193` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FKo7wsmlrrxb2osfaoavp46rv2r` FOREIGN KEY (`dept_id`) REFERENCES `app_dept` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色部门关联' ROW_FORMAT = Compact;


-- ----------------------------
-- Table structure for app_users_roles
-- ----------------------------
DROP TABLE IF EXISTS `app_users_roles`;
CREATE TABLE `app_users_roles`  (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `role_id` bigint(20) NOT NULL COMMENT '角色ID',
  PRIMARY KEY (`user_id`, `role_id`) USING BTREE,
  INDEX `FKq4eq273l04bpu4efj0jd0jb98`(`role_id`) USING BTREE,
  CONSTRAINT `FKgd3iendaoyh04b95ykqise6qh` FOREIGN KEY (`user_id`) REFERENCES `app_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `FKt4v0rrweyk393bdgt107vdx0x` FOREIGN KEY (`role_id`) REFERENCES `app_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户角色关联' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users_roles
-- ----------------------------
INSERT INTO `users_roles` VALUES (1, 1);
INSERT INTO `users_roles` VALUES (3, 2);
