<?php
/*
 * @Description: 不同controller使用的公共model方法
 * @Author: freeair
 * @Date: 2020-11-13 11:30:04
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-16 21:02:50
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Common_model extends CI_Model
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
     * @Description: 按手机号查找用户
     * @Author: freeair
     * @Date: 2020-11-13 15:57:18
     * @param {str}
     * @return {array}
     */
    public function get_user_by_phone($phone)
    {
        if (empty($phone)) {
            return false;
        }

        $query = $this->db->select('*')
            ->where('phone', $phone)
            ->order_by('id', 'desc')
            ->get($this->tables['user']);

        // 约定：注册用户中的手机号唯一，不能存在 两个用户使用相同的手机号
        if ($query->num_rows() !== 1) {
            return false;
        }

        return $query->result_array()[0];
    }

    /**
     * @Description: 提取用户信息，去掉敏感数据，用于写入session和返回前端
     * @Author: freeair
     * @Date: 2020-11-13 15:37:57
     * @param {array}
     * @return {array | bool}
     */
    public function build_user_info($user)
    {
        if (empty($user)) {
            return false;
        }

        // 1 去掉敏感用户信息
        $user_info = [
            'id'                       => $user['id'],
            'sort'                     => $user['sort'],
            'username'                 => $user['username'],
            'sex'                      => $user['sex'],
            'phone'                    => $user['phone'],
            'email'                    => $user['email'],
            'identity_document_number' => $user['identity_document_number'],
            'attr_01_id'               => $user['attr_01_id'],
            'attr_02_id'               => $user['attr_02_id'],
            'attr_03_id'               => $user['attr_03_id'],
            'attr_04_id'               => $user['attr_04_id'],
            'avatar_id'                => $user['avatar_id'],
            'avatar'                   => ['name' => '', 'path' => ''],
            'last_login'               => $user['last_login'],
        ];

        // 2 查找头像信息
        if (isset($user['avatar_id'])) {
            $query = $this->db->select('*')
                ->where('id', $user['avatar_id'])
                ->get($this->tables['user_avatar']);
        } else {
            return $user_info;
        }

        if ($query->num_rows() !== 1) {
            return $user_info;
        }
        $avatar              = $query->result_array()[0];
        $user_info['avatar'] = ['name' => $avatar['real_name'], 'path' => $avatar['path']];

        return $user_info;
    }

    /**
     * @Description: 用户登录后，基本信息写session
     * @Author: freeair
     * @Date: 2020-11-13 15:36:13
     * @param {array}
     * @return {bool}
     */
    public function set_session($user_info, $other_data)
    {
        if (empty($user_info) || empty($other_data)) {
            return false;
        }

        $session_data                   = $user_info;
        $session_data['acl']            = $other_data['acl'];
        $session_data['old_last_login'] = $user_info['last_login'];
        $session_data['last_check']     = time();
        $session_data['session_hash']   = $this->config->item('session_hash', 'app_config');

        $this->session->set_userdata($session_data);

        return true;
    }

    /**
     * @Description: 用户更改个人信息后，更新session数据
     * @Author: freeair
     * @Date: 2020-11-13 15:17:11
     * @param {*}
     * @return {*}
     */
    public function update_session($user_info)
    {
        if (empty($user_info)) {
            return false;
        }

        // 1 读取当前session数据
        $current = $this->session->userdata();
        if (empty($current['acl'])) {
            return false;
        }

        // 2 组织session数据，更新session
        $session_data                   = $user_info;
        $session_data['acl']            = $current['acl'];
        $session_data['old_last_login'] = $user_info['last_login'];
        $session_data['last_check']     = time();
        $session_data['session_hash']   = $this->config->item('session_hash', 'app_config');

        $this->session->set_userdata($session_data);
        $this->session->sess_regenerate(true);

        return true;
    }

}
