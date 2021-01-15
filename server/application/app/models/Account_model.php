<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-15 23:45:17
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

    /**
     * @Description: 3 更新前，查询头像文件路径和文件名
     * @Author: freeair
     * @Date: 2020-11-14 19:30:57
     * @param {*}
     * @return {*}
     */
    public function get_user_avatar($id = null)
    {
        $query = $this->db->select('*')
            ->where('id', $id)
            ->get($this->tables['user_avatar']);

        return $query->result_array()[0];
    }

    /**
     * @Description: 4 更改数据库中用户头像记录
     * @Author: freeair
     * @Date: 2020-11-14 14:59:18
     * @param {*}
     * @return {*}id
     */
    public function update_user_avatar($id = null, $avatar_file_path = null, $avatar_file_name = null)
    {
        if (empty($id) || empty($avatar_file_path) || empty($avatar_file_name)) {
            return false;
        }

        $data['path']        = $avatar_file_path;
        $data['real_name']   = $avatar_file_name;
        $data['update_time'] = date("Y-m-d H:i:s", time());

        $this->db->trans_start();
        if ($this->db->where('id', $id)->update($this->tables['user_avatar'], $data) === false) {
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

    public function clear_verification_code($phone = null)
    {
        if (!isset($phone)) {
            return false;
        }

        // 1 查找数据表
        $query = $this->db->select('id, phone, code, created_on')
            ->where('phone', $phone)
            ->limit(1)
            ->get($this->tables['verification_code']);

        if ($query->num_rows() !== 0) {
            $this->db->delete($this->tables['verification_code'], ['phone' => $phone]);
        }
        return true;
    }

    public function create_verification_code($phone = null)
    {
        if (!isset($phone)) {
            return false;
        }

        $rand_num = mt_rand(10000, 99999);
        $rand_str = (string) $rand_num;

        $query = $this->db->select('phone')
            ->where('phone', $phone)
            ->limit(1)
            ->order_by('id')
            ->get($this->tables['verification_code']);

        if ($query->num_rows() === 0) {
            $data = [
                'phone'      => $phone,
                'code'       => $rand_str,
                'created_on' => time(),
            ];
            $this->db->insert($this->tables['verification_code'], $data);
            $id = $this->db->insert_id($this->tables['verification_code'] . '_id_seq');

            return (isset($id)) ? $rand_str : false;
        } else if ($query->num_rows() === 1) {
            $data = [
                'code'       => $rand_str,
                'created_on' => time(),
            ];
            $this->db->update($this->tables['verification_code'], $data, ['phone' => $phone]);

            return ($this->db->affected_rows() === 1) ? $rand_str : false;
        }
    }

    public function check_verification_code($phone = null, $code = null, $del_code = false)
    {
        if (empty($phone) || empty($code)) {
            return false;
        }

        // 1 查找数据表
        $query = $this->db->select('id, phone, code, created_on')
            ->where('phone', $phone)
            ->limit(1)
            ->get($this->tables['verification_code']);
        if ($query->num_rows() !== 1) {
            return false;
        }

        $code_data = $query->row();
        // 2 比对验证码
        if ($code !== $query->row()->code) {
            return false;
        }
        // 3 是否失效？
        $expire_time = $this->config->item('verification_code_expire_time', 'app_config');
        if ($expire_time > 0) {
            if ((time() - $code_data->created_on) > $expire_time) {
                // 超期，删除
                $this->db->delete($this->tables['verification_code'], ['phone' => $code_data->phone]);
                return false;
            }
        }

        // 4 是否删除表中记录
        if ($del_code === true) {
            $res = $this->db->delete($this->tables['verification_code'], ['phone' => $code_data->phone]);
            return ($res === false) ? false : true;
        } else {
            return true;
        }

    }

    public function update_security_setting_by_phone($phone = null, $data = [])
    {
        if (empty($phone) || empty($data)) {
            return false;
        }

        $db_data = [];
        if ($data['prop'] === 'phone') {
            $db_data['phone'] = $data['new_phone'];
        }
        if ($data['prop'] === 'email') {
            $db_data['email'] = $data['new_email'];
        }

        if (empty($db_data)) {
            return false;
        }

        $this->db->trans_start();
        if ($this->db->where('phone', $phone)->update($this->tables['user'], $db_data) === false) {
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
