<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-13 21:15:34
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends CI_Model
{

    protected $db;

    public $tables = [];

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->config->load('app_config', true);
        $db_name      = $this->config->item('db_name', 'app_config');
        $this->tables = $this->config->item('tables', 'app_config');

        if (empty($db_name)) {
            $CI       = &get_instance();
            $this->db = $CI->db;
        } else {
            $this->db = $this->load->database($db_name, true, true);
        }

        $this->load->library('common_tools');
    }

    public function db()
    {
        return $this->db;
    }

    /**
     * 1 获取用户信息list表
     *
     * @author freeair
     * @DateTime 2020-11-10
     * @return mixed array | bool
     */
    public function get_form_list()
    {
        $res = [];

        // 1 查找部门list，合成树形结构
        $query = $this->db->select('id, label, pid')
            ->order_by('id', 'ASC')
            ->get($this->tables['dept']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $dept_temp = $query->result_array();
        $dept_list = $this->common_tools->arr2tree($dept_temp);

        // 2 查找岗位list
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['job']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $job_list = $query->result_array();

        // 3 查找政治面貌list
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['politic']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $politic_list = $query->result_array();

        // 4 查找职称list
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['professional_title']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $professional_title_list = $query->result_array();

        $res['dept_list']               = $dept_list;
        $res['job_list']                = $job_list;
        $res['politic_list']            = $politic_list;
        $res['professional_title_list'] = $professional_title_list;

        return $res;
    }

    /**
     * 2 更新用户基本信息
     *
     * @author freeair
     * @DateTime 2020-11-11
     * @param int $phone
     * @param [array] $data
     * @return bool
     */
    public function update_user_basic_info($phone = null, $data = null)
    {
        if (empty($phone)) {
            return true;
        }

        $this->db->trans_start();
        if ($this->db->where('phone', $phone)->update($this->tables['user'], $data) === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }
}
