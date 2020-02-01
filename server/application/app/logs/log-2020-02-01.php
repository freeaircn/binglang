<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-02-01 15:43:13 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 15:43:13 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\www\binglang\server\application\app\libraries\Common_tools.php:66) D:\www\binglang\server\system\core\Common.php 570
ERROR - 2020-02-01 15:43:39 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 15:45:41 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 15:45:41 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\www\binglang\server\application\app\libraries\Common_tools.php:65) D:\www\binglang\server\system\core\Common.php 570
ERROR - 2020-02-01 15:48:31 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 15:48:31 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\www\binglang\server\application\app\libraries\Common_tools.php:65) D:\www\binglang\server\system\core\Common.php 570
ERROR - 2020-02-01 15:56:43 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 15:56:43 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\www\binglang\server\application\app\libraries\Common_tools.php:65) D:\www\binglang\server\system\core\Common.php 570
ERROR - 2020-02-01 15:59:29 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:00:20 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:00:35 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:00:48 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:01:26 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:01:29 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:01:43 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 16:05:36 --> Severity: Warning --> Illegal offset type in isset or empty D:\www\binglang\server\application\app\libraries\App_form_validation.php 395
ERROR - 2020-02-01 22:12:58 --> Severity: Notice --> Undefined property: Dict_data::$dict_model D:\www\binglang\server\application\app\controllers\api\Dict_data.php 37
ERROR - 2020-02-01 22:12:58 --> Severity: error --> Exception: Call to a member function read() on null D:\www\binglang\server\application\app\controllers\api\Dict_data.php 37
ERROR - 2020-02-01 22:14:07 --> Severity: error --> Exception: Call to undefined method Dict_data_model::_get_sql_where() D:\www\binglang\server\application\app\models\Dict_data_model.php 49
ERROR - 2020-02-01 22:14:55 --> Severity: error --> Exception: Class 'App_Code' not found D:\www\binglang\server\application\app\controllers\api\Dict_data.php 42
ERROR - 2020-02-01 22:32:38 --> Query error: Column 'id' in where clause is ambiguous - Invalid query: SELECT `a`.*, `b`.`label` as `dict_label`
FROM `app_dict_data` `a`
INNER JOIN `app_dict` `b` ON `a`.`dict_id` = `b`.`id`
WHERE `id` IN('3')
ORDER BY `sort` ASC, `id` ASC
 LIMIT 10
ERROR - 2020-02-01 22:36:27 --> Severity: error --> Exception: Call to undefined method Dict_data_model::read_by_id() D:\www\binglang\server\application\app\controllers\api\Dict_data.php 51
