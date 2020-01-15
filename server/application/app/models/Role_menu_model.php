<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-15 22:20:38
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_menu_model extends CI_Model {

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

		$query = $this->db->get($this->tables['roles_menus']);
		$result = $query->result_array();

        return $result;
	}

	public function create($data)
    {
		return $this->db->insert($this->tables['roles_menus'], $data);
	}
	
	// public function update($id, $data)
    // {
	// 	$this->db->where('id', $id);
	// 	$this->db->update($this->tables['roles_menus'], $data);

	// 	$res = $this->db->affected_rows();
	// 	return ($res > 0) ? TRUE : FALSE;
	// }

	/**
     * 
     * @param int  $id
     * @return array
     */
	public function read_by_role($role_id)
    {
		$result = $this->db->where('role_id', $role_id)->get($this->tables['roles_menus'])->result_array();
		
		return $result;
	}
	
	/**
     * 
     * @param int  $id
     * @return 
     */
	public function delete_by_role($role_id)
    {
		$result = $this->db->where('role_id', $role_id)->delete($this->tables['roles_menus']);
		
		return $result;
	}
	
}
