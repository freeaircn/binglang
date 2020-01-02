<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-02 13:36:12
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
		$this->load->library('common_tools');
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
    public function tree_list($words=null)
    {
		$this->db->order_by('sort', 'DESC');
		$this->db->order_by('id', 'ASC');
		if($words === null)
		{
			$query = $this->db->get($this->tables['menu']);
		}
		else
		{
			$this->db->like('name', $words);
			$query = $this->db->get($this->tables['menu']);
		}
		
		$list = $query->result_array();
		if(empty($list))
		{
			return $list;
		}
        if ($list) {
            foreach ($list as &$item) {
                $item['cache'] = !!$item['cache'];
				$item['hidden'] = !!$item['hidden'];
				$item['outlink'] = !!$item['outlink'];
                unset($item);
            }
        }
		$list = $this->common_tools->arr2tree($list);
        return $list;
    }

}
