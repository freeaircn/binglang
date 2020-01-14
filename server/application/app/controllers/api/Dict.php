<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-29 14:06:12
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-14 21:11:57
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Dict extends RestController {

	function __construct()
    {
        // Construct the parent class
		parent::__construct();
		
		$this->load->model('dict_model');
		// $this->load->library('common_tools');
	}

	public function index_get()
	{
		$select_col = $this->get('select_col');
		$method = $this->get('method');
		$cond = $this->get('cond');
		$cond_col = $this->get('cond_col');

		$res = $this->dict_model->read($select_col, $method, $cond, $cond_col);
		// foreach ($res as &$item)
		// {
		// 	$dept_id = $item['dept_id'];
		// 	$dept_arr = $this->dept_model->get_all_parents_label($dept_id);
		// 	$dept = '';
		// 	for ($i = count($dept_arr)-1; $i >= 0; $i--)
		// 	{
		// 		$dept = $dept . ' / ' . $dept_arr[$i];
		// 	}
		// 	$dept = substr($dept, 3, strlen($dept));
		// 	$item['dept'] = $dept;
		// }
		// $res = $this->common_tools->arr2tree($result);
		$this->response($res, 200);
	}

	public function index_post()
	{
		$data['sort'] = $this->post('sort');
		$data['label'] = $this->post('label');
		$data['type'] = $this->post('type');
		$data['enabled'] = ($this->post('enabled') === '1') ? 1 : 0;
		
		$data['update_time'] = date("Y-m-d H:i:s", time());

		$result = $this->dict_model->create($data);
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
		$data['sort'] = $this->put('sort');
		$data['label'] = $this->put('label');
		$data['type'] = $this->put('type');
		$data['enabled'] = ($this->put('enabled') === '1') ? 1 : 0;
		
		$data['update_time'] = date("Y-m-d H:i:s", time());
		
		$result = $this->dict_model->update($id, $data);
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
		// $ids = (string)$id;

		// $ids = $this->dict_model->get_all_children_ids($id);
		$result = $this->dict_model->delete($id);

		$this->response($result, 200);
	}
}
