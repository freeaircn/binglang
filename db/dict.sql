CREATE DATABASE be_app CHARACTER SET utf8;

CREATE TABLE `be_dict` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NULL COMMENT '字典类型名',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `be_dict_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `dict_id` int(11) unsigned NOT NULL COMMENT '字典类型id',
  `pid` int(11) unsigned NULL COMMENT 'dict_item父节点',
  `code` int(11) unsigned NULL COMMENT '代码编码',
  `label` varchar(50) NULL COMMENT '字典条目名',
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_dict_item_type` FOREIGN KEY (`dict_id`) REFERENCES `be_dict` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
