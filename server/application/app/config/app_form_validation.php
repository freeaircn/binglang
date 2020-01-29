<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-25 23:39:38
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-29 12:58:42
 */
defined('BASEPATH') or exit('No direct script access allowed');

$config = [
    'user_index_get'    => [
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
            'errors' => ['valid_limit' => '请求参数非法！'],
        ],
        [
            'field'  => 'individual',
            'label'  => 'individual',
            'rules'  => [
                ['valid_individual',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str) || $str === '') {
                            return true;
                        }
                        // e.g. Chinese name
                        if ((bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,6})$/u', $str)) {
                            return true;
                        }
                        // e.g. email
                        if ((bool) preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $str)) {
                            return true;
                        }
                        // e.g. phone
                        if ((bool) preg_match('/^[1][3,4,5,7,8][0-9]{0,9}$/', $str)) {
                            return true;
                        }
                        // e.g. identity_document_number
                        if ((bool) preg_match('/^([0-9]{1,17}[0-9x])$/', $str)) {
                            return true;
                        }
                        // e.g. sort
                        if ($str != 0 && ctype_digit((string) $str)) {
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
                        return $str === '' ? true : (bool) preg_match('/^[0-1]$/', $str);
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
                        return $str === '' ? true : (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str);
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
                        return $str === '' ? true : (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str);
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
                        return $str === '' ? true : (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,40})$/u', $str);
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
                        return $str === '' ? true : (bool) preg_match('/^([a-zA-z\x{4e00}-\x{9fa5}]{1,40})$/u', $str);
                    },
                ],
            ],
            'errors' => ['valid_professional_title' => '请求参数非法！'],
        ],
        [
            'field'  => 'uid',
            'label'  => 'uid',
            'rules'  => [
                ['valid_uid',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return true;
                        }
                        // e.g. number no zero
                        return ($str != 0 && ctype_digit((string) $str));
                    },
                ],
            ],
            'errors' => ['valid_uid' => '请求参数非法！'],
        ],
    ],
    'user_index_post'   => [
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
            'field'  => 'username',
            'label'  => 'username',
            'rules'  => [
                ['valid_username',
                    function ($str = null) {
                        // field is required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. Chinese name
                        return (bool) preg_match('/^([\x{4e00}-\x{9fa5}]{1,6})$/u', $str);
                    },
                ],
            ],
            'errors' => ['valid_username' => '提交数据非法！username'],
        ],
        [
            'field'  => 'sex',
            'label'  => 'sex',
            'rules'  => [
                ['valid_sex',
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
            'errors' => ['valid_sex' => '提交数据非法！sex'],
        ],
        [
            'field'  => 'phone',
            'label'  => 'phone',
            'rules'  => [
                ['valid_phone',
                    function ($str = null) {
                        // field is required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. phone
                        return (bool) preg_match('/^[1][3,4,5,7,8][0-9]{9}$/', $str);
                    },
                ],
            ],
            'errors' => ['valid_phone' => '提交数据非法！phone'],
        ],
        [
            'field'  => 'email',
            'label'  => 'email',
            'rules'  => [
                ['valid_email',
                    function ($str = null) {
                        // field is required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. email
                        return (bool) preg_match('/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/', $str);
                    },
                ],
            ],
            'errors' => ['valid_email' => '提交数据非法！email'],
        ],
        [
            'field'  => 'password',
            'label'  => 'password',
            'rules'  => [
                ['valid_password',
                    function ($str = null) {
                        // field is required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. password
                        return ($str === '') ? true : (bool) preg_match('/^[a-zA-Z0-9]{1,16}$/', $str);
                    },
                ],
            ],
            'errors' => ['valid_password' => '提交数据非法！password'],
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
            'field'  => 'roles[]',
            'label'  => 'roles',
            'rules'  => [
                ['valid_roles',
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
            'errors' => ['valid_roles' => '请求参数非法！roles'],
        ],
        [
            'field'  => 'identity_document_number',
            'label'  => 'identity_document_number',
            'rules'  => [
                ['valid_identity_document_number',
                    function ($str = null) {
                        // field is required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. identity_document_number
                        return ($str === '') ? true : (bool) preg_match('/^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/', $str);
                    },
                ],
            ],
            'errors' => ['valid_identity_document_number' => '提交数据非法！identity_document_number'],
        ],
        [
            'field'  => 'dept_id',
            'label'  => 'dept_id',
            'rules'  => [
                ['valid_dept_id',
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
            'errors' => ['valid_dept_id' => '提交数据非法！dept_id'],
        ],
        [
            'field'  => 'job_id',
            'label'  => 'job_id',
            'rules'  => [
                ['valid_job_id',
                    function ($str = null) {
                        // field is required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. number no zero
                        return ($str === '') ? true : ($str != 0 && ctype_digit((string) $str));
                    },
                ],
            ],
            'errors' => ['valid_job_id' => '提交数据非法！job_id'],
        ],
        [
            'field'  => 'user_attribute[]',
            'label'  => 'user_attribute',
            'rules'  => [
                ['valid_user_attribute',
                    function ($str = null) {
                        // field is not required
                        if (!isset($str)) {
                            return false;
                        }
                        // e.g. number no zero
                        return ($str === '') ? true : ($str != 0 && ctype_digit((string) $str));
                    },
                ],
            ],
            'errors' => ['valid_user_attribute' => '提交数据非法！user_attribute'],
        ],
    ],
    'user_index_delete' => [
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
    ],
];

$config['error_prefix'] = '';
$config['error_suffix'] = '';
