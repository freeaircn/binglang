<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-25 23:39:38
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-26 21:57:59
 */
defined('BASEPATH') or exit('No direct script access allowed');

$config = [
    'user_index_get' => [
        [
            'field'  => 'limit',
            'label'  => 'limit',
            'rules'  => [
                ['valid_limit',
                    function ($str = null) {
                        // not include limit field, for non-required item
                        if (!isset($str)) {
                            return true;
                        }
                        // include limit field, e.g. match 5_10
                        return (bool) preg_match('/^\d{1,2}_\d{1,3}$/', $str);
                    },
                ],
            ],
            'errors' => ['valid_limit' => '请求参数非法！'],
        ],
        [
            'field'  => 'individual',
            'label'  => 'individual',
            'rules'  => [
                ['valid_individual',
                    function ($str = null) {
                        // not include limit field, for non-required item
                        return true;
                        // if (!isset($str)) {
                        //     return true;
                        // }
                        // // include limit field, e.g. match 5_10
                        // return (bool) preg_match('/^\d{1,2}_\d{1,3}$/', $str);
                    },
                ],
            ],
            'errors' => ['valid_individual' => '请求参数非法！'],
        ],
    ],
];

$config['error_prefix'] = '';
$config['error_suffix'] = '';
