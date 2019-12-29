<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-29 16:01:40
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus_api extends CI_Controller {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();
	}
	
	public function post()
	{
		$msg = $this->input->post('msg');

		$data = ['msg' => $msg, 'method' => 'post'];
		$res = json_encode($data);
		echo ($res);
		return ;
	}

}
