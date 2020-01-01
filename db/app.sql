CREATE DATABASE binglang CHARACTER SET utf8;

DROP TABLE IF EXISTS `app_menu`;
CREATE TABLE `app_menu`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单名称',
  `type` int(11) NULL DEFAULT NULL COMMENT '顶级-0，次级菜单-1，按钮-2',
  `pid` bigint(20) NOT NULL COMMENT '上级菜单ID',
  `sort` bigint(20) NULL DEFAULT NULL COMMENT '排序',
  `permission` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `component` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '组件',
  `component_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '-',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '链接地址',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `cache` bit(1) NULL DEFAULT b'0',
  `hidden` bit(1) NULL DEFAULT b'0',
  `outlink` bit(1) NULL DEFAULT NULL COMMENT '是否外链',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `FKqcf9gem97gqa5qjm4d3elcqt5`(`pid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

INSERT INTO `app_menu` (`id`, `name`, `type`, `pid`, `sort`, `permission`, `component`, `component_name`, `path`, `icon`, `cache`, `hidden`, `outlink`, `create_time`)
VALUES (1, '系统管理', 0, 0, 1, NULL, NULL, NULL, 'admin', 'system', b'0', b'0', b'0', '2020-01-01 00:00:00');
INSERT INTO `app_menu` VALUES (2, '菜单管理', 1, 1, 10, NULL, 'admin/menu/index', 'Menu', 'menu', 'system', b'0', b'0', b'0', '2020-01-01 00:00:00');



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




