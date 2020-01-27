<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-01-27 10:10:49 --> Severity: 4096 --> Object of class mysqli could not be converted to string D:\www\binglang\server\application\app\models\User_model.php 636
ERROR - 2020-01-27 10:10:49 --> Query error: Unknown column 'pid' in 'where clause' - Invalid query: SELECT `id`
FROM `app_user`
WHERE `pid` IN('')
ERROR - 2020-01-27 10:10:49 --> Severity: error --> Exception: Call to a member function result_array() on boolean D:\www\binglang\server\application\app\models\User_model.php 641
ERROR - 2020-01-27 10:13:00 --> Severity: 4096 --> Object of class mysqli could not be converted to string D:\www\binglang\server\application\app\models\User_model.php 640
ERROR - 2020-01-27 10:13:00 --> Query error: Unknown column 'pid' in 'where clause' - Invalid query: SELECT `id`
FROM `app_user`
WHERE `pid` IN('')
ERROR - 2020-01-27 10:13:00 --> Severity: error --> Exception: Call to a member function result_array() on boolean D:\www\binglang\server\application\app\models\User_model.php 645
ERROR - 2020-01-27 10:16:56 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\application\app\models\User_model.php 640
ERROR - 2020-01-27 10:16:56 --> Query error: Unknown column 'pid' in 'where clause' - Invalid query: SELECT `id`
FROM `app_user`
WHERE `pid` IN('Array')
ERROR - 2020-01-27 10:16:56 --> Severity: error --> Exception: Call to a member function result_array() on boolean D:\www\binglang\server\application\app\models\User_model.php 645
ERROR - 2020-01-27 10:21:35 --> Query error: Unknown column 'pid' in 'where clause' - Invalid query: SELECT `id`
FROM `app_user`
WHERE `pid` IN('2')
ERROR - 2020-01-27 10:21:35 --> Severity: error --> Exception: Call to a member function result_array() on boolean D:\www\binglang\server\application\app\models\User_model.php 646
ERROR - 2020-01-27 10:56:41 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ') )' at line 3 - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`
FROM `app_user`
WHERE (`dept_id` IN ('2','3',) )
ERROR - 2020-01-27 10:56:41 --> Severity: error --> Exception: Call to a member function num_rows() on boolean D:\www\binglang\server\application\app\models\User_model.php 106
ERROR - 2020-01-27 11:13:27 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\application\app\models\User_model.php 108
ERROR - 2020-01-27 11:13:27 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\application\app\models\User_model.php 108
ERROR - 2020-01-27 17:44:17 --> Query error: Unknown column 'username' in 'field list' - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`, `id`
FROM `app_dict_data`
WHERE `name` LIKE 'user!_attr!_professional!_title%' ESCAPE '!'
AND  `label` LIKE '%开发%' ESCAPE '!'
GROUP BY `id`
ERROR - 2020-01-27 17:44:17 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`, `id`
FROM `app_dict_data`, `app_user`
WHERE `name` LIKE 'user!_attr!_professional!_title%' ESCAPE '!'
AND  `label` LIKE '%开发%' ESCAPE '!'
GROUP BY `id`
ERROR - 2020-01-27 17:44:17 --> Severity: error --> Exception: Call to a member function num_rows() on boolean D:\www\binglang\server\application\app\models\User_model.php 60
ERROR - 2020-01-27 18:31:06 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:06 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:06 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:06 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:06 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:06 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:06 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`
FROM `app_user`
WHERE `id` IN(Array, Array, Array, Array, Array, Array)
ORDER BY `sort` ASC
 LIMIT 5
ERROR - 2020-01-27 18:31:06 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\www\binglang\server\system\core\Exceptions.php:271) D:\www\binglang\server\system\core\Common.php 570
ERROR - 2020-01-27 18:31:53 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:53 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:53 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:53 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:53 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:53 --> Severity: Notice --> Array to string conversion D:\www\binglang\server\system\database\DB_query_builder.php 837
ERROR - 2020-01-27 18:31:53 --> Query error: Unknown column 'Array' in 'where clause' - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`
FROM `app_user`
WHERE `id` IN(Array, Array, Array, Array, Array, Array)
ORDER BY `sort` ASC
 LIMIT 5
ERROR - 2020-01-27 18:31:53 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\www\binglang\server\system\core\Exceptions.php:271) D:\www\binglang\server\system\core\Common.php 570
ERROR - 2020-01-27 20:37:06 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
ORDER BY `sort` ASC
 LIMIT 5' at line 3 - Invalid query: SELECT `id`, `sort`, `username`, `sex`, `phone`, `email`, `identity_document_number`, `dept_id`, `job_id`, `enabled`, `last_login`, `ip_address`, `update_time`
FROM `app_user`
WHERE `id` IN()
ORDER BY `sort` ASC
 LIMIT 5
ERROR - 2020-01-27 21:05:53 --> Severity: Warning --> preg_match(): No ending delimiter '^' found D:\www\binglang\server\application\app\config\form_validation.php 76
ERROR - 2020-01-27 21:07:00 --> Severity: Warning --> preg_match(): Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 10 D:\www\binglang\server\application\app\config\form_validation.php 76
ERROR - 2020-01-27 21:08:22 --> Severity: Warning --> preg_match(): Compilation failed: PCRE does not support \L, \l, \N{name}, \U, or \u at offset 13 D:\www\binglang\server\application\app\config\form_validation.php 76
ERROR - 2020-01-27 21:11:51 --> Severity: Warning --> preg_match(): Compilation failed: character value in \x{} or \o{} is too large at offset 16 D:\www\binglang\server\application\app\config\form_validation.php 76
ERROR - 2020-01-27 21:12:27 --> Severity: Warning --> preg_match(): Compilation failed: character value in \x{} or \o{} is too large at offset 9 D:\www\binglang\server\application\app\config\form_validation.php 76
ERROR - 2020-01-27 21:12:30 --> Severity: Warning --> preg_match(): Compilation failed: character value in \x{} or \o{} is too large at offset 9 D:\www\binglang\server\application\app\config\form_validation.php 76
ERROR - 2020-01-27 21:59:24 --> 404 Page Not Found: Resource/dist
