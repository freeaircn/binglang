<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-06 20:39:53
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Menu extends RestController {

	function __construct()
    {
        // Construct the parent class
		parent::__construct();
		
		$this->load->model('menu_model');
		$this->load->library('common_tools');
	}

	public function index_get()
	{
		$words = $this->get( 'words' );

		$result = $this->menu_model->read_all($words);

		if(empty($result))
		{
			$this->response( [], 404 );
		}
		else
		{
			$res = $this->common_tools->arr2tree($result);
			$this->response( $res, 200 );
		}
		
	}

	public function index_post()
	{
		$data['name'] = $this->post( 'name' );
		$data['type'] = $this->post( 'type' );
		$data['pid'] = $this->post( 'pid' );
		$data['sort'] = $this->post( 'sort' );
		$data['permission'] = $this->post( 'permission' );
		$data['component'] = $this->post( 'component' );
		$data['component_name'] = $this->post( 'component_name' );
		$data['path'] = $this->post( 'path' );
		$data['icon'] = $this->post( 'icon' );
		$data['cache'] = $this->post( 'cache' );
		$data['hidden'] = $this->post( 'hidden' );
		$data['outlink'] = $this->post( 'outlink' );

		if ($data['cache'] == 'true') {
            $data['cache'] = 1;
        } else {
            $data['cache'] = 0;
		}
		if ($data['hidden'] == 'true') {
            $data['hidden'] = 1;
        } else {
            $data['hidden'] = 0;
		}
		if ($data['outlink'] == 'true') {
            $data['outlink'] = 1;
        } else {
            $data['outlink'] = 0;
		}

		$data['create_time'] = date("Y-m-d H:i:s", time());
		$result = $this->menu_model->create_one($data);

		if ($result === FALSE) {
			$this->response( [], 500 );
		} else {
			$this->response( $result, 201 );
		}
		
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
