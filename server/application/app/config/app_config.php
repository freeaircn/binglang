<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 19:25:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-16 16:21:21
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
$config['tables']['dict'] = 'app_dict';
$config['tables']['dict_data'] = 'app_dict_data';
$config['tables']['role'] = 'app_role';
$config['tables']['roles_menus'] = 'app_roles_menus';
$config['tables']['user'] = 'app_user';
$config['tables']['user_attribute'] = 'app_user_attribute';

/*
 | table A column and table B column you want to join WITH.
 */
// $config['joins']['dict']  = 'id';
// $config['joins']['dict_data'] = 'dict_id';
