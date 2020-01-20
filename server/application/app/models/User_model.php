<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-20 22:11:37
 */
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
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

    public function read($select_col = null, $method = null, $cond = null, $cond_col = null)
    {
        $this->db->order_by('id', 'ASC');

        if (!empty($select_col)) {
            $this->db->select($select_col);
        }

        if (!empty($method)) {
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

        $query  = $this->db->get($this->tables['user']);
        $result = $query->result_array();

        return $result;
    }

    /**
     * read all data in user table, transfer dept_label and job_label
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @return bool | array
     */
    public function read_all()
    {
        $query = $this->db->select('id, employee_number, username, sex, phone, email, identity_document_number, dept_id, job_id, enabled, last_login, ip_address, update_time')
            ->order_by('id', 'ASC')
            ->get($this->tables['user']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $users = $query->result_array();

        if (!empty($users)) {
            foreach ($users as &$v) {
                $query = $this->db->select('label')
                    ->where('id', $v['job_id'])
                    ->get($this->tables['job']);
                if ($query === false) {
                    $error = $this->db->error();
                    SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                    return false;
                }
                $job = $query->result_array();
                if (!empty($job)) {
                    $v['job_label'] = $job[0]['label'];
                }

                $temp = $this->_get_all_parents_label($v['dept_id'], $this->tables['dept']);
                if (!empty($temp)) {
                    $dept = '';
                    for ($i = count($temp) - 1; $i >= 0; $i--) {
                        $dept = $dept . ' / ' . $temp[$i];
                    }
                    $v['dept_label'] = substr($dept, 3, strlen($dept));
                }
            }
        }
        $res['users'] = $users;

        return $res;
    }

    /**
     * insert to user table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [array] $data
     * @return [mixed] bool|uid
     */
    public function create_user($data = null)
    {
        if (empty($data)) {
            return true;
        }

        if (!$this->db->insert($this->tables['user'], $data)) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $id = $this->db->insert_id($this->tables['user'] . '_id_seq');

        return $id;
    }

    /**
     * insert to users_roles table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [uint] $uid
     * @param [array] $role_ids
     * @return bool
     */
    public function create_user_role($uid = null, $role_ids = null)
    {
        if (empty($uid) || empty($role_ids)) {
            return true;
        }

        $this->db->trans_start();
        foreach ($role_ids as $v) {
            if (!empty($v)) {
                $data['user_id'] = $uid;
                $data['role_id'] = $v;
                $this->db->insert($this->tables['users_roles'], $data);
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        return true;
    }

    /**
     * insert to user_attribute table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [uint] $uid
     * @param [array] $extra_attributes
     * @return bool
     */
    public function create_user_extra_attribute($uid = null, $extra_attributes = null)
    {
        if (empty($uid) || empty($extra_attributes)) {
            return true;
        }

        $this->db->trans_start();
        foreach ($extra_attributes as $v) {
            if (!empty($v)) {
                $data['user_id']      = $uid;
                $data['dict_data_id'] = $v;
                $this->db->insert($this->tables['user_attribute'], $data);
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);

            return false;
        }

        return true;
    }

    /**
     * update user table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param int $id
     * @param [array] $data
     * @return bool
     */
    public function update_user($uid = null, $data = null)
    {
        if (empty($uid) || empty($data)) {
            return true;
        }
        $this->db->where('id', $uid);
        $res = $this->db->update($this->tables['user'], $data);

        if (!$res) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * update users_roles table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [uint] $uid
     * @param [array] $role_ids
     * @return bool
     */
    public function update_user_role($uid = null, $role_ids = null)
    {
        if (empty($uid) || empty($role_ids)) {
            return true;
        }

        // delete old
        if ($this->db->where('user_id', $uid)->delete($this->tables['users_roles']) === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        // insert new
        $this->db->trans_start();
        foreach ($role_ids as $v) {
            if (!empty($v)) {
                $data['user_id'] = $uid;
                $data['role_id'] = $v;
                $this->db->insert($this->tables['users_roles'], $data);
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        return true;
    }

    /**
     * update user_attribute table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [uint] $uid
     * @param [array] $extra_attributes
     * @return bool
     */
    public function update_user_extra_attribute($uid = null, $extra_attributes = null)
    {
        if (empty($uid) || empty($extra_attributes)) {
            return true;
        }
        // delete old
        if ($this->db->where('user_id', $uid)->delete($this->tables['user_attribute']) === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        // insert new
        $this->db->trans_start();
        foreach ($extra_attributes as $v) {
            if (!empty($v)) {
                $data['user_id']      = $uid;
                $data['dict_data_id'] = $v;
                $this->db->insert($this->tables['user_attribute'], $data);
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        return true;
    }

    /**
     * delete from user_attribute, users_roles, user table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [int] $id
     * @return bool
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            return false;
        }

        $this->db->trans_start();

        $this->db->where('user_id', $id)->delete($this->tables['user_attribute']);
        $this->db->where('user_id', $id)->delete($this->tables['users_roles']);
        $this->db->where('id', $id)->delete($this->tables['user']);

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return true;
    }

    /**
     * prepare a blank user's profile
     * attention: order by
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @return array| bool
     */
    public function prepare_new_form()
    {
        $res = [];
        // select dict.id, dict.label like name = user_attr_ from dict table
        $query = $this->db->select('id, label')
            ->like('name', 'user_attr_')
            ->order_by('id', 'ASC')
            ->get($this->tables['dict']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $dict = $query->result_array();

        // select dict_data.id, dict_data.label where dict.id from dict_data table
        $extra_attribute = [];
        foreach ($dict as $v) {
            $query = $this->db->select('id, label')
                ->where('dict_id', $v['id'])
                ->order_by('id', 'ASC')
                ->get($this->tables['dict_data']);
            if ($query === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                return false;
            }
            $dict_data         = $query->result_array();
            $extra_attribute[] =
                [
                "label"  => $v['label'],
                "values" => $dict_data,
            ];
        }

        // select role.id, role.label from role table
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['role']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $role = $query->result_array();

        // select dept.id, dept.label from dept table, make tree structure
        $query = $this->db->select('id, label, pid')
            ->order_by('id', 'ASC')
            ->get($this->tables['dept']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $dept_temp = $query->result_array();
        $dept      = $this->common_tools->arr2tree($dept_temp);

        // select job.id, job.label from job table
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['job']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $job = $query->result_array();

        $res['extra_attribute'] = $extra_attribute;
        $res['role']            = $role;
        $res['dept']            = $dept;
        $res['job']             = $job;

        return $res;
    }

    /**
     * prepare current user's profile
     * attention: order by
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param int $uid
     * @return array
     */
    public function prepare_current_form($uid = null)
    {
        if (empty($uid)) {
            return false;
        }
        // get select list of roles, dept, job, extra_attribute
        $lists = $this->prepare_new_form();
        if ($lists === false) {
            return false;
        }

        $res = [];

        // select from user table
        $query = $this->db->select('id, username, sex, phone, email, enabled, identity_document_number, employee_number, dept_id, job_id')
            ->where('id', $uid)
            ->get($this->tables['user']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $user = $query->result_array()[0];

        // select role_id from users_roles
        $query = $this->db->select('role_id')
            ->where('user_id', $uid)
            ->order_by('role_id', 'ASC')
            ->get($this->tables['users_roles']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $temp = $query->result_array();

        $role_ids = [];
        foreach ($temp as $v) {
            $role_ids[] = $v['role_id'];
        }

        // select dict_data_id from user_attribute
        $query = $this->db->select('dict_data_id')
            ->where('user_id', $uid)
            ->order_by('dict_data_id', 'ASC')
            ->get($this->tables['user_attribute']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $temp = $query->result_array();

        $extra_attributes = [];
        foreach ($temp as $v) {
            $extra_attributes[] = $v['dict_data_id'];
        }

        $user['role_ids']         = $role_ids;
        $user['extra_attributes'] = $extra_attributes;

        $res['lists'] = $lists;
        $res['user']  = $user;

        return $res;
    }

    /**
     * lookup all parent label of a child node
     * @param int $id
     * @param string $tbl
     * @return bool|array string
     */
    protected function _get_all_parents_label($id = null, $tbl = null)
    {
        if (empty($id) || empty($tbl)) {
            return false;
        }

        // $array[] = (string)$id;
        $array      = [];
        $temp_arr[] = (string) $id;
        do {
            $this->db->select('label, pid');
            $this->db->where_in('id', $temp_arr);
            $query = $this->db->get($tbl);
            if ($query === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                return false;
            } else {
                $res = $query->result_array();
            }
            unset($temp_arr);
            foreach ($res as $k => $v) {
                $array[]    = $v['label'];
                $temp_arr[] = (string) $v['pid'];
            }
        } while (!empty($res));

        return $array;
    }

    /**
     * 根据id便利数据表，输出包括输入id的所有子节点id
     * @param int $id
     * @return array string
     */
    // function get_all_children_ids($id)
    // {
    //     $array[] = (string)$id;
    //     $temp_arr[] = (string)$id;
    //     do
    //     {
    //         $this->db->select('id');
    //         $this->db->where_in('pid', $temp_arr);
    //         $query = $this->db->get($this->tables['user']);
    //         $res = $query->result_array();
    //         unset($temp_arr);
    //         foreach ($res as $k=>$v)
    //         {
    //             $array[] = (string)$v['id'];
    //             $temp_arr[] = (string)$v['id'];
    //         }
    //     }
    //     while (!empty($res));

    //     return $array;
    // }
}