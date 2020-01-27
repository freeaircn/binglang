<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-27 10:23:42
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
     * read data in user table, transfer dept_label and job_label, extra attribute
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [associative array] $client
     * @return mixed bool | array
     */
    public function read($client)
    {
        $extra_columns = $this->_get_user_extra_columns();
        if ($extra_columns === false) {
            return false;
        }

        $where_str = $this->_get_sql_where($client);
        if ($where_str === true) {
            $res['users']         = [];
            $res['total_rows']    = 0;
            $res['extra_columns'] = $extra_columns;

            return $res;
        }

        $this->db->select('id');
        if ($where_str !== '') {
            $this->db->where($where_str);
        }
        $query = $this->db->get($this->tables['user']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $total_rows = $query->num_rows();
        if ($total_rows === 0) {
            $res['users']         = [];
            $res['total_rows']    = 0;
            $res['extra_columns'] = $extra_columns;

            return $res;
        }

        // for wanted
        $this->db->select('id, sort, username, sex, phone, email, identity_document_number, dept_id, job_id, enabled, last_login, ip_address, update_time');
        if ($where_str !== '') {
            $temp_uid = $query->result_array();
            $this->db->where_in('id', $this->_get_sql_ci_where_in_by_ci_result($temp_uid, 'id'));
        }
        $this->db->order_by('sort', 'ASC');
        if (isset($client['limit']) && $client['limit'] !== '') {
            $limit_temp = explode('_', $client['limit']);
            $num        = (int) $limit_temp[0];
            $offset     = (int) $limit_temp[1];
            $this->db->limit($num, $offset);
        }
        $query = $this->db->get($this->tables['user']);

        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $users = $query->result_array();

        if (!empty($users)) {
            foreach ($users as &$v) {
                // transfer job_label
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

                // transfer dept_label
                $temp_label = $this->_get_all_parents_label($v['dept_id'], $this->tables['dept']);
                if (!empty($temp_label)) {
                    $dept = '';
                    for ($i = count($temp_label) - 1; $i >= 0; $i--) {
                        $dept = $dept . ' / ' . $temp_label[$i];
                    }
                    $v['dept_label'] = substr($dept, 3, strlen($dept));
                }

                // transfer extra attribute label
                // select dict_data_id from user_attribute
                $query = $this->db->select('dict_data_id')
                    ->where('user_id', $v['id'])
                    ->order_by('dict_data_id', 'ASC')
                    ->get($this->tables['user_attribute']);
                if ($query === false) {
                    $error = $this->db->error();
                    SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                    return false;
                }
                $temps = $query->result_array();

                // select dict_data.label where id from dict_data table

                for ($j = 0; $j < count($extra_columns); $j++) {
                    $v[$extra_columns[$j]['name']] = '';
                }

                $i = 0;
                foreach ($temps as $temp) {
                    $query = $this->db->select('label')
                        ->where('id', $temp['dict_data_id'])
                        ->get($this->tables['dict_data']);
                    if ($query === false) {
                        $error = $this->db->error();
                        SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                        return false;
                    }
                    $dict_data_arr = $query->result_array();
                    if (!empty($dict_data_arr[0])) {
                        $v[$extra_columns[$i]['name']] = $dict_data_arr[0]['label'];
                    }
                    $i++;
                }

            }
        }
        $res['users']         = $users;
        $res['total_rows']    = $total_rows;
        $res['extra_columns'] = $extra_columns;

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
        if (!isset($id)) {
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
        $query = $this->db->select('id, username, sex, phone, email, enabled, identity_document_number, sort, dept_id, job_id')
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
     * lookup child id of input
     * @param int $id
     * @param string $table
     * @return array id
     */
    protected function _get_all_children_ids($id, $table = '')
    {
        if (!isset($id)) {
            return [];
        }
        if (!isset($table) || $table == '') {
            return [];
        }

        $array[]    = $id;
        $temp_arr[] = (string) $id;
        do {
            $this->db->select('id');
            $this->db->where_in('pid', $temp_arr);
            $query = $this->db->get($this->tables[$table]);
            $res   = $query->result_array();
            unset($temp_arr);
            foreach ($res as $k => $v) {
                $array[]    = $v['id'];
                $temp_arr[] = (string) $v['id'];
            }
        } while (!empty($res));

        return $array;
    }

    /**
     * makeup where in string of sql
     * e.g. string like ('1', '3', '4')
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param array $array
     * @return string
     */
    protected function _get_sql_where_in($array = [])
    {
        if (empty($array)) {
            return '';
        }

        $str = "(";
        foreach ($array as $item) {
            $str .= "'" . (string) $item . "',";
        }
        $str = substr($str, 0, strlen($str) - 1);
        $str .= ")";

        return $str;
    }

    /**
     * makeup where in string of sql
     * e.g. string like ('1', '3', '4')
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param array $array - 2 dimensions
     * @param string $key
     * @return string
     */
    protected function _get_sql_where_in_by_ci_result($array = [], $key = '')
    {
        if (empty($array || $key === '')) {
            return '';
        }

        $str = "(";
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $str .= "'" . (string) $item[$key] . "',";
            }
        }
        if (strlen($str) > 2) {
            $str = substr($str, 0, strlen($str) - 1);
            $str .= ")";
        } else {
            $str = '';
        }
        return $str;
    }

    /**
     * makeup where in string of sql
     * e.g. array like ['1', '3', '4']
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param array $array - 2 dimensions
     * @param string $key
     * @return array
     */
    protected function _get_sql_ci_where_in_by_ci_result($array = [], $key = '')
    {
        if (empty($array || $key === '')) {
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

    /**
     * makeup where sub-statement of sql by client data
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @param [associated array] $client
     * @return mixed bool | string , true indicates no data in db table
     */
    protected function _get_sql_where($client = [])
    {
        if (empty($client)) {
            return '';
        }

        $where_str = '';
        $continue  = false;
        if (isset($client['individual']) && $client['individual'] !== '') {
            $where_str .= "( sort LIKE '%" . $client['individual'] . "%'";
            $where_str .= " OR username LIKE '%" . $client['individual'] . "%'";
            $where_str .= " OR phone LIKE '%" . $client['individual'] . "%'";
            $where_str .= " OR identity_document_number LIKE '%" . $client['individual'] . "%'";
            $where_str .= " OR email LIKE '%" . $client['individual'] . "%' )";
            $continue = true;
        }
        if (isset($client['sex']) && $client['sex'] !== '') {
            if ($continue) {
                $where_str .= " AND ";
                $where_str .= "sex = " . $client['sex'];
            } else {
                $where_str .= "sex = " . $client['sex'];
            }
            $continue = true;
        }
        if (isset($client['dept']) && $client['dept'] !== '') {
            $query_dept_id = $this->db->select('id')->like('label', $client['dept'])->group_by('id')->get($this->tables['dept']);
            if ($query_dept_id === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            } else {
                $dept_ids = [];
                foreach ($query_dept_id->result_array() as $item_dept_id) {
                    $dept_ids = array_merge($dept_ids, $this->_get_all_children_ids($item_dept_id['id'], 'dept'));
                }
                $dept_ids     = array_unique($dept_ids);
                $dept_ids_str = $this->_get_sql_where_in($dept_ids);
                if ($dept_ids_str !== '') {
                    if ($continue) {
                        $where_str .= " AND ";
                        $where_str .= "( dept_id IN " . $dept_ids_str . " )";
                    } else {
                        $where_str .= "( dept_id IN " . $dept_ids_str . " )";
                    }
                    $continue = true;
                } else {
                    return true;
                }
            }
        }
        if (isset($client['job']) && $client['job'] !== '') {
            $query_job_id = $this->db->select('id')->like('label', $client['job'])->group_by('id')->get($this->tables['job']);
            if ($query_job_id === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            } else {
                $job_ids     = $query_job_id->result_array();
                $job_ids_str = $this->_get_sql_where_in_by_ci_result($job_ids, 'id');
                if ($job_ids_str !== '') {
                    if ($continue) {
                        $where_str .= " AND ";
                        $where_str .= "( job_id IN " . $job_ids_str . " )";
                    } else {
                        $where_str .= "( job_id IN " . $job_ids_str . " )";
                    }
                    $continue = true;
                } else {
                    return true;
                }
            }
        }
        if (isset($client['politic']) && $client['politic'] !== '') {
            $query_politic_id = $this->db->select('id')->like('name', 'user_attr_politic', 'after')->like('label', $client['politic'])->group_by('id')->get($this->tables['dict_data']);
            if ($query_politic_id === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            } else {
                $politic_ids = $query_politic_id->result_array();
                if (!empty($politic_ids)) {
                    $politic_ids_str       = $this->_get_sql_ci_where_in_by_ci_result($politic_ids, 'id');
                    $query_politic_user_id = $this->db->select('user_id')->where_in('dict_data_id', $politic_ids_str)->group_by('user_id')->get($this->tables['user_attribute']);
                    if ($query_politic_user_id === false) {
                        $error = $this->db->error();
                        SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                    } else {
                        $politic_user_ids     = $query_politic_user_id->result_array();
                        $politic_user_ids_str = $this->_get_sql_where_in_by_ci_result($politic_user_ids, 'user_id');
                        if ($politic_user_ids_str !== '') {
                            if ($continue) {
                                $where_str .= " AND ";
                                $where_str .= "( id IN " . $politic_user_ids_str . " )";
                            } else {
                                $where_str .= "( id IN " . $politic_user_ids_str . " )";
                            }
                            $continue = true;
                        }
                    }
                } else {
                    return true;
                }
            }
        }

        if (isset($client['professional_title']) && $client['professional_title'] !== '') {
            $query_professional_title = $this->db->select('id')->like('name', 'user_attr_professional_title', 'after')->like('label', $client['professional_title'])->group_by('id')->get($this->tables['dict_data']);
            if ($query_professional_title === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            } else {
                $professional_titles = $query_professional_title->result_array();
                if (!empty($professional_titles)) {
                    $professional_titles_str = $this->_get_sql_ci_where_in_by_ci_result($professional_titles, 'id');
                    $query_user              = $this->db->select('user_id')->where_in('dict_data_id', $professional_titles_str)->group_by('user_id')->get($this->tables['user_attribute']);
                    if ($query_user === false) {
                        $error = $this->db->error();
                        SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                    } else {
                        $user_ids     = $query_user->result_array();
                        $user_ids_str = $this->_get_sql_where_in_by_ci_result($user_ids, 'user_id');
                        if ($user_ids_str !== '') {
                            if ($continue) {
                                $where_str .= " AND ";
                                $where_str .= "( id IN " . $user_ids_str . " )";
                            } else {
                                $where_str .= "( id IN " . $user_ids_str . " )";
                            }
                            $continue = true;
                        }
                    }
                } else {
                    return true;
                }
            }
        }
        return $where_str;
    }

    /**
     * prepare user extra attribute columns
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @return mixed bool | array
     */
    protected function _get_user_extra_columns()
    {
        // select dict.id, dict.label like name = user_attr_ from dict table
        $query = $this->db->select('name, label')
            ->like('name', 'user_attr_', 'after')
            ->order_by('id', 'ASC')
            ->get($this->tables['dict']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        return $query->result_array();
    }
}
