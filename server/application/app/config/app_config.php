<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 19:25:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-19 09:04:50
 */

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Database
| -------------------------------------------------------------------------
 */
$config['db_name']                  = 'binglang';
$config['tables']['menu']           = 'app_menu';
$config['tables']['dept']           = 'app_dept';
$config['tables']['job']            = 'app_job';
$config['tables']['dict']           = 'app_dict';
$config['tables']['dict_data']      = 'app_dict_data';
$config['tables']['role']           = 'app_role';
$config['tables']['roles_menus']    = 'app_roles_menus';
$config['tables']['user']           = 'app_user';
$config['tables']['users_roles']    = 'app_users_roles';
$config['tables']['user_attribute'] = 'app_user_attribute';

/*
| -------------------------------------------------------------------------
| Password hash
| -------------------------------------------------------------------------
 */
$config['MAX_PASSWORD_SIZE_BYTES'] = 4096;
$config['hash_method']             = 'argon2'; // bcrypt or argon2
$config['bcrypt_default_cost']     = 12; // Set cost according to your server benchmark - but no lower than 10 (default PHP value)
$config['argon2_default_params']   = [
    'memory_cost' => 1 << 14, // 16MB
    'time_cost'   => 4,
    'threads'     => 2,
];
