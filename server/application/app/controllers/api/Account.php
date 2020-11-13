<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-13 21:02:12
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
        $this->load->model('common_model');
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
            $list = $this->account_model->get_form_list();
            if ($data === false) {
                $res['code'] = App_Code::GET_SOURCE_NOT_EXIST;
                $res['msg']  = App_Msg::GET_SOURCE_NOT_EXIST;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $list;
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

        // 1 根据手机号匹配数据表用户记录，必须核对用户手机号，防止用户A修改了用户B
        $user  = $this->session->userdata();
        $phone = $user['phone'];
        if (empty($phone)) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }

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
        $rtn = $this->account_model->update_user_basic_info($phone, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_USER_FAILED;
            $res['msg']  = App_Msg::UPDATE_USER_FAILED;
            $this->common_tools->app_log('error', "UPDATE_USER_FAILED");

            $this->response($res, 200);
        }

        // 4 查询用户信息，更新session数据
        $current_user = $this->common_model->get_user_by_phone($phone);
        $user_info    = $this->common_model->build_user_info($current_user);
        $rtn          = $this->common_model->update_session($user_info);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_USER_FAILED;
            $res['msg']  = App_Msg::UPDATE_USER_FAILED;
            $this->common_tools->app_log('error', "UPDATE_SESSION_FAILED");

            $this->response($res, 200);
        }

        // 5 更新后的用户信息返回前端，更新前端vuex
        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;
        $res['data'] = ['user' => $user_info];

        $this->response($res, 200);
    }
}
