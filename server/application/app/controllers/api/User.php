<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-14 15:31:38
 */
defined('BASEPATH') or exit('No direct script access allowed');

use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;
use \App_Settings\APP_Rest_API as APP_Rest_API;

class User extends APP_Rest_API
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('user_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_get');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        if (isset($client['limit']) && isset($client['individual'])) {
            $data = $this->user_model->read($client);
            if ($data === false) {
                $res['code'] = App_Code::GET_USER_FAILED;
                $res['msg']  = App_Msg::GET_USER_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['form']) && $client['form'] === 'create_user') {
            $data = $this->user_model->get_form_by_create_user();
            if ($data === false) {
                $res['code'] = App_Code::GET_FORM_BY_CREATE_USER_FAILED;
                $res['msg']  = App_Msg::GET_FORM_BY_CREATE_USER_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['form']) && isset($client['uid']) && $client['form'] === 'edit_user') {
            $data = $this->user_model->get_form_by_edit_user($client['uid']);
            if ($data === false) {
                $res['code'] = App_Code::GET_FORM_BY_EDIT_USER_FAILED;
                $res['msg']  = App_Msg::GET_FORM_BY_EDIT_USER_FAILED;
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

    public function index_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        // 1 hash 密码
        if ($client['password'] === '') {
            $res['code'] = App_Code::PASSWORD_IS_EMPTY;
            $res['msg']  = App_Msg::PASSWORD_IS_EMPTY;
            $this->response($res, 200);
        }
        $hash_pwd = $this->common_tools->hash_password($client['password']);
        if ($hash_pwd === false) {
            $res['code'] = App_Code::HASH_PASSWORD_FAILED;
            $res['msg']  = App_Msg::HASH_PASSWORD_FAILED;
            $this->common_tools->app_log('error', "HASH_PASSWORD_FAILED");

            $this->response($res, 200);
        }

        $data['sort']     = $client['sort'];
        $data['username'] = $client['username'];
        $data['sex']      = (int) $client['sex'];
        $data['phone']    = $client['phone'];
        $data['email']    = $client['email'];
        $data['password'] = $hash_pwd;
        $data['enabled']  = (int) $client['enabled'];

        $data['identity_document_number'] = $client['identity_document_number'];

        // job field - optional, because job_id is db fk, when insert, fk could not be ''
        $data['attr_01_id'] = $client['attr_01_id'] === '' ? null : $client['attr_01_id'];
        $data['attr_02_id'] = $client['attr_02_id'] === '' ? null : $client['attr_02_id'];
        $data['attr_03_id'] = $client['attr_03_id'] === '' ? null : $client['attr_03_id'];
        $data['attr_04_id'] = $client['attr_04_id'] === '' ? null : $client['attr_04_id'];

        // 2 创建default头像
        $temp_id = $this->user_model->create_default_avatar($data['sex']);
        if ($temp_id === false) {
            $res['code'] = App_Code::CREATE_USER_FAILED;
            $res['msg']  = App_Msg::CREATE_USER_FAILED;
            $this->common_tools->app_log('error', "CREATE_USER_FAILED");

            $this->response($res, 200);
        }
        $data['avatar_id']   = $temp_id;
        $data['update_time'] = date("Y-m-d H:i:s", time());

        // insert user table
        $uid = $this->user_model->create_user($data, $client['roles']);
        if ($uid === false) {
            $res['code'] = App_Code::CREATE_USER_FAILED;
            $res['msg']  = App_Msg::CREATE_USER_FAILED;
            $this->common_tools->app_log('error', "CREATE_USER_FAILED");

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 201);
    }

    public function index_put()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        // hash password, skip hash_pwd when password is empty
        if ($client['password'] !== '') {
            $hash_pwd = $this->common_tools->hash_password($client['password']);
            if ($hash_pwd === false) {
                $res['code'] = App_Code::HASH_PASSWORD_FAILED;
                $res['msg']  = App_Msg::HASH_PASSWORD_FAILED;
                $this->common_tools->app_log('error', "HASH_PASSWORD_FAILED");

                $this->response($res, 200);
            }
            $data['password'] = $hash_pwd;
        }

        $uid              = $client['id'];
        $data['sort']     = $client['sort'];
        $data['username'] = $client['username'];
        $data['sex']      = (int) $client['sex'];
        $data['phone']    = $client['phone'];
        $data['email']    = $client['email'];
        $data['enabled']  = (int) $client['enabled'];

        $data['identity_document_number'] = $client['identity_document_number'];

        // job field - optional, because job_id is db fk, when update, fk could not be ''
        $data['attr_01_id'] = $client['attr_01_id'] === '' ? null : $client['attr_01_id'];
        $data['attr_02_id'] = $client['attr_02_id'] === '' ? null : $client['attr_02_id'];
        $data['attr_03_id'] = $client['attr_03_id'] === '' ? null : $client['attr_03_id'];
        $data['attr_04_id'] = $client['attr_04_id'] === '' ? null : $client['attr_04_id'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // update user table
        if ($uid === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }
        $rtn = $this->user_model->update_user($uid, $data, $client['roles']);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_USER_FAILED;
            $res['msg']  = App_Msg::UPDATE_USER_FAILED;
            $this->common_tools->app_log('error', "UPDATE_USER_FAILED");

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 200);
    }

    public function index_delete()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_delete');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id  = $client['id'];
        $rtn = $this->user_model->delete($id);
        if ($rtn === true) {
            $res['code'] = App_Code::SUCCESS;
            $res['msg']  = App_Msg::SUCCESS;
        } else {
            $res['code'] = App_Code::DELETE_USER_FAILED;
            $res['msg']  = App_Msg::DELETE_USER_FAILED;
            $this->common_tools->app_log('error', "DELETE_USER_FAILED");
        }

        $this->response($res, 200);
    }

}
