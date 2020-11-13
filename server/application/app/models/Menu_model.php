<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-13 21:22:54
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
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
        $query = $this->db->get($this->tables['menu']);

        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result      = $query->result_array();
        $res['menu'] = $this->common_tools->arr2tree($result);

        return $res;
    }

    /**
     * read request id, title and pid
     *
     * @author freeair
     * @DateTime 2020-02-07
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
        $query = $this->db->get($this->tables['menu']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $result = $query->result_array();

        // foreach ($result as &$item) {
        //     if (isset($item['title'])) {
        //         // riophae/vue-treeselect组件，识别字段id,label,children
        //         $item['label'] = $item['title'];
        //     }
        //     unset($item);
        // }

        $res['menu_list'] = $this->common_tools->arr2tree($result);
        return $res;
    }

    /**
     * select where id = $id
     *
     * @author freeair
     * @DateTime 2020-02-07
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
        $query = $this->db->get($this->tables['menu']);

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
     * build client menu - asyncRouter
     *
     * @author freeair
     * @DateTime 2020-02-08
     * @return mixed bool | array
     */
    public function build_menu()
    {
        $acl = $this->session->userdata('acl');
        if (empty($acl)) {
            return [];
        }

        $this->db->select('id, pid, path, component, name, hidden, alwaysShow, redirect, title, icon, noCache, breadcrumb');
        $this->db->where('type', '1');
        $this->db->where_in('roles', $acl);
        $this->db->order_by('sort', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get($this->tables['menu']);
        if ($query === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $part_1 = $query->result_array();

        foreach ($part_1 as &$item) {
            $item['meta'] = array_slice($item, 8, 4);
        }

        // +++++++++++++++
        // $this->db->select('id');
        // $this->db->where('type', '1');
        // $this->db->where_in('roles', $acl);
        // // $this->db->order_by('sort', 'ASC');
        // $this->db->order_by('id', 'ASC');
        // $query = $this->db->get($this->tables['menu']);
        // if ($query === false) {
        //     $error = $this->db->error();
        //     return false;
        // }
        // $ids = $this->common_tools->get_one_item_from_ci_result_array($query->result_array(), 'id');

        // $this->db->select('id, pid, path, component, name, hidden, alwaysShow, redirect');
        // $this->db->where_in('id', $ids);
        // // $this->db->order_by('sort', 'ASC');
        // // $this->db->order_by('id', 'ASC');
        // $query = $this->db->get($this->tables['menu']);
        // if ($query === false) {
        //     $error = $this->db->error();
        //     return false;
        // }
        // $part_1 = $query->result_array();

        // $this->db->select('title, icon, noCache, breadcrumb');
        // $this->db->where_in('id', $ids);
        // // $this->db->order_by('sort', 'ASC');
        // // $this->db->order_by('id', 'ASC');
        // $query = $this->db->get($this->tables['menu']);
        // if ($query === false) {
        //     $error = $this->db->error();
        //     return false;
        // }
        // $part_2 = $query->result_array();

        // $k = 0;
        // foreach ($part_1 as &$item) {
        //     $item['meta'] = $part_2[$k];
        //     $k++;
        // }

        $res['menu'] = $this->common_tools->arr2tree($part_1);
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
        if (!$this->db->insert($this->tables['menu'], $data)) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
        } else {
            $id = $this->db->insert_id($this->tables['menu'] . '_id_seq');
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

        if ($this->db->where('id', $id)->update($this->tables['menu'], $data) === false) {
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
        $this->db->where_in('id', $ids)->delete($this->tables['menu']);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            $this->common_tools->app_log('error', 'DB_ERR: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * 根据id便利数据表，输出包括输入id的所有子节点id
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
            $query = $this->db->get($this->tables['menu']);
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
        if (isset($client['title']) && $client['title'] !== '') {
            $where_str .= "title LIKE '%" . $client['title'] . "%' ";
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
