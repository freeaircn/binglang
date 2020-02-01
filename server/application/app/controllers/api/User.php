<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 16:34:15
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class User extends RestController
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

        if (isset($client['form']) && $client['form'] === 'user_create') {
            $data = $this->user_model->get_form_by_user_create();
            if ($data === false) {
                $res['code'] = App_Code::GET_FORM_BY_USER_CREATE_FAILED;
                $res['msg']  = App_Msg::GET_FORM_BY_USER_CREATE_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['form']) && isset($client['uid']) && $client['form'] === 'user_edit') {
            $data = $this->user_model->get_form_by_user_edit($client['uid']);
            if ($data === false) {
                $res['code'] = App_Code::GET_FORM_BY_USER_EDIT_FAILED;
                $res['msg']  = App_Msg::GET_FORM_BY_USER_EDIT_FAILED;
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
        // $valid = $this->common_tools->valid_client_data($client, 'user_index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        // hash password
        if ($client['password'] === '') {
            $res['code'] = App_Code::PASSWORD_IS_EMPTY;
            $res['msg']  = App_Msg::PASSWORD_IS_EMPTY;
            $this->response($res, 200);
        }
        $hash_pwd = $this->common_tools->hash_password($client['password']);
        if ($hash_pwd === false) {
            $res['code'] = App_Code::HASH_PASSWORD_FAILED;
            $res['msg']  = App_Msg::HASH_PASSWORD_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
        }

        $data['sort']     = $client['sort'];
        $data['username'] = $client['username'];
        $data['sex']      = (int) $client['sex'];
        $data['phone']    = $client['phone'];
        $data['email']    = $client['email'];
        $data['password'] = $hash_pwd;
        $data['enabled']  = (int) $client['enabled'];
        $data['dept_id']  = $client['dept_id'];

        $data['identity_document_number'] = $client['identity_document_number'];

        // job field - optional, because job_id is db fk, when insert, fk could not be ''
        if ($client['job_id'] === '') {
            $data['job_id'] = null;
        } else {
            $data['job_id'] = $client['job_id'];
        }
        $data['update_time'] = date("Y-m-d H:i:s", tCREATE_USER_FALIEDime());

        // insert user table
        $uid = $this->user_model->create_user($data, $client['roles'], $client['user_attribute']);
        if ($uid === false) {
            $res['code'] = App_Code::CREATE_USER_FAILED;
            $res['msg']  = App_Msg::CREATE_USER_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

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
        // $valid = $this->common_tools->valid_client_data($client, 'user_index_post');
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
                SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

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
        $data['dept_id']  = $client['dept_id'];

        $data['identity_document_number'] = $client['identity_document_number'];

        // job field - optional, because job_id is db fk, when update, fk could not be ''
        if ($client['job_id'] === '') {
            $data['job_id'] = null;
        } else {
            $data['job_id'] = $client['job_id'];
        }
        $data['update_time'] = date("Y-m-d H:i:s", time());

        // update user table
        if ($uid === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }
        $rtn = $this->user_model->update_user($uid, $data, $client['roles'], $client['user_attribute']);
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

    public function index_delete()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_user', 'index_delete');
        // $valid = $this->common_tools->valid_client_data($client, 'index_delete');
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
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);
        }

        $this->response($res, 200);
    }

}
