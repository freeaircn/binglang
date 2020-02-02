<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-02-02 15:27:02 --> Severity: Notice --> Undefined variable: empty D:\www\binglang\server\application\app\models\Dict_model.php 143
ERROR - 2020-02-02 15:27:02 --> Severity: error --> Exception: Function name must be a string D:\www\binglang\server\application\app\models\Dict_model.php 143
ERROR - 2020-02-02 20:02:31 --> Severity: Notice --> Undefined property: Dict_data::$dict_model D:\www\binglang\server\application\app\controllers\api\Dict_data.php 89
ERROR - 2020-02-02 20:02:31 --> Severity: error --> Exception: Call to a member function create() on null D:\www\binglang\server\application\app\controllers\api\Dict_data.php 89
ERROR - 2020-02-02 20:57:59 --> Query error: Cannot delete or update a parent row: a foreign key constraint fails (`binglang`.`app_user_attribute`, CONSTRAINT `fk_user_attribute_ref_dict_data_id` FOREIGN KEY (`dict_data_id`) REFERENCES `app_dict_data` (`id`)) - Invalid query: DELETE FROM `app_dict_data`
WHERE `id` = '9'
