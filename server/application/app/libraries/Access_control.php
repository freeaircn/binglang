<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-06-04 15:57:30
 * @LastEditors: freeair
 * @LastEditTime: 2020-06-04 20:44:03
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Access_control
{
    private $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
    }

    public function is_login()
    {
        // 1 判断用户登陆状态
        $phone = $this->CI->session->userdata('phone');
        return !empty($phone);
    }

    public function is_allowed()
    {
        // 2 获取http request的url和method参数APPROVED
        $arr    = explode('/', uri_string());
        $url_1  = isset($arr[0]) ? $arr[0] : '';
        $url_2  = isset($arr[1]) ? $arr[1] : '';
        $method = $this->CI->input->method();

        // 3 url中包含api关键字，验证用户权限
        if ($url_1 === 'api') {
            $key   = $url_2 . ':' . $method;
            $roles = $this->CI->session->userdata('roles');

            if (empty($roles)) {
                return false;
            } else {
                return in_array($key, $roles);
            }
        }
    }
}
