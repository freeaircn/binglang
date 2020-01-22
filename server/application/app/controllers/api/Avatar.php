<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-22 23:10:17
 */
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use \App_Settings\App_Code as App_Code;

class Avatar extends RestController
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // $this->load->model('user_model');
        // $this->load->library('common_tools');
    }

    public function update_post()
    {
        $config['upload_path']   = './resource/avatar/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = 100;
        $config['max_width']     = 1024;
        $config['max_height']    = 768;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $res['code'] = 300;
            $res['msg']  = $this->upload->display_errors();

        } else {
            $res['code'] = App_Code::SUCCESS;
            $res['data'] = $this->upload->data();
        }

        $this->response($res, 200);
    }

}
