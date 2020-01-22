<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-22 21:23:44
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
        $wanted = $this->get('wanted');
        $uid    = $this->get('uid');

        switch ($wanted) {
            case "all":
                $data = $this->user_model->read_all();
                if ($data === false) {
                    $res['code'] = App_Code::TBL_USER_READ_FAILED;
                    $res['msg']  = App_Msg::TBL_USER_READ_FAILED;
                } else {
                    $res['code'] = App_Code::SUCCESS;
                    $res['data'] = $data;
                }
                break;
            case "new_form":
                $data = $this->user_model->prepare_new_form();
                if ($data === false) {
                    $res['code'] = App_Code::TBL_USER_READ_FAILED;
                    $res['msg']  = App_Msg::TBL_USER_READ_FAILED;
                } else {
                    $res['code'] = App_Code::SUCCESS;
                    $res['data'] = $data;
                }
                break;
            case "current_form":
                $data = $this->user_model->prepare_current_form($uid);
                if ($data === false) {
                    $res['code'] = App_Code::TBL_USER_READ_FAILED;
                    $res['msg']  = App_Msg::TBL_USER_READ_FAILED;
                } else {
                    $res['code'] = App_Code::SUCCESS;
                    $res['data'] = $data;
                }
                break;
            default:

        }
        $this->response($res, 200);
    }

    public function index_post()
    {
        $data['username']                 = $this->post('username');
        $data['sex']                      = ($this->post('sex') === '1') ? 1 : 0;
        $data['phone']                    = $this->post('phone');
        $data['email']                    = $this->post('email');
        $data['enabled']                  = ($this->post('enabled') === '1') ? 1 : 0;
        $data['identity_document_number'] = $this->post('identity_document_number');
        $data['employee_number']          = $this->post('employee_number');

        $dept_id          = $this->post('dept_id');
        $job_id           = $this->post('job_id');
        $pwd              = $this->post('password');
        $role_ids         = $this->post('role_ids');
        $extra_attributes = $this->post('extra_attributes');

        // dept, job items - optional
        if (!empty($dept_id)) {
            $data['dept_id'] = $dept_id;
        }
        if (!empty($job_id)) {
            $data['job_id'] = $job_id;
        }

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // hash password
        $hash_pwd = $this->common_tools->hash_password($pwd);
        if ($hash_pwd === false) {
            $res['code'] = App_Code::HASH_PASSWORD_FAILED;
            $res['msg']  = App_Msg::HASH_PASSWORD_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }
        $data['password'] = $hash_pwd;

        // insert user table
        $uid = $this->user_model->create_user($data);
        if ($uid === false) {
            $res['code'] = App_Code::TBL_USER_CREATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_CREATE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }

        // get user id, insert role table
        $rtn = $this->user_model->create_user_role($uid, $role_ids);
        if ($rtn === false) {
            $res['code'] = App_Code::TBL_USER_ROLE_CREATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_ROLE_CREATE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }

        // get user id, insert user extra attribute table
        $rtn = $this->user_model->create_user_extra_attribute($uid, $extra_attributes);
        if ($rtn === false) {
            $res['code'] = App_Code::TBL_USER_EXTRA_ATTR_CREATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_EXTRA_ATTR_CREATE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }

        $res['code'] = App_Code::SUCCESS;
        $res['data'] = ['uid' => $uid];

        $this->response($res, 201);
    }

    public function index_put()
    {
        $uid                              = $this->put('id');
        $data['username']                 = $this->put('username');
        $data['sex']                      = ($this->put('sex') === '1') ? 1 : 0;
        $data['phone']                    = $this->put('phone');
        $data['email']                    = $this->put('email');
        $data['enabled']                  = ($this->put('enabled') === '1') ? 1 : 0;
        $data['identity_document_number'] = $this->put('identity_document_number');
        $data['employee_number']          = $this->put('employee_number');

        $dept_id          = $this->put('dept_id');
        $job_id           = $this->put('job_id');
        $pwd              = $this->put('password');
        $role_ids         = $this->put('role_ids');
        $extra_attributes = $this->put('extra_attributes');

        // dept, job items - optional
        if (!empty($dept_id)) {
            $data['dept_id'] = $dept_id;
        }
        if (!empty($job_id)) {
            $data['job_id'] = $job_id;
        }

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // hash password, when update, skip hash_pwd when pwd is empty
        if (!empty($pwd)) {
            $hash_pwd = $this->common_tools->hash_password($pwd);
            if ($hash_pwd === false) {
                $res['code'] = App_Code::HASH_PASSWORD_FAILED;
                $res['msg']  = App_Msg::HASH_PASSWORD_FAILED;
                SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

                $this->response($res, 200);
                return;
            }
            $data['password'] = $hash_pwd;
        }

        // update user table
        $rtn = $this->user_model->update_user($uid, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::TBL_USER_UPDATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_UPDATE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }

        // get user id, update role table
        $rtn = $this->user_model->update_user_role($uid, $role_ids);
        if ($rtn === false) {
            $res['code'] = App_Code::TBL_USER_ROLE_UPDATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_ROLE_UPDATE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }

        // get user id, update user extra attribute table
        $rtn = $this->user_model->update_user_extra_attribute($uid, $extra_attributes);
        if ($rtn === false) {
            $res['code'] = App_Code::TBL_USER_EXTRA_ATTR_UPDATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_EXTRA_ATTR_UPDATE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
            return;
        }

        $res['code'] = App_Code::SUCCESS;
        $res['data'] = ['uid' => $uid];

        $this->response($res, 200);
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        $result = $this->user_model->delete($id);

        if ($result === true) {
            $res['code'] = App_Code::SUCCESS;
            $res['msg']  = App_Msg::SUCCESS;
        } else {
            $res['code'] = App_Code::TBL_USER_DELETE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_DELETE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);
        }

        $this->response($res, 200);
    }
}
