<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 20:00:26
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-19 18:55:39
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
     * @param array $list 数据列表
     * @param string $id 父ID Key
     * @param string $pid ID Key
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
}
