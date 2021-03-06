<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-05 20:19:27
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Dept_model extends CI_Model
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

    public function read($client)
    {
        $where_str = $this->_get_sql_where($client);

        $this->db->select('*');
        if ($where_str !== '') {
            $this->db->where($where_str);
        }
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get($this->tables['dept']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result      = $query->result_array();
        $res['dept'] = $this->common_tools->arr2tree($result);

        return $res;
    }

    /**
     * read request id, label and pid
     *
     * @author freeair
     * @DateTime 2020-02-04
     * @param string $sel
     * @return mixed bool | array
     */
    public function select_by_req($sel = '')
    {
        if (empty($sel)) {
            return false;
        }

        $this->db->select($sel);
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get($this->tables['dept']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result = $query->result_array();

        $res['dept_list'] = $this->common_tools->arr2tree($result);
        return $res;
    }

    /**
     * select where id = $id
     *
     * @author freeair
     * @DateTime 2020-02-04
     * @param [type] $id
     * @return void
     */
    public function read_by_id($id = null)
    {
        if (!isset($id) || $id == 0) {
            return false;
        }

        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get($this->tables['dept']);

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
     * @DateTime 2020-02-04
     * @param array $data
     * @return mixed bool | id
     */
    public function create($data)
    {
        if (empty($data)) {
            return true;
        }

        $id = false;
        if (!$this->db->insert($this->tables['dept'], $data)) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        } else {
            $id = $this->db->insert_id($this->tables['dept'] . '_id_seq');
        }

        return $id;
    }
    /**
     * update table
     *
     * @author freeair
     * @DateTime 2020-02-04
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        if (empty($id) || empty($data)) {
            return true;
        }

        if ($this->db->where('id', $id)->update($this->tables['dept'], $data) === false) {
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
     * @DateTime 2020-02-05
     * @param array string
     * @return bool
     */
    public function delete($ids)
    {
        if (empty($ids)) {
            return true;
        }

        $this->db->trans_start();
        $this->db->where_in('id', $ids)->delete($this->tables['dept']);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * 根据id遍历数据表，输出包括输入id的所有子节点id
     * @param int $id
     * @return array string
     */
    public function get_all_children_ids($id)
    {
        $array[]    = (string) $id;
        $temp_arr[] = (string) $id;
        do {
            $this->db->select('id');
            $this->db->where_in('pid', $temp_arr);
            $query = $this->db->get($this->tables['dept']);
            $res   = $query->result_array();
            unset($temp_arr);
            foreach ($res as $k => $v) {
                $array[]    = (string) $v['id'];
                $temp_arr[] = (string) $v['id'];
            }
        } while (!empty($res));

        return $array;
    }

    /**
     * 根据id遍历数据表，输出包括输入id的所有父节点label
     * @param int $id
     * @return array string
     */
    public function get_all_parents_label($id)
    {
        // $array[] = (string)$id;
        $array      = [];
        $temp_arr[] = (string) $id;
        do {
            $this->db->select('label, pid');
            $this->db->where_in('id', $temp_arr);
            $query = $this->db->get($this->tables['dept']);
            $res   = $query->result_array();
            unset($temp_arr);
            foreach ($res as $k => $v) {
                $array[]    = $v['label'];
                $temp_arr[] = (string) $v['pid'];
            }
        } while (!empty($res));

        return $array;
    }

    /**
     * makeup where sub-statement of sql by client data
     *
     * @author freeair
     * @DateTime 2020-02-04
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
