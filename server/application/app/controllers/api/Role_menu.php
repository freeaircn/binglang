<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-13 21:10:16
 */
defined('BASEPATH') or exit('No direct script access allowed');

use \App_Settings\App_Code as App_Code;
use \App_Settings\App_Msg as App_Msg;
use \App_Settings\APP_Rest_API as APP_Rest_API;

class Role_menu extends APP_Rest_API
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('role_menu_model');
        $this->load->library('common_tools');
    }

    public function index_get()
    {
        $client = $this->get();

        // $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_role_menu', 'index_get');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        if (isset($client['req']) && $client['req'] === 'menu' && isset($client['id'])) {
            $data = $this->role_menu_model->select_menu_by_role($client['id']);
            if ($data === false) {
                $res['code'] = App_Code::GET_MENU_FAILED;
                $res['msg']  = App_Msg::GET_MENU_FAILED;
            } else {
                $res['code'] = App_Code::SUCCESS;
                $res['data'] = $data;
            }
            $this->response($res, 200);
        }

        $res['code'] = App_Code::REQ_DATA_NOT_EXIST;
        $res['msg']  = App_Msg::REQ_DATA_NOT_EXIST;
        $this->response($res, 200);
    }

    public function index_post()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $client       = json_decode($stream_clean, true);

        // $valid = $this->common_tools->valid_client_data($client, 'client_validation/api_role_menu', 'index_post');
        // if ($valid !== true) {
        //     $res['code'] = App_Code::PARAMS_INVALID;
        //     $res['msg']  = $valid;
        //     $this->response($res, 200);
        // }

        $role_id = $client['role_id'];
        $menu    = $client['menu'];

        $rtn = $this->role_menu_model->create_process($role_id, $menu);

        if ($rtn === false) {
            $res['code'] = App_Code::CREATE_MENU_FAILED;
            $res['msg']  = App_Msg::CREATE_MENU_FAILED;
            $this->common_tools->app_log('error', "CREATE_MENU_FAILED");

            $this->response($res, 200);
        }

        $res['code'] = App_Code::SUCCESS;
        $res['msg']  = App_Msg::SUCCESS;

        $this->response($res, 201);
    }
}
