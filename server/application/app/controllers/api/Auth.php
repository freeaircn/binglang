<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-24 20:42:46
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Auth extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->config->load('config', true);
        $this->load->model('auth_model');
        $this->load->model('common_model');
        $this->load->library('common_tools');
    }

    /** 1
     * @Description: 登录请求
     * @Author: freeair
     * @Date: 2020-11-13 16:42:39
     * @param {*}
     * @return {*}
     */
    public function login_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->check_client_data($client, ['phone', 'password']);
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;
            $this->response($res, 200);
        }

        $phone    = $client['phone'];
        $password = $client['password'];
        // $remember = $client['remember'];

        $ip_address = $this->input->ip_address();

        // 1 检查尝试登陆次数
        if ($this->auth_model->is_max_login_attempts_exceeded($phone, $ip_address)) {
            $res['code'] = App_Code::MAX_LOGIN_ATTEMPT_EXCEEDED;
            $res['msg']  = App_Msg::MAX_LOGIN_ATTEMPT_EXCEEDED;
            $this->response($res, 200);
        }

        // 2 查询用户是否存在
        $user = $this->common_model->get_user_by_phone($phone);
        if ($user === false) {
            $this->auth_model->increase_login_attempts($phone, $ip_address);

            $res['code'] = App_Code::USERNAME_OR_PASSWORD_WRONG;
            $res['msg']  = App_Msg::USERNAME_OR_PASSWORD_WRONG;
            $this->response($res, 200);
        }

        // 3 验证用户登陆密码
        if (($this->auth_model->verify_password($password, $user['password'])) === false) {
            $this->auth_model->increase_login_attempts($phone, $ip_address);

            $res['code'] = App_Code::USERNAME_OR_PASSWORD_WRONG;
            $res['msg']  = App_Msg::USERNAME_OR_PASSWORD_WRONG;
            $this->response($res, 200);
        }

        // 4 检查用户enabled
        if ($user['enabled'] == 0) {
            $res['code'] = App_Code::USER_NOT_ENABLED;
            $res['msg']  = App_Msg::USER_NOT_ENABLED;
            $this->response($res, 200);
        }

        // 5 清除用户尝试失败记录
        $this->auth_model->clear_login_attempts($phone, $ip_address);

        // 6 更新用户登陆成功时间
        $this->auth_model->update_last_login($user['id']);

        // 7 查询用户拥有的访问权限
        $user_acl = $this->auth_model->get_user_acl_by_uid($user['id']);

        // 8 提取用户信息，记录session
        $user_info    = $this->common_model->build_user_info($user);
        $session_data = $this->common_model->build_user_session_data($user_info, $user_acl);
        // $this->common_model->set_session($user_info, $other_data);
        $this->session->set_userdata($session_data);
        $this->session->sess_regenerate(true);

        // 9 remember用户，处理
        // if ($remember) {
        //     $this->remember_user($phone);
        // } else {
        //     $this->clear_remember_code($phone);
        // }

        // 10 如果密码算法参数变化，重新hash password，并写入数据库
        $this->auth_model->rehash_password_if_needed($user['password'], $user['phone'], $password);

        // 11 记录log
        $this->common_tools->app_log('notice', "login successfully.", 'auth-login');

        // 12 组织response数据
        $str         = session_id();
        $expire_code = $str[17] . $str[12] . $str[8] . $str[5] . $str[3];
        $expire_time = time() + $this->config->item('sess_expiration', 'config');

        $res['data'] = ['expire_time' => $expire_time, 'expire_code' => $expire_code, 'user' => $user_info];
        $res['code'] = App_Code::SUCCESS;
        $this->response($res, 200);
    }

    /** 2
     * @Description: 前端刷新页面操作，检查用户登录状态
     * @Author: freeair
     * @Date: 2020-11-13 16:43:00
     * @param {*}
     * @return {*}
     */
    public function check_user_get()
    {
        $phone = $this->session->userdata('phone');

        if (empty($phone)) {
            $res['code'] = App_Code::USER_NOT_LOGIN;
            $res['msg']  = App_Msg::USER_NOT_LOGIN;
            $this->response($res, 200);
        } else {
            $user      = $this->common_model->get_user_by_phone($phone);
            $user_info = $this->common_model->build_user_info($user);

            $res['data'] = ['user' => $user_info];
            $res['code'] = App_Code::SUCCESS;
            $this->response($res, 200);
        }
    }

    /** 3
     * @Description: 登出请求
     * @Author: freeair
     * @Date: 2020-11-13 16:44:04
     * @param {*}
     * @return {*}
     */
    public function logout_post()
    {
        // $array_items = ['username', 'email'];

        // $this->session->unset_userdata($array_items);

        // $this->session->unset_userdata([$identity, 'id', 'user_id']);

        // // delete the remember me cookies if they exist
        // delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));

        // // Clear all codes
        // $this->ion_auth_model->clear_forgotten_password_code($identity);
        // $this->ion_auth_model->clear_remember_code($identity);

        // 1 log
        $user = $this->session->userdata();
        $this->common_tools->app_log('notice', "logout.", 'auth-logout');

        // 2 销毁session
        $this->session->sess_destroy();

        $res['code'] = App_Code::SUCCESS;
        $this->response($res, 200);
    }

    /** 4
     * @Description: 忘记密码处理，前端请求验证码，发送邮件
     * @Author: freeair
     * @Date: 2020-11-13 16:41:24
     * @param {*}
     * @return {*}
     */
    public function verification_code_get()
    {
        $client = $this->get();

        $valid = $this->common_tools->check_client_data($client, ['phone']);
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;
            $this->response($res, 200);
        }

        $phone = $client['phone'];

        // 1 查询用户是否存在
        $user = $this->common_model->get_user_by_phone($phone);
        if ($user === false) {
            $res['code'] = App_Code::PHONE_NOT_EXISTING;
            $res['msg']  = App_Msg::PHONE_NOT_EXISTING;
            $this->response($res, 200);
        }

        // 2 查询用户的email是否存在
        $email = $user['email'];
        if (empty($email)) {
            $res['code'] = App_Code::USER_EMAIL_NOT_EXISTING;
            $res['msg']  = App_Msg::USER_EMAIL_NOT_EXISTING;
            $this->response($res, 200);
        }

        // 3 生成验证码
        $code = $this->auth_model->create_verification_code($phone);
        if ($code === false) {
            $res['code'] = App_Code::GEN_VERIFICATION_CODE_FAILED;
            $res['msg']  = App_Msg::GEN_VERIFICATION_CODE_FAILED;
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
            $res['code'] = App_Code::SYS_SEND_MAIL_FAILED;
            $res['msg']  = App_Msg::SYS_SEND_MAIL_FAILED;
            $this->common_tools->app_log('error', "send mail failed.", 'auth-verification_code');
        }
        $this->response($res, 200);
    }

    /** 6
     * @Description: 重置密码请求
     * @Author: freeair
     * @Date: 2020-11-13 16:45:04
     * @param {*}
     * @return {*}
     */
    public function reset_password_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->check_client_data($client, ['phone', 'password', 'verification_code']);
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;
            $this->response($res, 200);
        }

        $phone    = $client['phone'];
        $code     = $client['code'];
        $password = $client['password'];

        // 1 验证用户请求
        $is_valid = $this->auth_model->check_verification_code($phone, $code, true);
        if (!$is_valid) {
            $res['code'] = App_Code::SYS_VERIFICATION_CODE_INVALID;
            $res['msg']  = App_Msg::SYS_VERIFICATION_CODE_INVALID;
            $this->response($res, 200);
        }

        // 2 hash密码
        $hash_pwd = $this->common_tools->hash_password($password);
        if ($hash_pwd === false) {
            $res['code'] = App_Code::SYS_RESET_PASSWORD_FAILED;
            $res['msg']  = App_Msg::SYS_RESET_PASSWORD_FAILED;
            $this->common_tools->app_log('error', "create password hash failed.", 'auth-reset_pwd');

            $this->response($res, 200);
        }

        // 3 重置密码
        $result = $this->common_model->update_password_by_phone($phone, $hash_pwd);
        if (!$result) {
            $res['code'] = App_Code::SYS_RESET_PASSWORD_FAILED;
            $res['msg']  = App_Msg::SYS_RESET_PASSWORD_FAILED;
            $this->common_tools->app_log('error', "update password to db failed.", 'auth-reset_pwd');
        }

        // 4 log
        $this->common_tools->app_log('error', "reset password successfully.", 'auth-reset_pwd');

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = '请使用新密码登录!';
        $this->response($res, 200);
    }

    /**
     * @Description: 临时测试
     * @Author: freeair
     * @Date: 2020-11-13 16:45:35
     * @param {*}
     * @return {*}
     */
    public function test_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $meter_time = $client['metertime'];
        // mktime(hour,minute,second,month,day,year)
        $d = mktime($meter_time[1], $meter_time[0], 0, $meter_time[3], $meter_time[2], $meter_time[4]);

        $res['data']      = $client['metervalue'];
        $res['date_time'] = date("Y-m-d H:i:s", $d);
        $res['code']      = App_Code::SUCCESS;
        $this->response($res, 200);
    }
}
