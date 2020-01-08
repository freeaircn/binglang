<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-08 22:34:10
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model {

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

	/**
     * 获取所有菜单列表
     * @return array|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function read_all()
    {
		$this->db->order_by('sort', 'ASC');
		$this->db->order_by('id', 'ASC');
		$query = $this->db->get($this->tables['menu']);
		
		$result = $query->result_array();
        if ($result) {
            foreach ($result as &$item) {
				$item['hidden'] = !!$item['hidden'];
				$item['alwaysShow'] = !!$item['alwaysShow'];
				$item['noCache'] = !!$item['noCache'];
				$item['breadcrumb'] = !!$item['breadcrumb'];
				// riophae/vue-treeselect组件，识别字段id,label,children
				$item['label'] = $item['title'];
                unset($item);
            }
        }
        return $result;
	}

	public function read_by_col($col, $words)
    {
		$this->db->order_by('sort', 'ASC');
		$this->db->order_by('id', 'ASC');

		if ($col === 'title')
		{
			$this->db->like('title', $words);
			$query = $this->db->get($this->tables['menu']);
		}

		if ($col === 'id')
		{
			$this->db->where('id', $words);
			$query = $this->db->get($this->tables['menu']);
		}
		
		$result = $query->result_array();
        if($result) {
            foreach ($result as &$item) {
				$item['hidden'] = !!$item['hidden'];
				$item['alwaysShow'] = !!$item['alwaysShow'];
				$item['noCache'] = !!$item['noCache'];
				$item['breadcrumb'] = !!$item['breadcrumb'];
                unset($item);
            }
        }
        return $result;
	}
	
	public function create($data)
    {
		$this->db->insert($this->tables['menu'], $data);
		$id = $this->db->insert_id($this->tables['menu'] . '_id_seq');

		return (isset($id)) ? $id : FALSE;
	}
	
	public function update($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update($this->tables['menu'], $data);

		$res = $this->db->affected_rows();
		return ($res > 0) ? TRUE : FALSE;
    }
}
