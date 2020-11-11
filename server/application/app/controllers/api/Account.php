<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-11 22:17:05
 */
defined('BASEPATH') or exit('No direct script access allowed');

use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;
use \App_Settings\APP_Rest_API as APP_Rest_API;

class Account extends APP_Rest_API
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('account_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        // $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_get');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        if (isset($client['form']) && $client['form'] === 'list') {
            $data = $this->account_model->get_form_list();
            if ($data === false) {
                $res['code'] = App_Code::GET_SOURCE_NOT_EXIST;
                $res['msg']  = App_Msg::GET_SOURCE_NOT_EXIST;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        $res['code'] = App_Code::GET_SOURCE_NOT_EXIST;
        $res['msg']  = App_Msg::GET_SOURCE_NOT_EXIST;
        $this->response($res, 200);
    }

    public function index_put()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        // $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_post');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        // // 1 hash password, skip hash_pwd when password is empty
        // if ($client['password'] !== '') {
        //     $hash_pwd = $this->common_tools->hash_password($client['password']);
        //     if ($hash_pwd === false) {
        //         $res['code'] = App_Code::HASH_PASSWORD_FAILED;
        //         $res['msg']  = App_Msg::HASH_PASSWORD_FAILED;
        //         SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

        //         $this->response($res, 200);
        //     }
        //     $data['password'] = $hash_pwd;
        // }
        $phone            = $client['phone'];
        $data['username'] = $client['username'];
        $data['sex']      = (int) $client['sex'];

        $data['identity_document_number'] = $client['identity_document_number'];

        // 2 非必填下拉表单项，例如job, 因为job_id是数据表的外键fk，外键不能为空字符串''
        $data['attr_01_id'] = $client['attr_01_id'] === '' ? null : $client['attr_01_id'];
        $data['attr_02_id'] = $client['attr_02_id'] === '' ? null : $client['attr_02_id'];
        $data['attr_03_id'] = $client['attr_03_id'] === '' ? null : $client['attr_03_id'];
        $data['attr_04_id'] = $client['attr_04_id'] === '' ? null : $client['attr_04_id'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // 3 更新用户基本信息
        if ($phone === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }
        $rtn = $this->account_model->update_user_basic_info($phone, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_USER_FAILED;
            $res['msg']  = App_Msg::UPDATE_USER_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 200);
    }
}
