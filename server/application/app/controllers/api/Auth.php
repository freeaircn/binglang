<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-11 17:50:14
 */
defined('BASEPATH') or exit('No direct script access allowed');

use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Auth extends CI_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('user_model');
        // $this->load->library('common_tools');
    }

    public function login()
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

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 201);
    }
}
