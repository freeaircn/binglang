<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 23:17:49
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Dept_model extends CI_Model {

	protected $db;

	public $tables = [];

	public function __construct()
    {
        parent::__construct();
		// Your own constructor code
		$this->config->load('app_config', TRUE);
		$db_name = $this->config->item('db_name', 'app_config');
		$this->tables = $this->config->item('tables', 'app_config');

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

		if ($select_col !== NULL)
		{
			$this->db->select($select_col);
		}

		if ($method !== NULL)
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

		$query = $this->db->get($this->tables['dept']);
		$result = $query->result_array();
		
        return $result;
	}
	
	public function create($data)
    {
		$this->db->insert($this->tables['dept'], $data);
		$id = $this->db->insert_id($this->tables['dept'] . '_id_seq');

		return (isset($id)) ? $id : FALSE;
	}
	
	public function update($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update($this->tables['dept'], $data);

		$res = $this->db->affected_rows();
		return ($res > 0) ? TRUE : FALSE;
	}

	/**
     * 
     * @param array string $ids
     * @return array
     */
	public function delete($ids)
    {
		$result = $this->db->where_in('id', $ids)->delete($this->tables['dept']);
		
		return $result;
	}
	
	/**
     * 根据id遍历数据表，输出包括输入id的所有子节点id
     * @param int $id
     * @return array string
     */
	function get_all_children_ids($id)
	{
		$array[] = (string)$id;
		$temp_arr[] = (string)$id;
		do
		{
			$this->db->select('id');
			$this->db->where_in('pid', $temp_arr);
			$query = $this->db->get($this->tables['dept']);
			$res = $query->result_array();
			unset($temp_arr);
			foreach ($res as $k=>$v)
			{
				$array[] = (string)$v['id'];
				$temp_arr[] = (string)$v['id'];
			}
		}
		while (!empty($res));

		return $array;
	}

	/**
     * 根据id遍历数据表，输出包括输入id的所有父节点label
     * @param int $id
     * @return array string
     */
	function get_all_parents_label($id)
	{
		// $array[] = (string)$id;
		$array = [];
		$temp_arr[] = (string)$id;
		do
		{
			$this->db->select('label, pid');
			$this->db->where_in('id', $temp_arr);
			$query = $this->db->get($this->tables['dept']);
			$res = $query->result_array();
			unset($temp_arr);
			foreach ($res as $k=>$v)
			{
				$array[] = $v['label'];
				$temp_arr[] = (string)$v['pid'];
			}
		}
		while (!empty($res));

		return $array;
	}
}
