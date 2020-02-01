<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 22:35:16
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
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
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
            $this->db->where_in('a.id', $this->common_tools->get_sql_ci_where_in_by_ci_result($temp_id, 'id'));
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
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result = $query->result_array();

        $res['dict_data']  = $result;
        $res['total_rows'] = $total_rows;

        return $res;
    }

    public function create($data)
    {
        $this->db->insert($this->tables['dict_data'], $data);
        $id = $this->db->insert_id($this->tables['dict_data'] . '_id_seq');

        return (isset($id)) ? $id : false;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->tables['dict_data'], $data);

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
        // $result = $this->db->where_in('id', $ids)->delete($this->tables['dict_data']);
        $result = $this->db->where('id', $id)->delete($this->tables['dict_data']);

        return $result;
    }

    /**
     * makeup where sub-statement of sql by client data
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param [associated array] $client
     * @return mixed bool | string , true indicates no data in db table
     */

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

        return $where_str;
    }
}
