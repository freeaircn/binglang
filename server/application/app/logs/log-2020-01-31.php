<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-01-31 21:16:39 --> Severity: Notice --> Undefined property: Dict::$user_model D:\www\binglang\server\application\app\controllers\api\Dict.php 37
ERROR - 2020-01-31 21:16:39 --> Severity: error --> Exception: Call to a member function read() on null D:\www\binglang\server\application\app\controllers\api\Dict.php 37
ERROR - 2020-01-31 21:17:49 --> Severity: error --> Exception: Class 'App_Code' not found D:\www\binglang\server\application\app\controllers\api\Dict.php 42
ERROR - 2020-01-31 21:19:26 --> Severity: Notice --> Undefined variable: temp_uid D:\www\binglang\server\application\app\models\Dict_model.php 72
ERROR - 2020-01-31 21:19:26 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
ORDER BY `sort` ASC, `id` ASC
 LIMIT 5' at line 3 - Invalid query: SELECT `id`, `sort`, `label`, `name`, `enabled`, `update_time`
FROM `app_dict`
WHERE `id` IN()
ORDER BY `sort` ASC, `id` ASC
 LIMIT 5
