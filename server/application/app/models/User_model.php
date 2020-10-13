<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-01 18:17:32
 * @LastEditors: freeair
 * @LastEditTime: 2020-06-06 19:24:06
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
        // $dynamic_columns = $this->_get_dynamic_column_label();
        // if ($dynamic_columns === false) {
        //     return false;
        // }

        $where_str = $this->_get_sql_where($client);
        if ($where_str === true) {
            $res['users']      = [];
            $res['total_rows'] = 0;
            // $res['dynamic_columns'] = $dynamic_columns;

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
            $res['users']      = [];
            $res['total_rows'] = 0;
            // $res['dynamic_columns'] = $dynamic_columns;

            return $res;
        }

        $this->db->select('id, sort, username, sex, phone, email, identity_document_number, attr_01_id, attr_02_id, attr_03_id, attr_04_id, enabled, last_login, ip_address, update_time');
        if ($where_str !== '') {
            $temp_uid = $query->result_array();
            $this->db->where_in('id', $this->common_tools->get_one_item_from_ci_result_array($temp_uid, 'id'));
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
            foreach ($users as &$user) {
                // get dept_label
                $temp_label = $this->_get_all_parents_label($user['attr_01_id'], $this->tables['dept']);
                if (!empty($temp_label)) {
                    $dept = '';
                    for ($i = count($temp_label) - 1; $i >= 0; $i--) {
                        $dept = $dept . ' / ' . $temp_label[$i];
                    }
                    $user['dept_label'] = substr($dept, 3, strlen($dept));
                }

                // get job_label
                if ($user['attr_02_id'] !== null) {
                    $query = $this->db->select('label')
                        ->where('id', $user['attr_02_id'])
                        ->get($this->tables['job']);
                    if ($query === false) {
                        $error = $this->db->error();
                        SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                        return false;
                    }
                    $job = $query->result_array();
                }
                if (isset($job) && !empty($job)) {
                    $user['job_label'] = $job[0]['label'];
                } else {
                    $user['job_label'] = '';
                }

                // get politic_label
                if ($user['attr_03_id'] !== null) {
                    $query = $this->db->select('label')
                        ->where('id', $user['attr_03_id'])
                        ->get($this->tables['politic']);
                    if ($query === false) {
                        $error = $this->db->error();
                        SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                        return false;
                    }
                    $politic = $query->result_array();
                }
                if (isset($politic) && !empty($politic)) {
                    $user['politic_label'] = $politic[0]['label'];
                } else {
                    $user['politic_label'] = '';
                }

                // get professional_title_label
                if ($user['attr_04_id'] !== null) {
                    $query = $this->db->select('label')
                        ->where('id', $user['attr_04_id'])
                        ->get($this->tables['professional_title']);
                    if ($query === false) {
                        $error = $this->db->error();
                        SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                        return false;
                    }
                    $professional_title = $query->result_array();
                }
                if (isset($professional_title) && !empty($professional_title)) {
                    $user['professional_title_label'] = $professional_title[0]['label'];
                } else {
                    $user['professional_title_label'] = '';
                }

                // get extra attribute label
                // select dict_data_id from user_attribute
                // $query = $this->db->select('dict_data_id')
                //     ->where('user_id', $user['id'])
                //     ->order_by('dict_data_id', 'ASC')
                //     ->get($this->tables['user_attribute']);
                // if ($query === false) {
                //     $error = $this->db->error();
                //     SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                //     return false;
                // }
                // $uid_to_attribute = $query->result_array();

                // foreach ($dynamic_columns as $category) {
                //     $user[$category['name']] = '';
                // }
                // // 动态属性，非必填字段
                // if (count($uid_to_attribute) !== 0) {
                //     $where_in = $this->common_tools->get_one_item_from_ci_result_array($uid_to_attribute, 'dict_data_id');
                //     $query    = $this->db->select('label, name')
                //         ->where_in('id', $where_in)
                //         ->get($this->tables['dict_data']);
                //     if ($query === false) {
                //         $error = $this->db->error();
                //         SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
                //         return false;
                //     }
                //     $user_attribute_data = $query->result_array();

                //     foreach ($dynamic_columns as $category) {
                //         foreach ($user_attribute_data as $item) {
                //             // 转小写字母
                //             if (strpos(strtolower($item['name']), strtolower($category['name'])) !== false) {
                //                 $user[$category['name']] = $item['label'];
                //             }
                //         }
                //     }
                // }
            }
        }
        $res['users']      = $users;
        $res['total_rows'] = $total_rows;
        // $res['dynamic_columns'] = $dynamic_columns;

        return $res;
    }

    /**
     * insert to table
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param [array] $data
     * @return [mixed] bool | uid
     */
    public function create_user($user = null, $role_list = [])
    {
        if (empty($user)) {
            return true;
        }

        $uid = false;
        $this->db->trans_start();
        if (!$this->db->insert($this->tables['user'], $user)) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        }
        $uid = $this->db->insert_id($this->tables['user'] . '_id_seq');

        // assign role
        foreach ($role_list as $role) {
            if (!empty($role)) {
                $temp_role['user_id'] = $uid;
                $temp_role['role_id'] = $role;
                $this->db->insert($this->tables['users_roles'], $temp_role);
            }
        }

        // assign extra attribute
        // foreach ($attribute_list as $attribute) {
        //     if (!empty($attribute)) {
        //         $temp_attribute['user_id']      = $uid;
        //         $temp_attribute['dict_data_id'] = $attribute;
        //         $this->db->insert($this->tables['user_attribute'], $temp_attribute);
        //     }
        // }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }

        return $uid;
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
    public function update_user($uid = null, $user = null, $role_list = [])
    {
        if (empty($uid)) {
            return true;
        }

        $this->db->trans_start();
        if ($this->db->where('id', $uid)->update($this->tables['user'], $user) === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        }

        // delete old role
        if ($this->db->where('user_id', $uid)->delete($this->tables['users_roles']) === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        }
        // insert new role
        foreach ($role_list as $role) {
            if (!empty($role)) {
                $temp_role['user_id'] = $uid;
                $temp_role['role_id'] = $role;
                $this->db->insert($this->tables['users_roles'], $temp_role);
            }
        }

        // delete old attribute
        // if ($this->db->where('user_id', $uid)->delete($this->tables['user_attribute']) === false) {
        //     $error = $this->db->error();
        //     SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        // }
        // insert new attribute
        // foreach ($attribute_list as $attribute) {
        //     if (!empty($attribute)) {
        //         $temp_attribute['user_id']      = $uid;
        //         $temp_attribute['dict_data_id'] = $attribute;
        //         $this->db->insert($this->tables['user_attribute'], $temp_attribute);
        //     }
        // }
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
     * @return mixed array| bool
     */
    public function get_form_by_create_user()
    {
        $res = [];
        // select dict.id, dict.label like name = user_attr_ from dict table
        // $query = $this->db->select('id, label')
        //     ->like('name', 'user_attr_')
        //     ->order_by('id', 'ASC')
        //     ->get($this->tables['dict']);
        // if ($query === false) {
        //     $error = $this->db->error();
        //     SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //     return false;
        // }
        // $user_attribute_category = $query->result_array();

        // select dict_data.id, dict_data.label where dict.id from dict_data table
        // $user_attribute_dynamic_list = [];
        // foreach ($user_attribute_category as $v) {
        //     $query = $this->db->select('id, label')
        //         ->where('dict_id', $v['id'])
        //         ->order_by('id', 'ASC')
        //         ->get($this->tables['dict_data']);
        //     if ($query === false) {
        //         $error = $this->db->error();
        //         SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //         return false;
        //     }
        //     $user_attribute_data = $query->result_array();

        //     // 有A，但没有A1，A2，user_attribute_dynamic_list不填入A的部分
        //     if (count($user_attribute_data) !== 0) {
        //         $user_attribute_dynamic_list[] =
        //             [
        //             "label"    => $v['label'],
        //             "sub_list" => $user_attribute_data,
        //         ];
        //     }
        // }

        // select role.id, role.label from role table
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['role']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $role_list = $query->result_array();

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
        $dept_list = $this->common_tools->arr2tree($dept_temp);

        // select job.id, job.label from job table
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['job']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $job_list = $query->result_array();

        // select politic.id, politic.label from politic table
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['politic']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $politic_list = $query->result_array();

        // select professional_title.id, professional_title.label from professional_title table
        $query = $this->db->select('id, label')
            ->order_by('id', 'ASC')
            ->get($this->tables['professional_title']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        $professional_title_list = $query->result_array();

        // $res['user_attribute_dynamic_list'] = $user_attribute_dynamic_list;

        $res['role_list']               = $role_list;
        $res['dept_list']               = $dept_list;
        $res['job_list']                = $job_list;
        $res['politic_list']            = $politic_list;
        $res['professional_title_list'] = $professional_title_list;

        return $res;
    }

    /**
     * prepare current user's profile
     * attention: order by
     *
     * @author freeair
     * @DateTime 2020-01-19
     * @param int $uid
     * @return mixed bool | array
     */
    public function get_form_by_edit_user($uid = null)
    {
        if (empty($uid)) {
            return false;
        }
        // get select list of roles, dept, job, attribute
        $form_lists = $this->get_form_by_create_user();
        if ($form_lists === false) {
            return false;
        }

        $res = [];

        // select from user table
        $query = $this->db->select('id, username, sex, phone, email, enabled, identity_document_number, sort, attr_01_id, attr_02_id, attr_03_id, attr_04_id')
            ->where('id', $uid)
            ->get($this->tables['user']);
        if ($query === false) {
            $error = $this->db->error();
            SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            return false;
        }
        // no data in db
        if ($query->num_rows() === 0) {
            return false;
        }
        // $user = $query->result_array()[0];
        $user = $this->_replace_null_field_in_user_array($query->result_array()[0]);

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
        $temp_role = $query->result_array();
        $roles     = [];
        foreach ($temp_role as $v) {
            $roles[] = $v['role_id'];
        }

        // $dynamic_columns = $this->_get_dynamic_column_label();
        // if ($dynamic_columns === false) {
        //     return false;
        // }
        // $user_attribute = [];
        // foreach ($dynamic_columns as $category) {
        //     $user_attribute[] = '';
        // }

        // $query = $this->db->select('dict_data_id')
        //     ->where('user_id', $user['id'])
        //     ->order_by('dict_data_id', 'ASC')
        //     ->get($this->tables['user_attribute']);
        // if ($query === false) {
        //     $error = $this->db->error();
        //     SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //     return false;
        // }
        // $uid_to_attribute = $query->result_array();
        // // 非必填字段
        // if (count($uid_to_attribute) !== 0) {
        //     $where_in = $this->common_tools->get_one_item_from_ci_result_array($uid_to_attribute, 'dict_data_id');

        //     $query = $this->db->select('id, name')
        //         ->where_in('id', $where_in)
        //         ->get($this->tables['dict_data']);
        //     if ($query === false) {
        //         $error = $this->db->error();
        //         SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //         return false;
        //     }
        //     $user_attribute_data = $query->result_array();

        //     $i = 0;
        //     foreach ($dynamic_columns as $category) {
        //         foreach ($user_attribute_data as $item) {
        //             // 转小写字母
        //             if (strpos(strtolower($item['name']), strtolower($category['name'])) !== false) {
        //                 $user_attribute[$i] = $item['id'];
        //             }
        //         }
        //         $i++;
        //     }
        // }

        $user['roles'] = $roles;
        // $user['user_attribute'] = $user_attribute;

        // $res['user_attribute_dynamic_list'] = $form_lists['user_attribute_dynamic_list'];

        // $res['role_list']               = $form_lists['role_list'];
        // $res['dept_list']               = $form_lists['dept_list'];
        // $res['job_list']                = $form_lists['job_list'];
        // $res['politic_list']            = $form_lists['politic_list'];
        // $res['professional_title_list'] = $form_lists['professional_title_list'];

        $res['form']       = $user;
        $res['form_lists'] = $form_lists;

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
                        $where_str .= "( attr_01_id IN " . $dept_ids_str . " )";
                    } else {
                        $where_str .= "( attr_01_id IN " . $dept_ids_str . " )";
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
                        $where_str .= "( attr_02_id IN " . $job_ids_str . " )";
                    } else {
                        $where_str .= "( attr_02_id IN " . $job_ids_str . " )";
                    }
                    $continue = true;
                } else {
                    return true;
                }
            }
        }

        if (isset($client['politic']) && $client['politic'] !== '') {
            $query_politic_id = $this->db->select('id')->like('label', $client['politic'])->group_by('id')->get($this->tables['politic']);
            if ($query_politic_id === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            } else {
                $politic_ids     = $query_politic_id->result_array();
                $politic_ids_str = $this->_get_sql_where_in_by_ci_result($politic_ids, 'id');
                if ($politic_ids_str !== '') {
                    if ($continue) {
                        $where_str .= " AND ";
                        $where_str .= "( attr_03_id IN " . $job_ids_str . " )";
                    } else {
                        $where_str .= "( attr_03_id IN " . $job_ids_str . " )";
                    }
                    $continue = true;
                } else {
                    return true;
                }
            }
        }

        if (isset($client['professional_title']) && $client['professional_title'] !== '') {
            $query_professional_title_id = $this->db->select('id')->like('label', $client['professional_title'])->group_by('id')->get($this->tables['professional_title']);
            if ($query_professional_title_id === false) {
                $error = $this->db->error();
                SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
            } else {
                $professional_title_ids     = $query_professional_title_id->result_array();
                $professional_title_ids_str = $this->_get_sql_where_in_by_ci_result($professional_title_ids, 'id');
                if ($professional_title_ids_str !== '') {
                    if ($continue) {
                        $where_str .= " AND ";
                        $where_str .= "( attr_04_id IN " . $job_ids_str . " )";
                    } else {
                        $where_str .= "( attr_04_id IN " . $job_ids_str . " )";
                    }
                    $continue = true;
                } else {
                    return true;
                }
            }
        }

        // if (isset($client['politic']) && $client['politic'] !== '') {
        //     $query_politic_id = $this->db->select('id')->like('name', 'user_attr_politic', 'after')->like('label', $client['politic'])->group_by('id')->get($this->tables['dict_data']);
        //     if ($query_politic_id === false) {
        //         $error = $this->db->error();
        //         SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //     } else {
        //         $politic_ids = $query_politic_id->result_array();
        //         if (!empty($politic_ids)) {
        //             $politic_ids_str       = $this->common_tools->get_one_item_from_ci_result_array($politic_ids, 'id');
        //             $query_politic_user_id = $this->db->select('user_id')->where_in('dict_data_id', $politic_ids_str)->group_by('user_id')->get($this->tables['user_attribute']);
        //             if ($query_politic_user_id === false) {
        //                 $error = $this->db->error();
        //                 SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //             } else {
        //                 $politic_user_ids     = $query_politic_user_id->result_array();
        //                 $politic_user_ids_str = $this->_get_sql_where_in_by_ci_result($politic_user_ids, 'user_id');
        //                 if ($politic_user_ids_str !== '') {
        //                     if ($continue) {
        //                         $where_str .= " AND ";
        //                         $where_str .= "( id IN " . $politic_user_ids_str . " )";
        //                     } else {
        //                         $where_str .= "( id IN " . $politic_user_ids_str . " )";
        //                     }
        //                     $continue = true;
        //                 }
        //             }
        //         } else {
        //             return true;
        //         }
        //     }
        // }

        // if (isset($client['professional_title']) && $client['professional_title'] !== '') {
        //     $query_professional_title = $this->db->select('id')->like('name', 'user_attr_professional_title', 'after')->like('label', $client['professional_title'])->group_by('id')->get($this->tables['dict_data']);
        //     if ($query_professional_title === false) {
        //         $error = $this->db->error();
        //         SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //     } else {
        //         $professional_titles = $query_professional_title->result_array();
        //         if (!empty($professional_titles)) {
        //             $professional_titles_str = $this->common_tools->get_one_item_from_ci_result_array($professional_titles, 'id');
        //             $query_user              = $this->db->select('user_id')->where_in('dict_data_id', $professional_titles_str)->group_by('user_id')->get($this->tables['user_attribute']);
        //             if ($query_user === false) {
        //                 $error = $this->db->error();
        //                 SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
        //             } else {
        //                 $user_ids     = $query_user->result_array();
        //                 $user_ids_str = $this->_get_sql_where_in_by_ci_result($user_ids, 'user_id');
        //                 if ($user_ids_str !== '') {
        //                     if ($continue) {
        //                         $where_str .= " AND ";
        //                         $where_str .= "( id IN " . $user_ids_str . " )";
        //                     } else {
        //                         $where_str .= "( id IN " . $user_ids_str . " )";
        //                     }
        //                     $continue = true;
        //                 }
        //             }
        //         } else {
        //             return true;
        //         }
        //     }
        // }
        return $where_str;
    }

    /**
     * user dynamic attribute columns label
     *
     * @author freeair
     * @DateTime 2020-01-27
     * @return mixed bool | array
     */
    // protected function _get_dynamic_column_label()
    // {
    //     // select dict.id, dict.label like name = user_attr_ from dict table
    //     $query = $this->db->select('name, label')
    //         ->like('name', 'user_attr_', 'after')
    //         ->order_by('id', 'ASC')
    //         ->get($this->tables['dict']);
    //     if ($query === false) {
    //         $error = $this->db->error();
    //         SeasLog::error('DB_code: ' . $error['code'] . ' - ' . $error['message']);
    //         return false;
    //     }
    //     return $query->result_array();
    // }

    /**
     * replace null field with '' for select result from user table
     *
     * @author freeair
     * @DateTime 2020-01-29
     * @param array $array
     * @return mixed array | bool
     */
    protected function _replace_null_field_in_user_array($array = [])
    {
        if (empty($array)) {
            return true;
        }

        foreach ($array as &$v) {
            if ($v === null) {
                $v = '';
            }
        }
        return $array;
    }
}
