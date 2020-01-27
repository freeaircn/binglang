<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-27 09:55:03
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Job_model extends CI_Model
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
    }

    public function db()
    {
        return $this->db;
    }

    public function read($select_col = null, $method = null, $cond = null, $cond_col = null)
    {
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('id', 'ASC');

        if ($select_col !== null) {
            $this->db->select($select_col);
        }

        if ($method !== null) {
            if ($method === 'where' && (!empty($cond))) {
                $this->db->where($cond);
            }
            if ($method === 'like' && (!empty($cond))) {
                $this->db->like($cond);
            }
            if ($method === 'where_in' && (!empty($cond)) && (!empty($cond_col))) {
                $this->db->where_in($cond_col, $cond);
            }

        }

        $query  = $this->db->get($this->tables['job']);
        $result = $query->result_array();

        return $result;
    }

    public function create($data)
    {
        $this->db->insert($this->tables['job'], $data);
        $id = $this->db->insert_id($this->tables['job'] . '_id_seq');

        return (isset($id)) ? $id : false;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->tables['job'], $data);

        $res = $this->db->affected_rows();
        return ($res > 0) ? true : false;
    }

    /**
     *
     * @param array $id
     * @return array
     */
    public function delete($id)
    {
        // $result = $this->db->where_in('id', $ids)->delete($this->tables['job']);
        $result = $this->db->where('id', $id)->delete($this->tables['job']);

        return $result;
    }
}
