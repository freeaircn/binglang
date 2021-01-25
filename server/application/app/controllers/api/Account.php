<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-26 00:24:51
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

        // 1 处理输入
        $user_prop_mask = $this->common_model->get_user_prop_by_mask($this->config->item('user_prop_edit_mask', 'app_config'));
        $data           = array_intersect_key($client, $user_prop_mask);

        // 2 非必填下拉表单项，例如job, 因为job_id是数据表的外键fk，外键不能为空字符串''
        // $data['attr_01_id'] = $client['attr_01_id'] === '' ? null : $client['attr_01_id'];
        foreach ($data as $i => $value) {
            if (stripos($i, '_id') && $value === '') {
                $data[$i] = null;
            }
        }

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // 3 更新用户基本信息，从session获取用户phone，必须核对用户手机号，防止用户A修改了用户B
        $phone = $this->session->userdata('phone');
        $rtn   = $this->account_model->update_user_basic_info($phone, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::ACCOUNT_UPDATE_USER_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_UPDATE_USER_FAILED;
            $this->common_tools->app_log('error', "update user info to db failed.", 'account-update_basic_info');

            $this->response($res, 200);
        }

        // 4 查询用户信息，更新session数据
        if ($this->common_tools->update_user_prop_in_session($data) === false) {
            $res['code'] = App_Code::ACCOUNT_UPDATE_USER_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_UPDATE_USER_FAILED;
            $this->common_tools->app_log('error', "update user session failed.", 'account-update_basic_info');

            $this->response($res, 200);
        }

        // 5 更新后的用户信息返回前端，更新前端vuex
        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;
        $res['data'] = ['user' => $data];

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
            $res['msg1'] = $config['upload_path'];
            $this->response($res, 200);
        }

        // 2 调整上传图片大小
        $source_image = $this->upload->data('full_path');
        $name         = $this->upload->data('file_name');
        $path         = $this->config->item('avatar_active_path', 'app_config');

        $new_image = FCPATH . $this->config->item('avatar_active_path', 'app_config');
        if (!$this->common_tools->resize_avatar_img($source_image, $new_image)) {
            $res['code'] = App_Code::ACCOUNT_UPDATE_AVATAR_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_UPDATE_AVATAR_FAILED;
            $this->common_tools->app_log('error', "resize avatar img failed.", 'account-avatar');

            $this->response($res, 200);
        }

        // 3 查询头像文件路径和文件名，待后续删除旧头像文件
        $avatar_id      = $this->session->userdata('avatar_id');
        $current_avatar = $this->account_model->get_user_avatar($avatar_id);

        // 4 更改数据库中用户头像记录
        if (!$this->account_model->update_user_avatar($avatar_id, $path, $name)) {
            $res['code'] = App_Code::ACCOUNT_UPDATE_AVATAR_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_UPDATE_AVATAR_FAILED;
            $this->common_tools->app_log('error', "update avatar to db failed.", 'account-avatar');

            $this->response($res, 200);
        }

        // 5 更新session数据
        $this->session->set_userdata('avatar', $path . $name);

        // 6 删除旧头像文件
        if ($current_avatar['path'] !== $this->config->item('avatar_default_path', 'app_config')) {
            unlink(FCPATH . $current_avatar['path'] . $current_avatar['real_name']);
        }
        unlink(FCPATH . $this->config->item('avatar_upload_path', 'app_config') . $name);

        // 7 更新后的用户信息返回前端，更新前端vuex
        $res['code']   = App_Code::SUCCESS;
        $res['msg']    = App_Msg::SUCCESS;
        $res['avatar'] = $path . $name;

        $this->response($res, 200);
    }

    /**
     * @Description: 请求验证码，用于修改安全设置，例如：绑定手机号，邮箱，密码
     * @Author: freeair
     * @Date: 2020-11-17 17:27:10
     * @param {*}
     * @return {*}
     */
    public function verification_code_get()
    {
        $phone = $this->session->userdata('phone');

        // 1 查询用户是否存在
        $user = $this->common_model->get_user_by_phone($phone);
        if ($user === false) {
            $res['code'] = App_Code::ACCOUNT_GET_CODE_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_GET_CODE_FAILED;
            $this->common_tools->app_log('error', "user's phone not existing.", 'account-verification_code');

            $this->response($res, 200);
        }

        // 2 查询用户的email是否存在
        $email = $user['email'];
        if (empty($email)) {
            $res['code'] = App_Code::ACCOUNT_GET_CODE_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_GET_CODE_FAILED;
            $this->common_tools->app_log('error', "user's email not existing.", 'account-verification_code');

            $this->response($res, 200);
        }

        // 3 生成验证码
        $code = $this->account_model->create_verification_code($phone);
        if ($code === false) {
            $res['code'] = App_Code::ACCOUNT_GET_CODE_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_GET_CODE_FAILED;
            $this->common_tools->app_log('error', "create code failed.", 'account-verification_code');

            $this->response($res, 200);
        }

        // 4 发送邮件
        $data = [
            'phone'             => $phone,
            'verification_code' => $code,
            'dt'                => date("Y-m-d H:i:s"),
        ];

        if ($this->common_tools->api_send_mail($email, $data) === true) {
            $res['data'] = ['email' => $email];
            $res['code'] = App_Code::SUCCESS;
        } else {
            $res['code'] = App_Code::ACCOUNT_GET_CODE_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_GET_CODE_FAILED;
            $this->common_tools->app_log('error', "send mail failed.", 'account-verification_code');
        }
        $this->response($res, 200);
    }

    // 提交修改安全设置，例如：绑定手机号，邮箱，密码
    public function security_setting_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $phone = $this->session->userdata('phone');

        $code = $client['code'];
        $key  = $client['prop'];

        $data = [];
        foreach ($client as $index => $value) {
            if ($index === $key) {
                $data[$key] = $value;
            }
        }

        // 1 验证码
        $is_valid = $this->account_model->check_verification_code($phone, $code, true);
        if (!$is_valid) {
            $res['code'] = App_Code::SYS_VERIFICATION_CODE_INVALID;
            $res['msg']  = App_Msg::SYS_VERIFICATION_CODE_INVALID;
            $this->response($res, 200);
        }

        // 2 手机号，邮箱是否被绑定
        foreach ($data as $index => $value) {
            if ($index === $key) {
                if ($this->common_model->is_existing_in_tbl($key, $value, 'user')) {
                    $res['code'] = App_Code::ACCOUNT_NEW_SECURITY_SETTING_EXISTING;
                    $res['msg']  = $value . App_Msg::ACCOUNT_NEW_SECURITY_SETTING_EXISTING;
                    $this->response($res, 200);
                }
            }
        }

        // 3 更新安全设置
        $result = $this->account_model->update_security_setting_by_phone($phone, $data);
        if (!$result) {
            $res['code'] = App_Code::ACCOUNT_UPDATE_SECURITY_SETTING_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_UPDATE_SECURITY_SETTING_FAILED;
            $this->common_tools->app_log('error', "update data to db failed.", 'account-update_security_setting');

            $this->response($res, 200);
        }

        // 4 log
        $this->common_tools->app_log('warning', $key . " security setting successfully.", 'account-update_security_setting');

        // 5 修改email，更新session
        if ($key === 'email') {
            $this->common_tools->update_user_prop_in_session($data);

            $res['msg']  = App_Msg::SUCCESS;
            $res['data'] = ['user' => $data];
        }

        // 6 修改手机号，强制清除session
        if ($key === 'phone') {
            $this->session->sess_destroy();

            $res['msg']  = '请使用新手机号登录！';
            $res['data'] = ['cmd' => 'logout'];
        }

        $res['code'] = App_Code::SUCCESS;
        $this->response($res, 200);
    }

    public function password_put()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $phone    = $this->session->userdata('phone');
        $password = $client['password'];

        // 1 hash密码
        $hash_pwd = $this->common_tools->hash_password($password);
        if ($hash_pwd === false) {
            $res['code'] = App_Code::ACCOUNT_CHANGE_PWD_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_CHANGE_PWD_FAILED;
            $this->common_tools->app_log('error', "hash pwd failed.", 'account-change_password');

            $this->response($res, 200);
        }

        // 2 修改密码
        $result = $this->common_model->update_password_by_phone($phone, $hash_pwd);
        if (!$result) {
            $res['code'] = App_Code::ACCOUNT_CHANGE_PWD_FAILED;
            $res['msg']  = App_Msg::ACCOUNT_CHANGE_PWD_FAILED;
            $this->common_tools->app_log('error', "update pwd to db failed.", 'account-change_password');
        }

        // 3 log
        $this->common_tools->app_log('warning', "change password successfully.", 'account-change_password');

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;
        $this->response($res, 200);
    }
}
