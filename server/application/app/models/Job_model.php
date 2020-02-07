<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 20:37:45
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

        $this->load->library('common_tools');

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
     * @DateTime 2020-02-07
     * @param associative array $client
     * @return mixed bool | array
     */
    public function read($client)
    {
        $where_str = $this->_get_sql_where($client);

        $this->db->select('id');
        if ($where_str !== '') {
            $this->db->where($where_str);
        }
        // 查询一次，获取total
        $query = $this->db->get($this->tables['job']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $total_rows = $query->num_rows();
        if ($total_rows === 0) {
            $res['job']        = [];
            $res['total_rows'] = 0;

            return $res;
        }
        // 查询结果>0，再查询一次
        $this->db->select('*');
        if ($where_str !== '') {
            $temp_id = $query->result_array();
            $this->db->where_in('id', $this->common_tools->get_sql_ci_where_in_by_ci_result($temp_id, 'id'));
        }
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('id', 'ASC');
        if (isset($client['limit']) && $client['limit'] !== '') {
            $limit_temp = explode('_', $client['limit']);
            $num        = (int) $limit_temp[0];
            $offset     = (int) $limit_temp[1];
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get($this->tables['job']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result = $query->result_array();

        $res['job']        = $result;
        $res['total_rows'] = $total_rows;

        return $res;
    }

    /**
     * select where id = $id
     *
     * @author freeair
     * @DateTime 2020-02-07
     * @param int $id
     * @return mixed bool | array
     */
    public function read_by_id($id = null)
    {
        if (!isset($id) || $id == 0) {
            return false;
        }

        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->tables['job']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
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
     * insert to table
     *
     * @author freeair
     * @DateTime 2020-02-07
     * @param array $data
     * @return mixed bool | id
     */
    public function create($data)
    {
        if (empty($data)) {
            return true;
        }

        $id = false;
        if (!$this->db->insert($this->tables['job'], $data)) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        } else {
            $id = $this->db->insert_id($this->tables['job'] . '_id_seq');
        }

        return $id;
    }

    /**
     * update
     *
     * @author freeair
     * @DateTime 2020-02-07
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        if (empty($id) || empty($data)) {
            return true;
        }

        if ($this->db->where('id', $id)->update($this->tables['job'], $data) === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        } else {
            return true;
        }
    }

    /**
     * delete
     *
     * @author freeair
     * @DateTime 2020-02-07
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        if (!isset($id)) {
            return false;
        }

        $this->db->trans_start();
        $this->db->where('id', $id)->delete($this->tables['job']);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * makeup where sub-statement of sql by client data
     *
     * @author freeair
     * @DateTime 2020-02-07
     * @param array $client
     * @return void
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
        // if (isset($client['name']) && $client['name'] !== '') {
        //     if ($continue) {
        //         $where_str .= " AND ";
        //         $where_str .= "name LIKE '%" . $client['name'] . "%' ";
        //     } else {
        //         $where_str .= "name LIKE '%" . $client['name'] . "%' ";
        //     }
        //     $continue = true;
        // }

        return $where_str;
    }
}
