<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 15:48:06
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Dept extends RestController {

	function __construct()
    {
        // Construct the parent class
		parent::__construct();
		
		$this->load->model('dept_model');
		$this->load->library('common_tools');
	}

	public function index_get()
	{
		$select_col = $this->get('select_col');
		$method = $this->get('method');
		$cond = $this->get('cond');
		$cond_col = $this->get('cond_col');

		$result = $this->dept_model->read($select_col, $method, $cond, $cond_col);
		$res = $this->common_tools->arr2tree($result);
		$this->response($res, 200);
	}

	public function index_post()
	{
		$data['label'] = $this->post('label');
		$data['pid'] = $this->post('pid');
		$data['enabled'] = ($this->post('enabled') === '1') ? 1 : 0;
		
		$data['update_time'] = date("Y-m-d H:i:s", time());

		$result = $this->dept_model->create($data);
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
		$data['label'] = $this->put('label');
		$data['pid'] = $this->put('pid');
		$data['enabled'] = ($this->put('enabled') === '1') ? 1 : 0;

		$data['update_time'] = date("Y-m-d H:i:s", time());
		
		$result = $this->dept_model->update($id, $data);
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

		$ids = $this->dept_model->get_all_children_ids($id);
		$result = $this->dept_model->delete($ids);

		$this->response($result, 200);
	}
}
