<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-23 17:28:05
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
        $config['upload_path']   = FCPATH . '/resource/avatar/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = 100;
        $config['max_width']     = 1024;
        $config['max_height']    = 768;
        // $config['encrypt_name']  = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $res['code'] = 300;
            $res['msg']  = $this->upload->display_errors();

        } else {
            $res['code'] = App_Code::SUCCESS;
            $res['data'] = $this->upload->data();
        }
        $res['path'] = [
            'SELF'     => SELF,
            'BASEPATH' => BASEPATH,
            'FCPATH'   => FCPATH,
            'SYSDIR'   => SYSDIR,
            'APPPATH'  => APPPATH,
        ];

        $this->response($res, 200);
    }

}
