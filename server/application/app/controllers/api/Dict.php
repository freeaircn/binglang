<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 21:53:05
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Dict extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('dict_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        // $valid = $this->common_tools->valid_client_data($client, 'user_index_get');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        if (isset($client['limit']) && isset($client['label'])) {
            $data = $this->dict_model->read($client);
            if ($data === false) {
                $res['code'] = App_Code::TBL_USER_READ_FAILED;
                $res['msg']  = App_Msg::TBL_USER_READ_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['id'])) {
            $data = $this->dict_model->read_by_id($client['id']);
            if ($data === false) {
                $res['code'] = App_Code::TBL_USER_READ_FAILED;
                $res['msg']  = App_Msg::TBL_USER_READ_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        $res['code'] = App_Code::PARAMS_INVALID;
        $res['msg']  = '请求资源不存在！';
        $this->response($res, 200);
    }

    public function index_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        // $valid = $this->common_tools->valid_client_data($client, 'user_index_post');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        $data['sort']        = $client['sort'];
        $data['label']       = $client['label'];
        $data['name']        = $client['name'];
        $data['enabled']     = (int) $client['enabled'];
        $data['update_time'] = date("Y-m-d H:i:s", time());

        // insert table
        $id = $this->dict_model->create($data);
        if ($id === false) {
            $res['code'] = App_Code::TBL_USER_CREATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_CREATE_FAILED;
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

        // $valid = $this->common_tools->valid_client_data($client, 'user_index_post');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        $id                  = $client['id'];
        $data['sort']        = $client['sort'];
        $data['label']       = $client['label'];
        $data['name']        = $client['name'];
        $data['enabled']     = (int) $client['enabled'];
        $data['update_time'] = date("Y-m-d H:i:s", time());

        // post和put 共用一份输入验证规则
        if ($id === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = '提交数据非法！';

            $this->response($res, 200);
        }

        $rtn = $this->dict_model->update($id, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::TBL_USER_UPDATE_FAILED;
            $res['msg']  = App_Msg::TBL_USER_UPDATE_FAILED;
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

        // $valid = $this->common_tools->valid_client_data($client, 'user_index_delete');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        $id  = $client['id'];
        $rtn = $this->dict_model->delete($id);
        if ($rtn === true) {
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
