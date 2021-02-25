<?php
/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 10:07:44
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-24 20:46:52
 */
namespace App_Settings;

class App_Code
{
    const SUCCESS = 0;

    const PARAMS_INVALID                = 100;
    const REQ_DATA_NOT_EXIST            = 110;
    const PASSWORD_IS_EMPTY             = 120;
    const HASH_PASSWORD_FAILED          = 121;
    const SYS_SEND_MAIL_FAILED          = 130;
    const SYS_VERIFICATION_CODE_INVALID = 131;
    const SYS_RESET_PASSWORD_FAILED     = 132;

    // hook - access control
    const USER_NOT_LOGIN    = 150;
    const USER_NOT_APPROVED = 151;

    // api user
    const GET_USER_FAILED                = 200;
    const GET_FORM_BY_CREATE_USER_FAILED = 201;
    const GET_FORM_BY_EDIT_USER_FAILED   = 202;
    const CREATE_USER_FAILED             = 203;
    const UPDATE_USER_FAILED             = 204;
    const DELETE_USER_FAILED             = 205;
    // api dict
    const GET_DICT_FAILED          = 220;
    const GET_DICT_FOR_EDIT_FAILED = 221;
    const CREATE_DICT_FAILED       = 222;
    const UPDATE_DICT_FAILED       = 223;
    const DELETE_DICT_FAILED       = 224;
    // api dict_data
    const GET_DICT_DATA_FAILED          = 230;
    const GET_DICT_DATA_FOR_EDIT_FAILED = 231;
    const CREATE_DICT_DATA_FAILED       = 232;
    const UPDATE_DICT_DATA_FAILED       = 233;
    const DELETE_DICT_DATA_FAILED       = 234;
    // api dept
    const GET_DEPT_FAILED          = 240;
    const GET_DEPT_FOR_EDIT_FAILED = 241;
    const CREATE_DEPT_FAILED       = 242;
    const UPDATE_DEPT_FAILED       = 243;
    const DELETE_DEPT_FAILED       = 244;
    // api job
    const GET_JOB_FAILED          = 250;
    const GET_JOB_FOR_EDIT_FAILED = 251;
    const CREATE_JOB_FAILED       = 252;
    const UPDATE_JOB_FAILED       = 253;
    const DELETE_JOB_FAILED       = 254;
    // api menu
    const GET_MENU_FAILED          = 260;
    const GET_MENU_FOR_EDIT_FAILED = 261;
    const CREATE_MENU_FAILED       = 262;
    const UPDATE_MENU_FAILED       = 263;
    const DELETE_MENU_FAILED       = 264;
    // api role
    const GET_ROLE_FAILED          = 270;
    const GET_ROLE_FOR_EDIT_FAILED = 271;
    const CREATE_ROLE_FAILED       = 272;
    const UPDATE_ROLE_FAILED       = 273;
    const DELETE_ROLE_FAILED       = 274;
    // api auth
    const MAX_LOGIN_ATTEMPT_EXCEEDED   = 280;
    const USERNAME_OR_PASSWORD_WRONG   = 281;
    const PHONE_NOT_EXISTING           = 283;
    const USER_NOT_ENABLED             = 282;
    const USER_EMAIL_NOT_EXISTING      = 290;
    const GEN_VERIFICATION_CODE_FAILED = 291;
    // api account
    const ACCOUNT_NEW_SECURITY_SETTING_EXISTING  = 300;
    const ACCOUNT_UPDATE_SECURITY_SETTING_FAILED = 302;
    const ACCOUNT_CHANGE_PWD_FAILED              = 301;
    const ACCOUNT_UPDATE_AVATAR_FAILED           = 303;
    const ACCOUNT_UPDATE_USER_FAILED             = 304;
    const ACCOUNT_GET_CODE_FAILED                = 305;

    // old
    const USERS_TOKEN_INVALID = 110;

    // ! 变量名组成：控制器名_流程_描述
    // ! Users Signup流程 code：201~249
    const USERS_SIGNUP_IDENTITY_EXISTING  = 201;
    const USERS_SIGNUP_EMAIL_EXISTING     = 202;
    const USERS_SIGNUP_USER_CREATE_FAILED = 203;
    // const USERS_SIGNUP_LOG_USER_INFO_FAILED = 204;
    const USERS_SIGNUP_CREATE_VERIFICATION_FAILED = 205;
    const USERS_SIGNUP_VERIFY_VERIFICATION_FAILED = 206;
    // const USERS_SIGNUP_USER_ACTIVATE_FAILED = 206;

    // ! Users Login流程 code：250~299
    const USERS_LOGIN_FAILED           = 250;
    const USERS_LOGIN_GEN_TOKEN_FAILED = 251;

    // ! Users Active Mail流程 code：300~349
    const USERS_ACTIVATE_IDENTITY_NOT_EXISTING = 300;
    const USERS_ACTIVATE_USER_BEEN_ACTIVATED   = 301;
    const USERS_ACTIVATE_INPUT_EMAIL_INVALID   = 302;
    const USERS_ACTIVATE_GEN_CODE_FAILED       = 303;
    const USERS_ACTIVATE_SEND_MAIL_FAILED      = 304;

    // ! Users settings code：350~399
    const USERS_GET_USER_INFO_FAILED  = 350;
    const USERS_UPDATE_PROFILE_FAILED = 351;
    const USERS_USER_INFO_INCORRECT   = 352;
    const USERS_EMAIL_UPDATE_FAILED   = 353;
    const USERS_PHONE_UPDATE_FAILED   = 354;

    // ! Users Forgot Password流程 code：400~449
    const USERS_PASSWORD_IDENTITY_NOT_EXISTING = 400;
    const USERS_PASSWORD_INPUT_EMAIL_INVALID   = 402;
    const USERS_PASSWORD_GEN_CODE_FAILED       = 403;
    const USERS_PASSWORD_SEND_MAIL_FAILED      = 404;
    const USERS_PASSWORD_RESET_INVALID         = 405;
    const USERS_PASSWORD_RESET_FAILED          = 406;
    const USERS_PASSWORD_OLD_INVALID           = 407;
    const USERS_PASSWORD_UPDATE_FAILED         = 408;

    // ! Generators code：450~499
    const GEN_GET_START_LAST_LOG_FAILED = 450;
    const GEN_SET_START_STOP_LOG_FAILED = 451;
}
