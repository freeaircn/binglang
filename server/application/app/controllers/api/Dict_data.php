<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 22:15:22
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Dict_data extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('dict_data_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        // $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_dict', 'index_get');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        if (isset($client['limit']) && isset($client['label'])) {
            $data = $this->dict_data_model->read($client);
            if ($data === false) {
                $res['code'] = App_Code::GET_DICT_FAILED;
                $res['msg']  = App_Msg::GET_DICT_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['id'])) {
            $data = $this->dict_data_model->read_by_id($client['id']);
            if ($data === false) {
                $res['code'] = App_Code::GET_DICT_FOR_EDIT_FAILED;
                $res['msg']  = App_Msg::GET_DICT_FOR_EDIT_FAILED;
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

        // $res = $this->dict_data_model->read($select_col, $method, $cond, $cond_col);

        // $this->response($res, 200);
    }

    public function index_post()
    {
        $data['sort']    = $this->post('sort');
        $data['label']   = $this->post('label');
        $data['name']    = $this->post('name');
        $data['code']    = $this->post('code');
        $data['enabled'] = ($this->post('enabled') === '1') ? 1 : 0;
        $data['dict_id'] = $this->post('dict_id');

        $data['update_time'] = date("Y-m-d H:i:s", time());

        $result = $this->dict_data_model->create($data);
        if ($result === false) {
            $this->response([], 500);
        } else {
            $this->response($result, 201);
        }

    }

    public function index_put()
    {
        $id              = $this->put('id');
        $data['sort']    = $this->put('sort');
        $data['label']   = $this->put('label');
        $data['name']    = $this->put('name');
        $data['code']    = $this->put('code');
        $data['enabled'] = ($this->put('enabled') === '1') ? 1 : 0;

        $data['update_time'] = date("Y-m-d H:i:s", time());

        $result = $this->dict_data_model->update($id, $data);
        if ($result === false) {
            $this->response([], 500);
        } else {
            $this->response($result, 200);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');
        // $ids = (string)$id;

        // $ids = $this->dict_model->get_all_children_ids($id);
        $result = $this->dict_data_model->delete($id);

        $this->response($result, 200);
    }
}
