<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-02 13:37:59
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
		$words = $this->get( 'words' );

		$list = $this->menu_model->tree_list($words);

		if(empty($list))
		{
			$this->response( [], 404 );
		}
		else
		{
			$this->response( $list, 200 );
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
