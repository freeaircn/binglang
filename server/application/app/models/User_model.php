<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-17 09:15:42
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	protected $db;

	public $tables = [];

	public function __construct()
    {
        parent::__construct();
		// Your own constructor code
		$this->config->load('app_config', TRUE);
		$db_name = $this->config->item('db_name', 'app_config');
		$this->tables = $this->config->item('tables', 'app_config');

		$this->load->library('common_tools');

		if (empty($db_name))
		{
			$CI =& get_instance();
			$this->db = $CI->db;
		}
		else
		{
			$this->db = $this->load->database($db_name, TRUE, TRUE);
		}
	}
	
	public function db()
	{
		return $this->db;
	}

	public function read($select_col = NULL, $method = NULL, $cond = NULL, $cond_col = NULL)
    {
		$this->db->order_by('id', 'ASC');

		if (!empty($select_col))
		{
			$this->db->select($select_col);
		}

		if (!empty($method))
		{
			if ($method === 'where' && (!empty($cond)))
			{
				$this->db->where($cond);
			}
			if ($method === 'like' && (!empty($cond)))
			{
				$this->db->like($cond);
			}
			if ($method === 'where_in' && (!empty($cond)) && (!empty($cond_col)))
			{
				$this->db->where_in($cond_col, $cond);
			}

		}

		$query = $this->db->get($this->tables['user']);
		$result = $query->result_array();
		
        return $result;
	}
	
	public function create($data)
    {
		$this->db->insert($this->tables['user'], $data);
		$id = $this->db->insert_id($this->tables['user'] . '_id_seq');

		return (isset($id)) ? $id : FALSE;
	}
	
	public function update($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update($this->tables['user'], $data);

		$res = $this->db->affected_rows();
		return ($res > 0) ? TRUE : FALSE;
	}

	/**
     * 
     * @param array $id
     * @return array
     */
	public function delete($id)
    {
		// $result = $this->db->where_in('id', $ids)->delete($this->tables['user']);
		$result = $this->db->where('id', $id)->delete($this->tables['user']);
		
		return $result;
	}

	/**
     * 准备user 信息空表单
     * 读app_dict, app_dict_data，app_role，app_dept, app_job
     * @return array
     */
	public function prepare_new_form()
	{
		// 检索dict表name字段含user_attr_，得到dict.id, dict.label
		$dict = $this->db->select('id, label')
				->like('name', 'user_attr_')
				->get($this->tables['dict'])
				->result_array();

		// 通过dict.id 检索dict_data 表，得到dict_data.id, dict_data.label
		$extra_attribute = [];
		foreach ($dict as $v)
		{
			$dict_data = $this->db->select('id, label')
						->where('dict_id', $v['id'])
						->get($this->tables['dict_data'])
						->result_array();
			$extra_attribute[] = array
			(
				"attribute_label" => $v['label'],
				"attribute_values" => $dict_data
			);
		}
		
		// 检索app_role 表，得到role.id, role.label
		$role = $this->db->select('id, label')
					->get($this->tables['role'])
					->result_array();
		
		// 检索app_dept 得到dept.id, dept.label tree
		$dept_temp = $this->db->select('id, label, pid')
					->get($this->tables['dept'])
					->result_array();
		$dept = $this->common_tools->arr2tree($dept_temp);

		// 检索app_job 得到job.id, job.label
		$job = $this->db->select('id, label')
					->get($this->tables['job'])
					->result_array();
		
		$res = [];
		$res[] = array
		(
			"extra_attribute" => $extra_attribute,
			"dept" => $dept,
			"job" => $job,
			"role" => $role
		);

		return $res;
	}
	
	/**
     * 根据id便利数据表，输出包括输入id的所有子节点id
     * @param int $id
     * @return array string
     */
	// function get_all_children_ids($id)
	// {
	// 	$array[] = (string)$id;
	// 	$temp_arr[] = (string)$id;
	// 	do
	// 	{
	// 		$this->db->select('id');
	// 		$this->db->where_in('pid', $temp_arr);
	// 		$query = $this->db->get($this->tables['user']);
	// 		$res = $query->result_array();
	// 		unset($temp_arr);
	// 		foreach ($res as $k=>$v)
	// 		{
	// 			$array[] = (string)$v['id'];
	// 			$temp_arr[] = (string)$v['id'];
	// 		}
	// 	}
	// 	while (!empty($res));

	// 	return $array;
	// }
}
