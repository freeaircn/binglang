<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 19:25:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 17:37:42
 */

defined('BASEPATH') OR exit('No direct script access allowed');


$config['db_name'] = 'binglang';

/*
| -------------------------------------------------------------------------
| Tables.
| -------------------------------------------------------------------------
| Database table names.
*/
$config['tables']['menu'] = 'app_menu';
$config['tables']['dept'] = 'app_dept';
$config['tables']['job'] = 'app_job';

/*
 | Users table column and Group table column you want to join WITH.
 |
 | Joins from users.id
 | Joins from groups.id
 */
$config['join']['users']  = 'user_id';
$config['join']['groups'] = 'group_id';


