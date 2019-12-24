CREATE DATABASE db_sshk CHARACTER SET utf8;

/*
 * 机组启停时间记录
 */
CREATE TABLE `g1_start_stop_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `is_running` tinyint(1) unsigned NOT NULL,
  `start_on` int(11) unsigned NOT NULL,
  `start_recoder` char(8) NOT NULL,
  `stop_on` int(11) unsigned NULL,
  `stop_recoder` char(8) NULL,
  `stop_cause` tinyint(1) unsigned NULL,
  `update_on` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `g1_start_stop_log` (`id`, `is_running`, `start_on`, `start_recoder`, `stop_on`, `stop_recoder`, `stop_cause`, `update_on`) VALUES
     (1,0,0,'张三',0,'张三',1,0);
     
CREATE TABLE `g2_start_stop_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `is_running` tinyint(1) unsigned NOT NULL,
  `start_on` int(11) unsigned NOT NULL,
  `start_recoder` char(8) NOT NULL,
  `stop_on` int(11) unsigned NULL,
  `stop_recoder` char(8) NULL,
  `stop_cause` tinyint(1) unsigned NULL,
  `update_on` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `g2_start_stop_log` (`id`, `is_running`, `start_on`, `start_recoder`, `stop_on`, `stop_recoder`, `stop_cause`, `update_on`) VALUES
     (1,0,0,'张三',0,'张三',1,0);

CREATE TABLE `g3_start_stop_log` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `is_running` tinyint(1) unsigned NOT NULL,
  `start_on` int(11) unsigned NOT NULL,
  `start_recoder` char(8) NOT NULL,
  `stop_on` int(11) unsigned NULL,
  `stop_recoder` char(8) NULL,
  `stop_cause` tinyint(1) unsigned NULL,
  `update_on` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `g3_start_stop_log` (`id`, `is_running`, `start_on`, `start_recoder`, `stop_on`, `stop_recoder`, `stop_cause`, `update_on`) VALUES
     (1,0,0,'张三',0,'张三',1,0);


/* 删除
 */
DROP TABLE IF EXISTS `g_start_stop_log`;
DROP TABLE IF EXISTS `g3_start_stop_log`;
DROP TABLE IF EXISTS `g2_start_stop_log`;
DROP TABLE IF EXISTS `g1_start_stop_log`;

DROP DATABASE IF EXISTS `db_device`;
DROP DATABASE IF EXISTS `db_sshk_device`;
DROP DATABASE IF EXISTS `db_sshk`;