<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-13 21:18:35
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Dict_data_model extends CI_Model
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
     * read data in table
     *
     * @author freeair
     * @DateTime 2020-02-01
     * @param [associative array] $client
     * @return void
     */
    public function read($client)
    {
        $where_str = $this->_get_sql_where($client);
        // $where_str = '';

        $this->db->select('id');
        if ($where_str !== '') {
            $this->db->where($where_str);
        }
        // 查询一次，获取total
        $query = $this->db->get($this->tables['dict_data']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $total_rows = $query->num_rows();
        if ($total_rows === 0) {
            $res['dict_data']  = [];
            $res['total_rows'] = 0;

            return $res;
        }

        // 查询结果>0，再查询一次，使用了join子句
        $this->db->select('a.*, b.label as dict_label');
        $this->db->from($this->tables['dict_data'] . ' a');
        $this->db->join($this->tables['dict'] . ' b', 'a.dict_id = b.id', 'inner');

        if ($where_str !== '') {
            $temp_id = $query->result_array();
            $this->db->where_in('a.id', $this->common_tools->get_one_item_from_ci_result_array($temp_id, 'id'));
        }
        $this->db->order_by('a.sort', 'ASC');
        $this->db->order_by('a.id', 'ASC');

        if (isset($client['limit']) && $client['limit'] !== '') {
            $limit_temp = explode('_', $client['limit']);
            $num        = (int) $limit_temp[0];
            $offset     = (int) $limit_temp[1];
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get();

        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result = $query->result_array();

        $res['dict_data']  = $result;
        $res['total_rows'] = $total_rows;

        return $res;
    }

    /**
     * read_by_id
     *
     * @author freeair
     * @DateTime 2020-02-02
     * @param [type] $id
     * @return mixed bool | array
     */
    public function read_by_id($id = null)
    {
        if (!isset($id) || $id == 0) {
            return false;
        }

        // $this->db->select('id, sort, label, name, enabled');
        $this->db->where('id', $id);
        $query = $this->db->get($this->tables['dict_data']);

        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        // no data in db
        if ($query->num_rows() === 0) {
            return false;
        }
        $res['form'] = $query->result_array()[0];

        return $res;
    }

    /**
     * insert
     *
     * @author freeair
     * @DateTime 2020-02-02
     * @param [array] $data
     * @return void
     */
    public function create($data)
    {
        if (empty($data)) {
            return true;
        }

        $id = false;
        if (!$this->db->insert($this->tables['dict_data'], $data)) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
        } else {
            $id = $this->db->insert_id($this->tables['dict_data'] . '_id_seq');
        }

        return $id;
    }

    /**
     * update
     *
     * @author freeair
     * @DateTime 2020-02-02
     * @param [int] $id
     * @param [array] $data
     * @return bool
     */
    public function update($id, $data)
    {
        if (empty($id) || empty($data)) {
            return true;
        }

        if ($this->db->where('id', $id)->update($this->tables['dict_data'], $data) === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        } else {
            return true;
        }
    }

    /**
     * delete
     *
     * @author freeair
     * @DateTime 2020-02-02
     * @param [int] $id
     * @return bool
     */
    public function delete($id)
    {
        if (!isset($id)) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id', $id)->delete($this->tables['dict_data']);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * makeup where sub-statement of sql by client data
     *
     * @author freeair
     * @DateTime 2020-02-01
     * @param [associated array] $client
     * @return mixed bool | string , true indicates no data in db table
     */
    protected function _get_sql_where($client = [])
    {
        if (empty($client)) {
            return '';
        }

        $where_str = '';
        $continue  = false;
        if (isset($client['label']) && $client['label'] !== '') {
            $where_str .= "label LIKE '%" . $client['label'] . "%' ";
            $continue = true;
        }
        if (isset($client['name']) && $client['name'] !== '') {
            if ($continue) {
                $where_str .= " AND ";
                $where_str .= "name LIKE '%" . $client['name'] . "%' ";
            } else {
                $where_str .= "name LIKE '%" . $client['name'] . "%' ";
            }
            $continue = true;
        }
        if (isset($client['dict']) && $client['dict'] !== '') {
            if ($continue) {
                $where_str .= " AND ";
                $where_str .= "dict_id =" . $client['dict'];
            } else {
                $where_str .= "dict_id =" . $client['dict'];
            }
            $continue = true;
        }

        return $where_str;
    }
}
