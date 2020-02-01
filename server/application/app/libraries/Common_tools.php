<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 20:00:26
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 22:29:35
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Common tools
 */
class Common_tools extends CI_Model
{
    public function __construct()
    {
        // Check compat first
        $this->config->load('app_config', true);
    }

    /**
     * 一维数据数组生成数据树
     *
     * @param array $list 数据列表
     * @param string $id ID Key
     * @param string $pid 父ID Key
     * @param string $son 定义子数据Key
     * @return array
     */
    public static function arr2tree($list, $id = 'id', $pid = 'pid', $son = 'children')
    {
        list($tree, $map) = [[], []];
        foreach ($list as $item) {
            $map[$item[$id]] = $item;
        }
        foreach ($list as $item) {
            if (isset($item[$pid]) && isset($map[$item[$pid]])) {
                $map[$item[$pid]][$son][] = &$map[$item[$id]];
            } else {
                $tree[] = &$map[$item[$id]];
            }
        }
        unset($map);
        return $tree;
    }

    /**
     * valid client data
     *
     * @author freeair
     * @DateTime 2020-02-01
     * @param array $array
     * @param string $rule_config - rule config file, e.g client_validation/api_user.php
     * @param string $rule_item - defined by api method, e.g. index_get, index_post ...
     * @return void
     */
    public function valid_client_data($array = [], $rule_config = '', $rule_item = '')
    {
        if (empty($rule_config) || empty($rule_item)) {
            return false;
        }

        $this->config->load($rule_config, true);
        $rules = $this->config->item($rule_item, $rule_config);

        $this->load->library('app_form_validation', $rules);

        $this->app_form_validation->reset_validation();
        $this->app_form_validation->set_data($array);

        if ($this->app_form_validation->run($rule_item) === false) {
            return $this->app_form_validation->error_string();
        } else {
            return true;
        }
    }

    /**
     * Hashes the password to be stored in the database.
     *
     * @param string $password
     * @param string $identity
     *
     * @return false|string
     * @author Mathew
     */
    public function hash_password($password)
    {
        $MAX_PASSWORD_SIZE_BYTES = $this->config->item('MAX_PASSWORD_SIZE_BYTES', 'app_config');
        // Check for empty password, or password containing null char, or password above limit
        // Null char may pose issue: http://php.net/manual/en/function.password-hash.php#118603
        // Long password may pose DOS issue (note: strlen gives size in bytes and not in multibyte symbol)
        if (empty($password) || strpos($password, "\0") !== false ||
            strlen($password) > $MAX_PASSWORD_SIZE_BYTES) {
            SeasLog::error('APP_code: ' . ' - ' . 'input of func hash_password checked failed');
            return false;
        }

        $algo   = $this->_get_hash_algo();
        $params = $this->_get_hash_parameters();

        if ($algo !== false && $params !== false) {
            return password_hash($password, $algo, $params);
        }

        return false;
    }

    /** Retrieve hash algorithm according to options
     *
     * @return string|bool
     */
    protected function _get_hash_algo()
    {
        $algo        = false;
        $hash_method = $this->config->item('hash_method', 'app_config');
        switch ($hash_method) {
            case 'bcrypt':
                $algo = PASSWORD_BCRYPT;
                break;

            case 'argon2':
                $algo = PASSWORD_ARGON2I;
                break;

            default:
                // Do nothing
        }

        return $algo;
    }

    /** Retrieve hash parameter according to options
     * @return array|bool
     */
    protected function _get_hash_parameters()
    {
        $params      = false;
        $hash_method = $this->config->item('hash_method', 'app_config');
        switch ($hash_method) {
            case 'bcrypt':
                $params = [
                    $this->config->item('bcrypt_default_cost', 'app_config'),
                ];
                break;

            case 'argon2':
                $params = $this->config->item('argon2_default_params', 'app_config');
                break;

            default:
                // Do nothing
        }

        return $params;
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
    //         $query = $this->db->get($this->tables['dict']);
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

    /**
     * makeup where in string array of sql
     * e.g. array like ['1', '3', '4']
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param array $array - 2 dimensions
     * @param string $key
     * @return array
     */
    public function get_sql_ci_where_in_by_ci_result($array = [], $key = '')
    {
        if (empty($array) || $key === '') {
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
}
