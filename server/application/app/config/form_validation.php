<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-25 23:39:38
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-26 00:46:51
 */
defined('BASEPATH') or exit('No direct script access allowed');

$config = [
    'user_index_get' => [
        [
            'field'  => 'limit',
            'label'  => 'limit',
            'rules'  => [
                ['valid_limit',
                    function ($str) {
                        if ($str != '') {
                            return false;
                        } else {
                            return true;
                        }
                    },
                ],
            ],
            'errors' => ['valid_limit' => '请求参数非法！'],
        ],
    ],
];

$config['error_prefix'] = '';
$config['error_suffix'] = '';
