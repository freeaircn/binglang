<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 10:07:44
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 21:22:01
 */
namespace App_Settings;

class App_Msg
{
    const TEST_MSG = 'Hello World !';

    const SUCCESS = '操作成功！';

    const PARAMS_INVALID       = '请求参数非法！';
    const GET_SOURCE_NOT_EXIST = '请求的资源不存在！';
    const PASSWORD_IS_EMPTY    = '密码不能为空！';
    const HASH_PASSWORD_FAILED = '密码hash失败，请稍后重试！';

    const GET_USER_FAILED                = '用户信息，查询失败，请稍后重试！';
    const GET_FORM_BY_USER_CREATE_FAILED = '用户信息的空表单，获取失败，请稍后重试！';
    const GET_FORM_BY_USER_EDIT_FAILED   = '用户信息表单，获取失败，请稍后重试！';
    const CREATE_USER_FAILED             = '新建用户，操作失败，请稍后重试！';
    const UPDATE_USER_FAILED             = '编辑用户，操作失败，请稍后重试！';
    const DELETE_USER_FAILED             = '删除用户，操作失败，请稍后重试！';

    const GET_DICT_FAILED          = '词典信息，查询失败，请稍后重试！';
    const GET_DICT_FOR_EDIT_FAILED = '词典信息表单，获取失败，请稍后重试！';
    const CREATE_DICT_FAILED       = '新建词典，操作失败，请稍后重试！';
    const UPDATE_DICT_FAILED       = '编辑词典，操作失败，请稍后重试！';
    const DELETE_DICT_FAILED       = '删除词典，操作失败，请稍后重试！';

    const GET_DICT_DATA_FAILED          = '词条信息，查询失败，请稍后重试！';
    const GET_DICT_DATA_FOR_EDIT_FAILED = '词条信息表单，获取失败，请稍后重试！';
    const CREATE_DICT_DATA_FAILED       = '新建词条，操作失败，请稍后重试！';
    const UPDATE_DICT_DATA_FAILED       = '编辑词条，操作失败，请稍后重试！';
    const DELETE_DICT_DATA_FAILED       = '删除词条，操作失败，请稍后重试！';

    const GET_DEPT_FAILED          = '部门信息，查询失败，请稍后重试！';
    const GET_DEPT_FOR_EDIT_FAILED = '部门信息表单，获取失败，请稍后重试！';
    const CREATE_DEPT_FAILED       = '新建部门，操作失败，请稍后重试！';
    const UPDATE_DEPT_FAILED       = '编辑部门，操作失败，请稍后重试！';
    const DELETE_DEPT_FAILED       = '删除部门，操作失败，请稍后重试！';

    const GET_JOB_FAILED          = '岗位信息，查询失败，请稍后重试！';
    const GET_JOB_FOR_EDIT_FAILED = '岗位信息表单，获取失败，请稍后重试！';
    const CREATE_JOB_FAILED       = '新建岗位，操作失败，请稍后重试！';
    const UPDATE_JOB_FAILED       = '编辑岗位，操作失败，请稍后重试！';
    const DELETE_JOB_FAILED       = '删除岗位，操作失败，请稍后重试！';

    const GET_ROLE_FAILED          = '角色信息，查询失败，请稍后重试！';
    const GET_ROLE_FOR_EDIT_FAILED = '角色信息表单，获取失败，请稍后重试！';
    const CREATE_ROLE_FAILED       = '新建角色，操作失败，请稍后重试！';
    const UPDATE_ROLE_FAILED       = '编辑角色，操作失败，请稍后重试！';
    const DELETE_ROLE_FAILED       = '删除角色，操作失败，请稍后重试！';

    // old
    const TBL_USER_CREATE_FAILED            = '数据表USER新加失败，请稍后重试！';
    const TBL_USER_ROLE_CREATE_FAILED       = '数据表USER_ROLE新加失败，请稍后重试！';
    const TBL_USER_EXTRA_ATTR_CREATE_FAILED = '数据表USER_EXTRA_ATTR新加失败，请稍后重试！';
    const TBL_USER_UPDATE_FAILED            = '数据表USER修改失败，请稍后重试！';
    const TBL_USER_ROLE_UPDATE_FAILED       = '数据表USER_ROLE修改失败，请稍后重试！';
    const TBL_USER_EXTRA_ATTR_UPDATE_FAILED = '数据表USER_EXTRA_ATTR修改失败，请稍后重试！';
    const TBL_USER_DELETE_FAILED            = '数据表USER删除失败，请稍后重试！';
    const TBL_USER_READ_FAILED              = '数据表USER读失败，请稍后重试！';

    const POST_INPUT_EMPTY_MSG    = '提交的数据有误！';
    const USERS_TOKEN_INVALID_MSG = '用户凭据或失效，请重新登录！';
    //
    const USERS_SIGNUP_IDENTITY_EXISTING_MSG  = ' 已被其他用户注册！';
    const USERS_SIGNUP_EMAIL_EXISTING_MSG     = ' 已被其他用户注册！';
    const USERS_SIGNUP_USER_CREATE_FAILED_MSG = '服务器创建新用户失败，请稍后再试(203)';
    // const USERS_SIGNUP_LOG_USER_INFO_FAILED_MSG = '服务器保存用户信息失败，请再次提交！(204)';
    const USERS_SIGNUP_CREATE_VERIFICATION_FAILED_MSG = '发送验证码失败，请重新获取！';
    const USERS_SIGNUP_VERIFY_VERIFICATION_FAILED_MSG = '验证码不正确或已失效！';
    // const USERS_LOGIN_FAILED_MSG = '用户登录失败！';
    const USERS_LOGIN_GEN_TOKEN_FAILED_MSG = '服务器处理用户登录请求失败，请重新登录！(251)';

    //
    const USERS_ACTIVATE_IDENTITY_NOT_EXISTING_MSG = ' 用户不存在';
    const USERS_ACTIVATE_USER_BEEN_ACTIVATED_MSG   = ' 用户已经激活';
    const USERS_ACTIVATE_INPUT_EMAIL_INVALID_MSG   = '邮箱不符，输入的邮箱地址与用户注册的邮箱 ';
    const USERS_ACTIVATE_GEN_CODE_FAILED_MSG       = '服务器处理激活用户失败，请重试(303)';
    const USERS_ACTIVATE_SEND_MAIL_FAILED_MSG      = '服务器发送激活邮件失败，请重试(304)';

    //
    const USERS_GET_USER_INFO_FAILED_MSG  = '服务器查询用户信息失败！';
    const USERS_UPDATE_PROFILE_FAILED_MSG = '服务器更新用户信息失败！';
    const USERS_USER_INFO_INCORRECT_MSG   = '提交的用户信息有误，请重新登录再更改！';
    const USERS_EMAIL_UPDATE_FAILED_MSG   = '邮箱更新失败，请重试！';
    const USERS_PHONE_UPDATE_FAILED_MSG   = '手机号更新失败，请重试！';
    //
    const USERS_PASSWORD_IDENTITY_NOT_EXISTING_MSG = ' 用户不存在';
    const USERS_PASSWORD_INPUT_EMAIL_INVALID_MSG   = '邮箱不符，用户注册的邮箱是: ';
    const USERS_PASSWORD_GEN_CODE_FAILED_MSG       = '服务器处理忘记密码失败，请重试(403)';
    const USERS_PASSWORD_SEND_MAIL_FAILED_MSG      = '服务器发送邮件失败，请重试(404)';
    const USERS_PASSWORD_RESET_INVALID_MSG         = '链接已失效，无法提交新密码';
    const USERS_PASSWORD_RESET_FAILED_MSG          = '服务器更新密码失败，请重试';
    const USERS_PASSWORD_OLD_INVALID_MSG           = '输入的旧密码错误';
    const USERS_PASSWORD_UPDATE_FAILED_MSG         = '服务器更新密码失败，请重试';

    // 自定义 http header
    // const WX_HEADER_CODE = 'x-wx-code';
    // const WX_HEADER_ENCRYPTED_DATA = 'x-wx-encrypted-data';
    // const WX_HEADER_IV = 'x-wx-iv';
    // const WX_HEADER_SKEY = 'x-wx-skey';
}
