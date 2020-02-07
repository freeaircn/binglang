<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 21:20:16
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Role extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('role_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_role', 'index_get');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        if (isset($client['limit']) && isset($client['label'])) {
            $data = $this->role_model->read($client);
            if ($data === false) {
                $res['code'] = App_Code::GET_ROLE_FAILED;
                $res['msg']  = App_Msg::GET_ROLE_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['id'])) {
            $data = $this->role_model->read_by_id($client['id']);
            if ($data === false) {
                $res['code'] = App_Code::GET_ROLE_FOR_EDIT_FAILED;
                $res['msg']  = App_Msg::GET_ROLE_FOR_EDIT_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        $res['code'] = App_Code::GET_SOURCE_NOT_EXIST;
        $res['msg']  = App_Msg::GET_SOURCE_NOT_EXIST;
        $this->response($res, 200);

        // $select_col = $this->get('select_col');
        // $method     = $this->get('method');
        // $cond       = $this->get('cond');
        // $cond_col   = $this->get('cond_col');

        // $res = $this->role_model->read($select_col, $method, $cond, $cond_col);

        // $this->response($res, 200);
    }

    public function index_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_role', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $data['sort']    = $client['sort'];
        $data['label']   = $client['label'];
        $data['name']    = $client['name'];
        $data['enabled'] = (int) $client['enabled'];
        $data['remark']  = $client['remark'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        $id = $this->role_model->create($data);
        if ($id === false) {
            $res['code'] = App_Code::CREATE_ROLE_FAILED;
            $res['msg']  = App_Msg::CREATE_ROLE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
        }
        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 201);

        // $data['sort']    = $this->post('sort');
        // $data['label']   = $this->post('label');
        // $data['name']    = $this->post('name');
        // $data['enabled'] = ($this->post('enabled') === '1') ? 1 : 0;
        // $data['remark']  = $this->post('remark');

        // $data['update_time'] = date("Y-m-d H:i:s", time());

        // $result = $this->role_model->create($data);
        // if ($result === false) {
        //     $this->response([], 500);
        // } else {
        //     $this->response($result, 201);
        // }
    }

    public function index_put()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_role', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id              = $client['id'];
        $data['sort']    = $client['sort'];
        $data['label']   = $client['label'];
        $data['name']    = $client['name'];
        $data['enabled'] = (int) $client['enabled'];
        $data['remark']  = $client['remark'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // post和put 共用一份输入验证规则
        if ($id === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }

        $rtn = $this->role_model->update($id, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_ROLE_FAILED;
            $res['msg']  = App_Msg::UPDATE_ROLE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 200);

        // $id              = $this->put('id');
        // $data['sort']    = $this->put('sort');
        // $data['label']   = $this->put('label');
        // $data['name']    = $this->put('name');
        // $data['enabled'] = ($this->put('enabled') === '1') ? 1 : 0;
        // $data['remark']  = $this->put('remark');

        // $data['update_time'] = date("Y-m-d H:i:s", time());

        // $result = $this->role_model->update($id, $data);
        // if ($result === false) {
        //     $this->response([], 500);
        // } else {
        //     $this->response($result, 200);
        // }
    }

    public function index_delete()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_role', 'index_delete');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id = $client['id'];

        $rtn = $this->role_model->delete($id);
        if ($rtn === true) {
            $res['code'] = App_Code::SUCCESS;
            $res['msg']  = App_Msg::SUCCESS;
        } else {
            $res['code'] = App_Code::DELETE_ROLE_FAILED;
            $res['msg']  = App_Msg::DELETE_ROLE_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);
        }

        $this->response($res, 200);

        // $id = $this->delete('id');
        // // $ids = (string)$id;

        // // $ids = $this->dict_model->get_all_children_ids($id);
        // $result = $this->role_model->delete($id);

        // $this->response($result, 200);
    }
}
