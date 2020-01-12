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
  `sort` bigint(20) NULL DEFAULT NULL COMMENT '排序',
  `pid` bigint(20) NOT NULL COMMENT '上级菜单ID',  
  `update_time` datetime NULL DEFAULT NULL COMMENT '创建日期',
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
  `sort` bigint(20) NOT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

INSERT INTO `app_job` VALUES (1, '开发员', b'1', 1, '2020-01-01 09:14:05');
INSERT INTO `app_job` VALUES (2, '测试员', b'1', 2, '2020-01-01 09:14:05');



--
-- 表的结构 `app_dept`
--

CREATE TABLE `app_dict` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NULL COMMENT '字典类型名',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `app_dict_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dict_id` int(11) unsigned NOT NULL COMMENT '字典类型id',
  `pid` int(11) unsigned NULL COMMENT 'dict_item父节点',
  `code` int(11) unsigned NULL COMMENT '代码编码',
  `label` varchar(50) NULL COMMENT '字典条目名',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_dict_item_type` FOREIGN KEY (`dict_id`) REFERENCES `be_dict` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




