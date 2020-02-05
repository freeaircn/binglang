<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-02-05 20:15:46 --> Query error: Cannot delete or update a parent row: a foreign key constraint fails (`binglang`.`app_user`, CONSTRAINT `fk_user_ref_dept_id` FOREIGN KEY (`dept_id`) REFERENCES `app_dept` (`id`)) - Invalid query: DELETE FROM `app_dept`
WHERE `id` IN('2', '8')
