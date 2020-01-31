<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 21:54:52
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Dict_model extends CI_Model
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
     * @DateTime 2020-01-19
     * @param [associative array] $client
     * @return mixed bool | array
     */
    public function read($client)
    {
        $where_str = $this->_get_sql_where($client);

        $this->db->select('id');
        if ($where_str !== '') {
            $this->db->where($where_str);
        }
        $query = $this->db->get($this->tables['dict']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $total_rows = $query->num_rows();
        if ($total_rows === 0) {
            $res['dict']       = [];
            $res['total_rows'] = 0;

            return $res;
        }

        $this->db->select('id, sort, label, name, enabled, update_time');
        if ($where_str !== '') {
            $temp_id = $query->result_array();
            $this->db->where_in('id', $this->_get_sql_ci_where_in_by_ci_result($temp_id, 'id'));
        }
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('id', 'ASC');
        if (isset($client['limit']) && $client['limit'] !== '') {
            $limit_temp = explode('_', $client['limit']);
            $num        = (int) $limit_temp[0];
            $offset     = (int) $limit_temp[1];
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get($this->tables['dict']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $dict = $query->result_array();

        $res['dict']       = $dict;
        $res['total_rows'] = $total_rows;

        return $res;
    }

    /**
     * read data in table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param int $id
     * @return mixed bool | array
     */
    public function read_by_id($id = null)
    {
        if (!isset($id) || $id == 0) {
            return false;
        }

        $this->db->select('id, sort, label, name, enabled');
        $this->db->where('id', $id);
        $query = $this->db->get($this->tables['dict']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        // no data in db
        if ($query->num_rows() === 0) {
            return false;
        }
        $dict = $query->result_array()[0];

        $res['form'] = $dict;

        return $res;
    }

    /**
     * insert to table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [array] $data
     * @return [mixed] bool | id
     */
    public function create($data)
    {
        if (empty($data)) {
            return true;
        }

        $id = false;
        if (!$this->db->insert($this->tables['dict'], $data)) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        } else {
            $id = $this->db->insert_id($this->tables['dict'] . '_id_seq');
        }

        return $id;
    }

    /**
     * update table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param int $id
     * @param [array] $data
     * @return bool
     */
    public function update($id, $data)
    {
        if (empty($id)) {
            return true;
        }

        if ($this->db->where('id', $id)->update($this->tables['dict'], $data) === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        } else {
            return true;
        }
    }

    /**
     * delete from table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [int] $id
     * @return bool
     */
    public function delete($id)
    {
        if (!isset($id)) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id', $id)->delete($this->tables['dict']);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * makeup where in string of sql
     * e.g. array like ['1', '3', '4']
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param array $array - 2 dimensions
     * @param string $key
     * @return array
     */
    protected function _get_sql_ci_where_in_by_ci_result($array = [], $key = '')
    {
        if (empty($array || $key === '')) {
            return [];
        }

        $res = [];
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $res[] = (string) $item[$key];
            }
        }
        return $res;
    }

    /**
     * makeup where sub-statement of sql by client data
     *
     * @author freeair
     * @DateTime 2020-01-27
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
