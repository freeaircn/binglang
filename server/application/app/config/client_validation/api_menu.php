<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-25 23:39:38
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 22:35:15
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
        'field'  => 'title',
        'label'  => 'title',
        'rules'  => [
            ['valid_title',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. Chinese
                    return $str === '' ? true : (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,40})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_title' => '请求参数非法！'],
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
    [
        'field'  => 'req',
        'label'  => 'req',
        'rules'  => [
            ['valid_req',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. '', or number no zero
                    return ($str === 'id_title_pid');
                },
            ],
        ],
        'errors' => ['valid_req' => '提交数据非法！'],
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
        'field'  => 'type',
        'label'  => 'type',
        'rules'  => [
            ['valid_type',
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
        'errors' => ['valid_type' => '提交数据非法！type'],
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
                    // e.g. English
                    return (bool) preg_match('/^([a-zA-z]{1,60})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_name' => '提交数据非法！name'],
    ],
    [
        'field'  => 'path',
        'label'  => 'path',
        'rules'  => [
            ['valid_path',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. English - _
                    return (bool) preg_match('/^([a-zA-z_-]{1,60})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_path' => '提交数据非法！path'],
    ],
    [
        'field'  => 'title',
        'label'  => 'title',
        'rules'  => [
            ['valid_title',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. Chinese
                    return (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,30})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_title' => '请求参数非法！title'],
    ],
    [
        'field'  => 'icon',
        'label'  => 'icon',
        'rules'  => [
            ['valid_icon',
                function ($str = null) {
                    // field is not required
                    if (!isset($str)) {
                        return true;
                    }
                    // e.g. English
                    return (bool) preg_match('/^([a-z]{1,60})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_icon' => '请求参数非法！icon'],
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
        'field'  => 'pid',
        'label'  => 'pid',
        'rules'  => [
            ['valid_pid',
                function ($str = null) {
                    // field is required
                    if (!isset($str)) {
                        return false;
                    }
                    // e.g. number
                    return (1 && ctype_digit((string) $str));
                },
            ],
        ],
        'errors' => ['valid_pid' => '提交数据非法！pid'],
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
                    // e.g. English op Chinese
                    return (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str);
                },
            ],
        ],
        'errors' => ['valid_label' => '请求参数非法！label'],
    ],
    [
        'field'  => 'hidden',
        'label'  => 'hidden',
        'rules'  => [
            ['valid_hidden',
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
        'errors' => ['valid_hidden' => '提交数据非法！hidden'],
    ],
    [
        'field'  => 'alwaysShow',
        'label'  => 'alwaysShow',
        'rules'  => [
            ['valid_alwaysShow',
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
        'errors' => ['valid_alwaysShow' => '提交数据非法！alwaysShow'],
    ],
    [
        'field'  => 'noCache',
        'label'  => 'noCache',
        'rules'  => [
            ['valid_noCache',
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
        'errors' => ['valid_noCache' => '提交数据非法！noCache'],
    ],
    [
        'field'  => 'breadcrumb',
        'label'  => 'breadcrumb',
        'rules'  => [
            ['valid_breadcrumb',
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
        'errors' => ['valid_breadcrumb' => '提交数据非法！breadcrumb'],
    ],
];
