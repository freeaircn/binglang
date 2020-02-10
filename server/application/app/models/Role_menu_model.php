<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-10 10:50:08
 */
defined('BASEPATH') or exit('No direct script access allowed');

class Role_menu_model extends CI_Model
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

        // $this->load->library('common_tools');

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
     * select_menu_by_role
     *
     * @author freeair
     * @DateTime 2020-02-10
     * @param int $id
     * @return mixed bool | array
     */
    public function select_menu_by_role($id = null)
    {
        if (empty($id)) {
            return false;
        }

        $this->db->select('menu_id');
        $this->db->where('role_id', $id);
        $this->db->order_by('menu_id', 'ASC');
        $query = $this->db->get($this->tables['roles_menus']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $res['menu'] = $query->result_array();

        return $res;

    }

    /**
     * create_process
     *
     * @author freeair
     * @DateTime 2020-02-10
     * @param int $role_id
     * @param array $menu
     * @return bool
     */
    public function create_process($role_id, $menu)
    {
        if (empty($role_id) || empty($menu)) {
            return true;
        }

        $this->db->trans_start();
        // delete first
        $this->db->where('role_id', $role_id)->delete($this->tables['roles_menus']);

        // then insert
        $cnt = count($menu);
        $i   = 0;
        foreach ($menu as $v) {
            $data = [
                'role_id' => $role_id,
                'menu_id' => $v,
            ];
            $this->db->insert($this->tables['roles_menus'], $data);
            $i++;
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        return ($i == $cnt) ? true : false;
    }
}
