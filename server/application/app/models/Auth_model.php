<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-18 22:11:15
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
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
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 17:40:21
     * @param {type}
     * @return: bool
     */
    public function is_max_login_attempts_exceeded($phone, $ip_address)
    {
        if (empty($phone) || empty($ip_address)) {
            return false;
        }

        $max_attempts = $this->config->item('maximum_login_attempts', 'app_config');
        $lockout_time = $this->config->item('lockout_time', 'app_config');

        if ($max_attempts > 0) {
            $this->db->select('id');
            $this->db->where('identity', $phone);
            $this->db->where('ip_address', $ip_address);
            $this->db->where('time >', time() - $lockout_time, false);
            $query    = $this->db->get($this->tables['login_attempts']);
            $attempts = $query->num_rows();

            return $attempts >= $max_attempts;
        }

        return false;
    }

    /**
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 20:10:48
     * @param {type}
     * @return:
     */
    public function increase_login_attempts($identity, $ip_address)
    {
        if (empty($identity) || empty($ip_address)) {
            return false;
        }

        $data = ['ip_address' => $ip_address, 'identity' => $identity, 'time' => time()];
        return $this->db->insert($this->tables['login_attempts'], $data);
    }

    /**
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 20:17:36
     * @param {type}
     * @return:
     */
    public function clear_login_attempts($identity, $ip_address)
    {
        if (empty($identity) || empty($ip_address)) {
            return false;
        }

        $this->db->where('identity', $identity);
        $this->db->where('ip_address', $ip_address);
        return $this->db->delete($this->tables['login_attempts']);
    }

    /**
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 19:38:21
     * @param {type}
     * @return: array
     */
    // public function get_user_by_phone($phone)
    // {
    //     if (empty($phone)) {
    //         return false;
    //     }

    //     $query = $this->db->select('*')
    //         ->where('phone', $phone)
    //         ->order_by('id', 'desc')
    //         ->get($this->tables['user']);

    //     // 约定：注册用户中的手机号唯一，不能存在 两个用户使用相同的手机号
    //     if ($query->num_rows() !== 1) {
    //         return false;
    //     }

    //     return $query->result_array()[0];
    // }

    /**
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 20:33:50
     * @param {type}
     * @return: array
     */
    public function get_user_acl_by_uid($uid)
    {
        if (empty($uid)) {
            return [];
        }

        // 1 查询用户所属的role
        $query = $this->db->select('role_id')
            ->where('user_id', $uid)
            ->order_by('role_id', 'asc')
            ->get($this->tables['users_roles']);

        if ($query->num_rows() === 0) {
            return [];
        }

        // CI的where_in 要求数组元素是string类型
        $role_id_str = $this->common_tools->get_one_item_from_ci_result_array($query->result_array(), 'role_id');

        // 2 根据role_id，查询menu id
        $query = $this->db->select('menu_id')
            ->where_in('role_id', $role_id_str)
            ->order_by('menu_id', 'asc')
            ->get($this->tables['roles_menus']);

        if ($query->num_rows() === 0) {
            return [];
        }
        $temp        = $this->common_tools->get_one_item_from_ci_result_array($query->result_array(), 'menu_id');
        $menu_id_str = array_unique($temp);

        // 3 根据menu_id，查询menu表的“roles”字段
        $query = $this->db->select('roles')
            ->where_in('id', $menu_id_str)
            ->get($this->tables['menu']);

        if ($query->num_rows() === 0) {
            return [];
        }
        $acl_str = $this->common_tools->get_one_item_from_ci_result_array($query->result_array(), 'roles');

        return $acl_str;
    }

    /**
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 19:55:25
     * @param {type}
     * @return: bool
     */
    public function verify_password($password, $hash_password_db)
    {
        $MAX_PASSWORD_SIZE_BYTES = $this->config->item('MAX_PASSWORD_SIZE_BYTES', 'app_config');
        // Check for empty password, or password containing null char, or password above limit
        // Null char may pose issue: http://php.net/manual/en/function.password-hash.php#118603
        // Long password may pose DOS issue (note: strlen gives size in bytes and not in multibyte symbol)
        if (empty($password) || empty($hash_password_db) || strpos($password, "\0") !== false
            || strlen($password) > $MAX_PASSWORD_SIZE_BYTES) {
            return false;
        }

        return password_verify($password, $hash_password_db);
    }

    /**
     * @Description:
     * @Author: freeair
     * @Date: 2020-06-03 20:43:14
     * @param {type}
     * @return:
     */
    public function update_last_login($uid)
    {
        $this->db->update($this->tables['user'], ['last_login' => time()], ['id' => $uid]);
        return $this->db->affected_rows() == 1;
    }

    /**
     * Check if password needs to be rehashed
     * If true, then rehash and update it in DB
     *
     * @param string $hash
     * @param string $identity
     * @param string $password
     *
     */
    public function rehash_password_if_needed($hash, $identity, $password)
    {
        $algo   = $this->common_tools->get_hash_algo();
        $params = $this->common_tools->get_hash_parameters();

        if ($algo !== false && $params !== false) {
            if (password_needs_rehash($hash, $algo, $params)) {
                $this->_set_password_db($identity, $password);
            }
        }
    }

    /**
     * create_verification_code
     *
     * @param int|null $phone
     *
     * @return string|bool
     * @author
     */
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

    /**
     * check_verification_code
     *
     * @param string|null $phone
     * @param string|null $code
     * @return bool
     * @author
     */
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

    /**
     * Internal function to set a password in the database
     *
     * @param string $identity
     * @param string $password
     *
     * @return bool
     */
    protected function _set_password_db($identity, $password)
    {
        $hash = $this->common_tools->hash_password($password);

        if ($hash === false) {
            return false;
        }

        // When setting a new password, invalidate any other token
        $data = [
            'password'                => $hash,
            'remember_code'           => null,
            'forgotten_password_code' => null,
            'forgotten_password_time' => null,
        ];

        $this->db->update($this->tables['user'], $data, ['phone' => $identity]);

        return $this->db->affected_rows() == 1;
    }

}
