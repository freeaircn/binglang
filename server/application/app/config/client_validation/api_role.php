<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-25 23:39:38
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 21:26:55
 */
defined('BASEPATH') or exit('No direct script access allowed');

$config['index_delete'] = [
    [
        'field'  => 'id',
        'label'  => 'id',
        'rules'  => [
            ['valid_id',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. number no zero
                    return ($str != 0 && ctype_digit((string) $str));
                },
            ],
        ],
        'errors' => ['valid_id' => '提交数据非法！id'],
    ],
];

$config['index_get'] = [
    [
        'field'  => 'limit',
        'label'  => 'limit',
        'rules'  => [
            ['valid_limit',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. 5_10
                    return $str === '' ? true : (bool) preg_match('/^\d{1,2}_\d{1,3}$/', $str);
                },
            ],
        ],
        'errors' => ['valid_limit' => '请求参数非法！limit'],
    ],
    [
        'field'  => 'label',
        'label'  => 'label',
        'rules'  => [
            ['valid_label',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. Chinese
                    return $str === '' ? true : (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,6})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_label' => '请求参数非法！'],
    ],
    [
        'field'  => 'id',
        'label'  => 'id',
        'rules'  => [
            ['valid_id',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. '', or number no zero
                    return ($str != 0 && ctype_digit((string) $str));
                },
            ],
        ],
        'errors' => ['valid_id' => '提交数据非法！'],
    ],
];

$config['index_post'] = [
    [
        'field'  => 'id',
        'label'  => 'id',
        'rules'  => [
            ['valid_id',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. '', or number no zero
                    return $str === '' ? true : ($str != 0 && ctype_digit((string) $str));
                },
            ],
        ],
        'errors' => ['valid_id' => '提交数据非法！id'],
    ],
    [
        'field'  => 'sort',
        'label'  => 'sort',
        'rules'  => [
            ['valid_sort',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. number no zero
                    return ($str != 0 && ctype_digit((string) $str));
                },
            ],
        ],
        'errors' => ['valid_sort' => '提交数据非法！sort'],
    ],
    [
        'field'  => 'label',
        'label'  => 'label',
        'rules'  => [
            ['valid_label',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. Chinese
                    return (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,15})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_label' => '请求参数非法！label'],
    ],
    [
        'field'  => 'name',
        'label'  => 'name',
        'rules'  => [
            ['valid_name',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. 0 or 1
                    return (bool) preg_match('/^[a-z_]{1,63}$/', $str);
                },
            ],
        ],
        'errors' => ['valid_name' => '请求参数非法！name'],
    ],
    [
        'field'  => 'enabled',
        'label'  => 'enabled',
        'rules'  => [
            ['valid_enabled',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. 0 or 1
                    return (bool) preg_match('/^[0-1]$/', $str);
                },
            ],
        ],
        'errors' => ['valid_enabled' => '提交数据非法！enabled'],
    ],
    [
        'field'  => 'remark',
        'label'  => 'remark',
        'rules'  => [
            ['valid_remark',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. Chinese
                    return $str === '' ? true : (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,127})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_remark' => '请求参数非法！label'],
    ],
];
