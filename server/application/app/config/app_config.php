<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 19:25:12
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-26 00:22:20
 */

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Database
| -------------------------------------------------------------------------
 */
$config['db_name']        = 'binglang';
$config['tables']['menu'] = 'app_menu';

$config['tables']['dict']      = 'app_dict';
$config['tables']['dict_data'] = 'app_dict_data';

$config['tables']['role']        = 'app_role';
$config['tables']['roles_menus'] = 'app_roles_menus';

$config['tables']['user']               = 'app_user';
$config['tables']['dept']               = 'app_dept';
$config['tables']['job']                = 'app_job';
$config['tables']['politic']            = 'app_politic';
$config['tables']['professional_title'] = 'app_professional_title';

$config['tables']['users_roles'] = 'app_users_roles';
$config['tables']['user_avatar'] = 'app_user_avatar';
// $config['tables']['user_attribute'] = 'app_user_attribute';

$config['tables']['login_attempts']    = 'app_login_attempts';
$config['tables']['verification_code'] = 'app_verification_code';

/*
| -------------------------------------------------------------------------
| Password hash
| -------------------------------------------------------------------------
 */
$config['MAX_PASSWORD_SIZE_BYTES'] = 254;
$config['hash_method']             = 'argon2'; // bcrypt or argon2
$config['bcrypt_default_cost']     = 12; // Set cost according to your server benchmark - but no lower than 10 (default PHP value)
$config['argon2_default_params']   = [
    'memory_cost' => 1 << 14, // 16MB
    'time_cost'   => 4,
    'threads'     => PASSWORD_ARGON2_DEFAULT_THREADS,
];

/*
| -------------------------------------------------------------------------
| Auth
| -------------------------------------------------------------------------
 */
$config['maximum_login_attempts'] = 5; // The maximum number of failed login attempts.
$config['lockout_time']           = 600; // The number of seconds to lockout an account due to exceeded attempts
$config['session_hash']           = '6583d6c4f205998ecacc9f51b68a2a2e44ea0006';

/*
| -------------------------------------------------------------------------
| Email options.
| -------------------------------------------------------------------------
| email_config:
|       'file' = Use the default CI config or use from a config file
|       array  = Manually set your email config settings
 */
$config['mail_title'] = "【请勿回复此邮件】"; // Site Title, example.com
$config['sys_mail']   = "officehouqiao@163.com"; // Admin Email, admin@example.com

$config['use_ci_email'] = true; // Send Email using the builtin CI email class, if false it will return the code and the identity
$config['email_config'] = [
    'mailtype'     => 'html',
    'charset'      => 'utf-8',
    'protocol'     => 'smtp',
    'smtp_host'    => 'ssl://smtp.163.com',
    'smtp_user'    => 'officehouqiao@163.com',
    'smtp_port'    => 465,
    'smtp_pass'    => 'HQ1101',
    'smtp_timeout' => 4,
    'validate'     => false,
    'priority'     => 3,
    'crlf'         => '\r\n',
    'newline'      => '\r\n',
];

/*
| -------------------------------------------------------------------------
| Email templates.
| -------------------------------------------------------------------------
| Folder where email templates are stored.
| Default: auth/
 */
$config['email_templates'] = 'auth/email/';

/*
| -------------------------------------------------------------------------
| verification code Email Template
| -------------------------------------------------------------------------
| Default: activate.tpl.php
 */
$config['email_verification_code'] = 'verification_code.tpl.php';

$config['verification_code_expire_time'] = 300; // The number of seconds after which a verification code request will expire. If set to 0, will not expire.

$config['avatar_default_file'] = 'avatar_default_male.jpg';
$config['avatar_default_path'] = 'resource/avatar/default/';
$config['avatar_active_path']  = 'resource/avatar/active/';
$config['avatar_upload_path']  = 'resource/avatar/temp/';

//
$config['user_prop_dict_name'] = 'user_prop_mask';
// 数据词典Mask - 用户属性过滤，mask二进制位定义 - ... bit7 bit6 bit5 bit4 bit3 bit2 bit1 bit0
// bit0 - 是否存储session和store - 1:是 0: 否
// bit1 - 用户可修改 - 1：是 0：否 - 用户可修改项集合 小于 存储集合
$config['user_prop_cache_mask'] = 1;
$config['user_prop_edit_mask']  = 2;
