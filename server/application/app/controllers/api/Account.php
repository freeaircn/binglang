<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-16 21:16:07
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

        $this->config->load('app_config', true);

        $this->load->model('account_model');
        $this->load->model('common_model');
        $this->load->library('common_tools');
    }

    public function basic_Info_form_list_content_get()
    {
        $list = $this->account_model->get_form_list();
        if ($list === false) {
            $res['code'] = App_Code::GET_SOURCE_NOT_EXIST;
            $res['msg']  = App_Msg::GET_SOURCE_NOT_EXIST;
        } else {
            $res['code'] = App_Code::SUCCESS;
            $res['data'] = $list;
        }
        $this->response($res, 200);
    }

    public function basic_info_put()
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
        $phone = $this->session->userdata('phone');

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

    public function avatar_post()
    {
        $phone     = $this->session->userdata('phone');
        $file_name = $phone . '_' . time();
        // 1 响应前端上传请求
        $config['upload_path']   = FCPATH . $this->config->item('avatar_upload_path', 'app_config');
        $config['file_name']     = $file_name;
        $config['overwrite']     = true;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048;
        // $config['max_width']     = 1024;
        // $config['max_height']    = 768;
        // $config['encrypt_name']  = true;

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $res['code'] = 300;
            $res['msg']  = $this->upload->display_errors();
            $this->response($res, 200);
        }

        // 2 调整上传图片大小
        $source_image = $this->upload->data('full_path');
        $name         = $this->upload->data('file_name');
        $path         = $this->config->item('avatar_active_path', 'app_config');

        $new_image = FCPATH . $this->config->item('avatar_active_path', 'app_config');
        if (!$this->common_tools->resize_avatar_img($source_image, $new_image)) {
            $res['code'] = 300;
            $res['msg']  = 'resize img failed';
            $this->response($res, 200);
        }

        // 3 查询头像文件路径和文件名，待后续删除旧头像文件
        $avatar_id      = $this->session->userdata('avatar_id');
        $current_avatar = $this->account_model->get_user_avatar($avatar_id);

        // 4 更改数据库中用户头像记录
        if (!$this->account_model->update_user_avatar($avatar_id, $path, $name)) {
            $res['code'] = 300;
            $res['msg']  = 'update DB failed';
            $this->response($res, 200);
        }

        // 5 更新session数据
        $this->session->set_userdata('avatar', ['name' => $name, 'path' => $path]);

        // 6 删除旧头像文件
        if ($current_avatar['path'] !== $this->config->item('avatar_default_path', 'app_config')) {
            unlink(FCPATH . $current_avatar['path'] . $current_avatar['real_name']);
        }
        unlink(FCPATH . $this->config->item('avatar_upload_path', 'app_config') . $name);

        // 7 更新后的用户信息返回前端，更新前端vuex
        $res['code']   = App_Code::SUCCESS;
        $res['msg']    = App_Msg::SUCCESS;
        $res['avatar'] = ['path' => $path, 'name' => $name];

        $res['code'] = App_Code::SUCCESS;
        $this->response($res, 200);
    }
}
