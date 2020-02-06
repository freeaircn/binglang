<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-05 20:09:22
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Dept extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('dept_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_dept', 'index_get');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        if (isset($client['label'])) {
            $data = $this->dept_model->read($client);
            if ($data === false) {
                $res['code'] = App_Code::GET_DEPT_FAILED;
                $res['msg']  = App_Msg::GET_DEPT_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['req']) && $client['req'] === 'id_label_pid') {
            $data = $this->dept_model->select_by_req('id, label, pid');
            if ($data === false) {
                $res['code'] = App_Code::GET_DEPT_FAILED;
                $res['msg']  = App_Msg::GET_DEPT_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['id'])) {
            $data = $this->dept_model->read_by_id($client['id']);
            if ($data === false) {
                $res['code'] = App_Code::GET_DEPT_FOR_EDIT_FAILED;
                $res['msg']  = App_Msg::GET_DEPT_FOR_EDIT_FAILED;
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

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_dept', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $data['sort']    = $client['sort'];
        $data['label']   = $client['label'];
        $data['pid']     = $client['pid'];
        $data['enabled'] = (int) $client['enabled'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        $id = $this->dept_model->create($data);
        if ($id === false) {
            $res['code'] = App_Code::CREATE_DEPT_FAILED;
            $res['msg']  = App_Msg::CREATE_DEPT_FAILED;
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

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_dept', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id              = $client['id'];
        $data['sort']    = $client['sort'];
        $data['label']   = $client['label'];
        $data['pid']     = $client['pid'];
        $data['enabled'] = (int) $client['enabled'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // post和put 共用一份输入验证规则
        if ($id === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }

        $rtn = $this->dept_model->update($id, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_DEPT_FAILED;
            $res['msg']  = App_Msg::UPDATE_DEPT_FAILED;
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

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_dept', 'index_delete');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id = $client['id'];

        $ids = $this->dept_model->get_all_children_ids($id);
        $rtn = $this->dept_model->delete($ids);
        if ($rtn === true) {
            $res['code'] = App_Code::SUCCESS;
            $res['msg']  = App_Msg::SUCCESS;
        } else {
            $res['code'] = App_Code::DELETE_DEPT_FAILED;
            $res['msg']  = App_Msg::DELETE_DEPT_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);
        }

        $this->response($res, 200);
    }
}
