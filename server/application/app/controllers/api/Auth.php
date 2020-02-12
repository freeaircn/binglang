<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-12 09:50:39
 */
defined('BASEPATH') or exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;

class Auth extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('user_model');
        // $this->load->library('common_tools');
    }

    public function login_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        // $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_auth', 'index_post');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        $data['phone']    = $client['phone'];
        $data['password'] = $client['password'];

        // insert user table
        // $uid = $this->user_model->create_user($data, $client['roles'], $client['user_attribute']);
        // if ($uid === false) {
        //     $res['code'] = App_Code::CREATE_USER_FAILED;
        //     $res['msg']  = App_Msg::CREATE_USER_FAILED;
        //     SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

        //     $this->response($res, 200);
        // }
        if ($data['password'] !== '111') {
            $res['code'] = 300;
            $res['msg']  = "账号或密码错误！";
            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['data'] = ['token' => 'token', 'user' => 'user', 'roles' => ['roles']];

        $this->response($res, 200);
    }
}
