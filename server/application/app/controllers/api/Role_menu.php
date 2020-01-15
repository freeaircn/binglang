<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-15 22:46:59
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Role_menu extends RestController {

	function __construct()
    {
        // Construct the parent class
		parent::__construct();
		
		$this->load->model('role_menu_model');
		// $this->load->library('common_tools');
	}

	public function index_get()
	{
		$select_col = $this->get('select_col');
		$method = $this->get('method');
		$cond = $this->get('cond');
		$cond_col = $this->get('cond_col');

		$result = $this->role_menu_model->read($select_col, $method, $cond, $cond_col);

		$this->response($result, 200);
	}

	public function index_post()
	{
		$role_id = $this->post('role_id');
		$menus = $this->post('menus');

		do
		{
			$this->role_menu_model->delete_by_role($role_id);
			$arr = $this->role_menu_model->read_by_role($role_id);
		}
		while (!empty($arr));

		$cnt = count($menus);
		$i = 0;
		foreach ($menus as $k=>$v)
		{
			$data = array(
				'role_id' => $role_id,
				'menu_id' => $v
			);

			do
			{
				$res = $this->role_menu_model->create($data);
			}
			while (!$res);
			$i++;
		}

		$result = ($i == $cnt) ? TRUE:FALSE;

		if ($result === FALSE)
		{
			$this->response([], 500);
		}
		else
		{
			$this->response($result, 201);
		}
	}
}
