<?php
/*
 * @Description: 公共方法
 * @Author: freeair
 * @Date: 2020-01-01 20:00:26
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-09 16:31:39
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Common_tools extends CI_Model
{
    public function __construct()
    {
        // Check compat first
        $this->config->load('app_config', true);
    }

    /**
     * @Description: 一维数据数组生成数据树
     * @Author: freeair
     * @Date: 2019-11-13 20:35:56
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
     * @Description: 检查前端请求输入
     *
     * @author freeair
     * @DateTime 2020-02-01
     * @param array $array
     * @param string $rule_config - 合法性定义文件, 例如： client_validation/api_user.php
     * @param string $rule_item - API的方法, 例如： index_get, index_post ...
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
            $this->app_log('error', "input of func hash_password checked failed.");
            return false;
        }

        $algo   = $this->get_hash_algo();
        $params = $this->get_hash_parameters();

        if ($algo !== false && $params !== false) {
            return password_hash($password, $algo, $params);
        }

        return false;
    }

    /** Retrieve hash algorithm according to options
     *
     * @return string|bool
     */
    public function get_hash_algo()
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
    public function get_hash_parameters()
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
    public function get_one_item_from_ci_result_array($array = [], $key = '')
    {
        if (empty($array) || $key === '') {
            return [];
        }

        $res = [];
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $res[] = $item[$key];
            }
        }
        return $res;
    }

    /**
     * @Description: 统一log接口
     * @Author: freeair
     * @Date: 2020-11-13 20:40:51
     * @param {*}
     * @return {*}
     */
    public function app_log($level, $msg, $where = '')
    {
        // 1 组织日志消息头内容
        $session = session_id();
        if (empty($session)) {
            $session = "trace_id";
        } elseif (strlen($session) >= 8) {
            $session = substr($session, 0, 8);
        } else {
            $session = "trace_id";
        }

        $phone = $this->session->userdata('phone');
        if (empty($phone)) {
            $phone = "phone";
        } elseif (strlen($phone) >= 11) {
            $phone = substr($phone, 0, 3) . "****" . substr($phone, 7, 4);
        } else {
            $phone = "phone";
        }

        if (empty($where)) {
            $msg_header = $session . ' | ' . $phone . ' | ' . 'where' . ' | ';
        } else {
            $msg_header = $session . ' | ' . $phone . ' | ' . $where . ' | ';
        }

        // 2 调用接口写log
        switch ($level) {
            case "debug":
                SeasLog::debug($msg_header . $msg);
                break;
            case "info":
                SeasLog::info($msg_header . $msg);
                break;
            case "notice":
                SeasLog::notice($msg_header . $msg);
                break;
            case "warning":
                SeasLog::warning($msg_header . $msg);
                break;
            case "error":
                SeasLog::error($msg_header . $msg);
                break;
            case "critical":
                SeasLog::critical($msg_header . $msg);
                break;
            case "alert":
                SeasLog::alert($msg_header . $msg);
                break;
            case "emergency":
                SeasLog::emergency($msg_header . $msg);
                break;
            default:
                break;
        }

    }

    /**
     * @Description: 修改用户上传的头像图片大小
     * @Author: freeair
     * @Date: 2020-11-14 10:48:48
     * @param {str}
     * @return {*}
     */
    public function resize_avatar_img($source_img, $new_image)
    {
        if (empty($source_img) || empty($new_image)) {
            return false;
        }

        $config['image_library'] = 'gd2';
        $config['source_image']  = $source_img;
        $config['new_image']     = $new_image;
        // $config['create_thumb']   = true;
        $config['maintain_ratio'] = true;
        $config['width']          = 200;
        $config['height']         = 200;

        $this->load->library('image_lib', $config);

        if (!$this->image_lib->resize()) {
            // echo $this->image_lib->display_errors();
            return false;
        } else {
            return true;
        }

    }
}
