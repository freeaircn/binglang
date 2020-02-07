<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 22:38:14
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;

class Menu extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('menu_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_menu', 'index_get');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        if (isset($client['title'])) {
            $data = $this->menu_model->read($client);
            if ($data === false) {
                $res['code'] = App_Code::GET_MENU_FAILED;
                $res['msg']  = App_Msg::GET_MENU_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['req']) && $client['req'] === 'id_title_pid') {
            $data = $this->menu_model->select_by_req('id, title, pid');
            if ($data === false) {
                $res['code'] = App_Code::GET_MENU_FAILED;
                $res['msg']  = App_Msg::GET_MENU_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        if (isset($client['id'])) {
            $data = $this->menu_model->read_by_id($client['id']);
            if ($data === false) {
                $res['code'] = App_Code::GET_MENU_FOR_EDIT_FAILED;
                $res['msg']  = App_Msg::GET_MENU_FOR_EDIT_FAILED;
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

        // $result = $this->menu_model->read($select_col, $method, $cond, $cond_col);
        // $res    = $this->common_tools->arr2tree($result);
        // $this->response($res, 200);
    }

    public function index_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_menu', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $data['type']      = $client['type'];
        $data['name']      = $client['name'];
        $data['path']      = $client['path'];
        $data['component'] = $client['component'];
        $data['redirect']  = $client['redirect'];
        $data['title']     = $client['title'];
        $data['icon']      = $client['icon'];
        $data['roles']     = $client['roles'];
        $data['sort']      = $client['sort'];
        $data['pid']       = $client['pid'];

        $data['hidden']     = (int) $client['hidden'];
        $data['alwaysShow'] = (int) $client['alwaysShow'];
        $data['noCache']    = (int) $client['noCache'];
        $data['breadcrumb'] = (int) $client['breadcrumb'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        $id = $this->menu_model->create($data);
        if ($id === false) {
            $res['code'] = App_Code::CREATE_MENU_FAILED;
            $res['msg']  = App_Msg::CREATE_MENU_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 201);

        // $data['type']      = $this->post('type');
        // $data['name']      = $this->post('name');
        // $data['path']      = $this->post('path');
        // $data['component'] = $this->post('component');
        // $data['redirect']  = $this->post('redirect');
        // // $data['hidden'] = $this->post('hidden');
        // // $data['alwaysShow'] = $this->post('alwaysShow');
        // $data['title'] = $this->post('title');
        // $data['icon']  = $this->post('icon');
        // // $data['noCache'] = $this->post('noCache');
        // // $data['breadcrumb'] = $this->post('breadcrumb');
        // $data['roles'] = $this->post('roles');
        // $data['sort']  = $this->post('sort');
        // $data['pid']   = $this->post('pid');

        // $data['hidden']     = ($this->post('hidden') === '1') ? 1 : 0;
        // $data['alwaysShow'] = ($this->post('alwaysShow') === '1') ? 1 : 0;
        // $data['noCache']    = ($this->post('noCache') === '1') ? 1 : 0;
        // $data['breadcrumb'] = ($this->post('breadcrumb') === '1') ? 1 : 0;

        // $data['update_time'] = date("Y-m-d H:i:s", time());

        // $result = $this->menu_model->create($data);
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

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_menu', 'index_post');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id                = $client['id'];
        $data['type']      = $client['type'];
        $data['name']      = $client['name'];
        $data['path']      = $client['path'];
        $data['component'] = $client['component'];
        $data['redirect']  = $client['redirect'];
        $data['title']     = $client['title'];
        $data['icon']      = $client['icon'];
        $data['roles']     = $client['roles'];
        $data['sort']      = $client['sort'];
        $data['pid']       = $client['pid'];

        $data['hidden']     = (int) $client['hidden'];
        $data['alwaysShow'] = (int) $client['alwaysShow'];
        $data['noCache']    = (int) $client['noCache'];
        $data['breadcrumb'] = (int) $client['breadcrumb'];

        $data['update_time'] = date("Y-m-d H:i:s", time());

        // post和put 共用一份输入验证规则
        if ($id === '') {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = App_Msg::PARAMS_INVALID;

            $this->response($res, 200);
        }

        $rtn = $this->menu_model->update($id, $data);
        if ($rtn === false) {
            $res['code'] = App_Code::UPDATE_MENU_FAILED;
            $res['msg']  = App_Msg::UPDATE_MENU_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 200);
        // $id                = $this->put('id');
        // $data['type']      = $this->put('type');
        // $data['name']      = $this->put('name');
        // $data['path']      = $this->put('path');
        // $data['component'] = $this->put('component');
        // $data['redirect']  = $this->put('redirect');
        // // $data['hidden'] = $this->put('hidden');
        // // $data['alwaysShow'] = $this->put('alwaysShow');
        // $data['title'] = $this->put('title');
        // $data['icon']  = $this->put('icon');
        // // $data['noCache'] = $this->put('noCache');
        // // $data['breadcrumb'] = $this->put('breadcrumb');
        // $data['roles'] = $this->put('roles');
        // $data['sort']  = $this->put('sort');
        // $data['pid']   = $this->put('pid');

        // $data['hidden']     = ($this->put('hidden') === '1') ? 1 : 0;
        // $data['alwaysShow'] = ($this->put('alwaysShow') === '1') ? 1 : 0;
        // $data['noCache']    = ($this->put('noCache') === '1') ? 1 : 0;
        // $data['breadcrumb'] = ($this->put('breadcrumb') === '1') ? 1 : 0;

        // $data['update_time'] = date("Y-m-d H:i:s", time());

        // $result = $this->menu_model->update($id, $data);
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

        $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_menu', 'index_delete');
        if ($valid !== true) {
            $res['code'] = App_Code::PARAMS_INVALID;
            $res['msg']  = $valid;
            $this->response($res, 200);
        }

        $id = $client['id'];

        $ids = $this->menu_model->get_all_children_ids($id);
        $rtn = $this->menu_model->delete($ids);
        if ($rtn === true) {
            $res['code'] = App_Code::SUCCESS;
            $res['msg']  = App_Msg::SUCCESS;
        } else {
            $res['code'] = App_Code::DELETE_MENU_FAILED;
            $res['msg']  = App_Msg::DELETE_MENU_FAILED;
            SeasLog::error('APP_code: ' . $res['code'] . ' - ' . $res['msg']);
        }

        $this->response($res, 200);
        // $id = $this->delete('id');

        // $ids    = $this->menu_model->get_all_children_ids($id);
        // $result = $this->menu_model->delete($ids);

        // $this->response($result, 200);
    }
}
