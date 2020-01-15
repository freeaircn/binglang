<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-15 13:33:04
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Dict_data_model extends CI_Model {

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
		$this->db->order_by('sort', 'ASC');
		$this->db->order_by('id', 'ASC');

		if (!empty($select_col))
		{
			$this->db->select($select_col . ', b.label as dict_label');
		}
		else
		{
			$this->db->select('a.*, b.label as dict_label');
		}

		$this->db->from($this->tables['dict_data'] . ' a');
		$this->db->join($this->tables['dict'] . ' b', 'a.dict_id = b.id', 'inner');

		if (!empty($method))
		{
			if ($method === 'where' && (!empty($cond)))
			{
				foreach ($cond as $k=>$v)
				{
					$this->db->where('a.' . $k, $v);
				}
				// $this->db->where($cond);
			}
			if ($method === 'like' && (!empty($cond)))
			{
				foreach ($cond as $k=>$v)
				{
					$this->db->like('a.' . $k, $v);
				}
				// $this->db->like($cond);
			}
			if ($method === 'where_in' && (!empty($cond)) && (!empty($cond_col)))
			{
				$this->db->where_in('a.' . $cond_col, $cond);
			}

		}

		$query = $this->db->get();
		$result = $query->result_array();
		
        return $result;
	}
	
	public function create($data)
    {
		$this->db->insert($this->tables['dict_data'], $data);
		$id = $this->db->insert_id($this->tables['dict_data'] . '_id_seq');

		return (isset($id)) ? $id : FALSE;
	}
	
	public function update($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update($this->tables['dict_data'], $data);

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
		// $result = $this->db->where_in('id', $ids)->delete($this->tables['dict_data']);
		$result = $this->db->where('id', $id)->delete($this->tables['dict_data']);
		
		return $result;
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
	// 		$query = $this->db->get($this->tables['dict_data']);
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
