<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-01-26 00:18:24 --> Could not find the language line "form_validation_valid_limit"
ERROR - 2020-01-26 00:21:10 --> Could not find the language line "form_validation_valid_limit"
ERROR - 2020-01-26 00:23:02 --> Could not find the language line "form_validation_valid_limit"
ERROR - 2020-01-26 09:29:05 --> Severity: Notice --> Undefined variable: valid D:\www\binglang\server\application\app\controllers\api\User.php 32
ERROR - 2020-01-26 09:30:12 --> Severity: Notice --> Undefined index: limit D:\www\binglang\server\application\app\controllers\api\User.php 32
ERROR - 2020-01-26 10:33:39 --> Severity: Notice --> Undefined index: limit D:\www\binglang\server\application\app\controllers\api\User.php 32
ERROR - 2020-01-26 10:36:59 --> Severity: Notice --> Undefined index: a D:\www\binglang\server\application\app\controllers\api\User.php 32
ERROR - 2020-01-26 10:45:02 --> Severity: Notice --> Undefined variable: a D:\www\binglang\server\application\app\controllers\api\User.php 32
ERROR - 2020-01-26 22:38:12 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '`sort` LIKE '%1%' OR `WHERE` `username` LIKE '%1%' OR `WHERE` `phone` LIKE '%1%'' at line 3 - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`
FROM `app_user`
WHERE `WHERE` `sort` LIKE '%1%' OR `WHERE` `username` LIKE '%1%' OR `WHERE` `phone` LIKE '%1%' OR `WHERE` `identity_document_number` LIKE '%1%' OR `WHERE` `email` LIKE '%1%'
ORDER BY `sort` ASC
 LIMIT 5
ERROR - 2020-01-26 22:41:16 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '`sort` LIKE '%1%' OR `username` LIKE '%1%' OR `phone` LIKE '%1%' OR `identity_do' at line 3 - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`
FROM `app_user`
WHERE `WHERE` `sort` LIKE '%1%' OR `username` LIKE '%1%' OR `phone` LIKE '%1%' OR `identity_document_number` LIKE '%1%' OR `email` LIKE '%1%'
ORDER BY `sort` ASC
 LIMIT 5
