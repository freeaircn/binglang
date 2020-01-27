<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-25 23:39:38
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-27 21:45:44
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
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. 5_10
                        return $str !== '' ? (bool) preg_match('/^\d{1,2}_\d{1,3}$/', $str) : true;
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
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        if ($str === '') {
                            return true;
                        }
                        // e.g. sort or phone
                        if ((bool) preg_match('/^([0-9]{1,11})$/', $str)) {
                            return true;
                        }
                        // e.g. Chinese name
                        if ((bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,6})$/u', $str)) {
                            return true;
                        }
                        // e.g. identity_document_number
                        if ((bool) preg_match('/^([0-9]{1,17}[0-9x])$/', $str)) {
                            return true;
                        }
                        // e.g. email
                        if ((bool) preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $str)) {
                            return true;
                        }
                        return false;
                    },
                ],
            ],
            'errors' => ['valid_individual' => '请求参数非法！'],
        ],
        [
            'field'  => 'sex',
            'label'  => 'sex',
            'rules'  => [
                ['valid_sex',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. 0 or 1
                        return $str !== '' ? (bool) preg_match('/^[0-1]$/', $str) : true;
                    },
                ],
            ],
            'errors' => ['valid_sex' => '请求参数非法！'],
        ],
        [
            'field'  => 'dept',
            'label'  => 'dept',
            'rules'  => [
                ['valid_dept',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. Chinese, English
                        return $str !== '' ? (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str) : true;
                    },
                ],
            ],
            'errors' => ['valid_dept' => '请求参数非法！'],
        ],
        [
            'field'  => 'job',
            'label'  => 'job',
            'rules'  => [
                ['valid_job',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. Chinese, English
                        return $str !== '' ? (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str) : true;
                    },
                ],
            ],
            'errors' => ['valid_job' => '请求参数非法！'],
        ],
        [
            'field'  => 'politic',
            'label'  => 'politic',
            'rules'  => [
                ['valid_politic',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. Chinese
                        return $str !== '' ? (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,40})$/u', $str) : true;
                    },
                ],
            ],
            'errors' => ['valid_politic' => '请求参数非法！'],
        ],
        [
            'field'  => 'professional_title',
            'label'  => 'professional_title',
            'rules'  => [
                ['valid_professional_title',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. Chinese, English
                        return $str !== '' ? (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str) : true;
                    },
                ],
            ],
            'errors' => ['valid_professional_title' => '请求参数非法！'],
        ],
    ],
];

$config['error_prefix'] = '';
$config['error_suffix'] = '';
