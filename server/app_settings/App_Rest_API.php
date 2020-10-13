<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-06-05 14:51:11
 * @LastEditors: freeair
 * @LastEditTime: 2020-09-06 20:45:36
 */
namespace App_Settings;

use chriskacerguis\RestServer\RestController;

class APP_Rest_API extends RestController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

        // 1 判断用户登陆状态
        $phone = $this->session->userdata('phone');
        if (empty($phone)) {
            $res['code'] = App_Code::USER_NOT_LOGIN;
            $res['msg']  = App_Msg::USER_NOT_LOGIN;
            $this->response($res, 200);
        }

        // 2 超级用户？
        if ($phone == '13812345678') {
            // print_r($this->session->userdata('acl'));
            return;
        }

        // 2 提取http request的url和method
        $arr    = explode('/', uri_string());
        $url_1  = isset($arr[0]) ? $arr[0] : '';
        $url_2  = isset($arr[1]) ? $arr[1] : '';
        $method = $this->input->method();

        // print_r($method);
        // var_dump($url_2);
        // 3 url中包含api关键字，验证用户权限
        if ($url_1 === 'api') {
            $key   = $url_2 . ':' . $method;
            $roles = $this->session->userdata('acl');

            if (empty($roles)) {
                $res['code'] = App_Code::USER_NOT_APPROVED;
                $res['msg']  = App_Msg::USER_NOT_APPROVED;
                $this->response($res, 200);
            }
            if (in_array($key, $roles) === false) {
                $res['code'] = App_Code::USER_NOT_APPROVED;
                $res['msg']  = App_Msg::USER_NOT_APPROVED;
                $this->response($res, 200);
            }
        }
    }
}
