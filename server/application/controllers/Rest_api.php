<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-29 17:39:49
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Rest_api extends RestController {

	function __construct()
    {
        // Construct the parent class
        parent::__construct();
	}

	public function index_post()
	{
		$msg = $this->post( 'msg' );

		$data = ['msg' => $msg, 'method' => 'post'];
		$this->response( $data, 201 );
	}
}
