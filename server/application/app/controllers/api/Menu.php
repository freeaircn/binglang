<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-01 20:45:48
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Menu extends RestController {

	function __construct()
    {
        // Construct the parent class
		parent::__construct();
		
		$this->load->model('menu_model');
	}

	public function index_get()
	{
		$key = $this->get( 'key' );

		$list = $this->menu_model->tree_list();

		if($list)
		{
			$this->response( $list, 200 );
		}
		else
		{
			$this->response( [], 404 );
		}
		
	}

	public function index_post()
	{
		$id = $this->post( 'id' );

		$data = ['id' => $id];
		$this->response( $data, 201 );
	}

	public function index_put()
	{
		$id = $this->put( 'id' );

		$data = ['id' => $id];
		$this->response( $data, 200 );
	}

	public function index_delete()
	{
		$id = $this->delete( 'id' );

		$data = ['id' => $id];
		$this->response( $data, 200 );
	}
}
