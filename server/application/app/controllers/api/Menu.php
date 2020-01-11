<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 16:50:49
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
		$select_col = $this->get('select_col');
		$method = $this->get('method');
		$cond = $this->get('cond');
		$cond_col = $this->get('cond_col');

		$result = $this->menu_model->read($select_col, $method, $cond, $cond_col);
		$res = $this->common_tools->arr2tree($result);
		$this->response($res, 200);
	}

	public function index_post()
	{
		$data['type'] = $this->post('type');
		$data['name'] = $this->post('name');
		$data['path'] = $this->post('path');
		$data['component'] = $this->post('component');
		$data['redirect'] = $this->post('redirect');
		// $data['hidden'] = $this->post('hidden');
		// $data['alwaysShow'] = $this->post('alwaysShow');
		$data['title'] = $this->post('title');
		$data['icon'] = $this->post('icon');
		// $data['noCache'] = $this->post('noCache');
		// $data['breadcrumb'] = $this->post('breadcrumb');
		$data['roles'] = $this->post('roles');
		$data['sort'] = $this->post('sort');
		$data['pid'] = $this->post('pid');

		$data['hidden'] = ($this->post('hidden') === '1') ? 1 : 0;
		$data['alwaysShow'] = ($this->post('alwaysShow') === '1') ? 1 : 0;
		$data['noCache'] = ($this->post('noCache') === '1') ? 1 : 0;
		$data['breadcrumb'] = ($this->post('breadcrumb') === '1') ? 1 : 0;

		$data['update_time'] = date("Y-m-d H:i:s", time());

		$result = $this->menu_model->create($data);
		if ($result === FALSE)
		{
			$this->response([], 500);
		}
		else
		{
			$this->response($result, 201);
		}
		
	}

	public function index_put()
	{
		$id = $this->put('id');
		$data['type'] = $this->put('type');
		$data['name'] = $this->put('name');
		$data['path'] = $this->put('path');
		$data['component'] = $this->put('component');
		$data['redirect'] = $this->put('redirect');
		// $data['hidden'] = $this->put('hidden');
		// $data['alwaysShow'] = $this->put('alwaysShow');
		$data['title'] = $this->put('title');
		$data['icon'] = $this->put('icon');
		// $data['noCache'] = $this->put('noCache');
		// $data['breadcrumb'] = $this->put('breadcrumb');
		$data['roles'] = $this->put('roles');
		$data['sort'] = $this->put('sort');
		$data['pid'] = $this->put('pid');

		$data['hidden'] = ($this->put('hidden') === '1') ? 1 : 0;
		$data['alwaysShow'] = ($this->put('alwaysShow') === '1') ? 1 : 0;
		$data['noCache'] = ($this->put('noCache') === '1') ? 1 : 0;
		$data['breadcrumb'] = ($this->put('breadcrumb') === '1') ? 1 : 0;

		$data['update_time'] = date("Y-m-d H:i:s", time());

		$result = $this->menu_model->update($id, $data);
		if ($result === FALSE)
		{
			$this->response([], 500);
		}
		else
		{
			$this->response($result, 200);
		}
	}

	public function index_delete()
	{
		$id = $this->delete('id');

		$ids = $this->menu_model->get_all_children_ids($id);
		$result = $this->menu_model->delete($ids);

		$this->response($result, 200);
	}
}
