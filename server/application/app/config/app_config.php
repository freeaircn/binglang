<?php

defined('BASEPATH') OR exit('No direct script access allowed');


$config['db_name'] = 'binglang';

/*
| -------------------------------------------------------------------------
| Tables.
| -------------------------------------------------------------------------
| Database table names.
*/
$config['tables']['menu'] = 'app_menu';

/*
 | Users table column and Group table column you want to join WITH.
 |
 | Joins from users.id
 | Joins from groups.id
 */
$config['join']['users']  = 'user_id';
$config['join']['groups'] = 'group_id';


