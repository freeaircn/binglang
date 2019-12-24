<?php
namespace App_Settings;

class App_Code {

  const TEST_MSG = 'Constant Here';
  // ! 公共code
  const SUCCESS             = 0;
  const POST_INPUT_EMPTY    = 101;
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

  /**
   * code map to msg
   */
  const POST_INPUT_EMPTY_MSG = '提交的数据有误！';
  const USERS_TOKEN_INVALID_MSG = '用户凭据或失效，请重新登录！';
  //
  const USERS_SIGNUP_IDENTITY_EXISTING_MSG = ' 已被其他用户注册！';
  const USERS_SIGNUP_EMAIL_EXISTING_MSG = ' 已被其他用户注册！';
  const USERS_SIGNUP_USER_CREATE_FAILED_MSG = '服务器创建新用户失败，请稍后再试(203)';
  // const USERS_SIGNUP_LOG_USER_INFO_FAILED_MSG = '服务器保存用户信息失败，请再次提交！(204)';
  const USERS_SIGNUP_CREATE_VERIFICATION_FAILED_MSG = '发送验证码失败，请重新获取！';
  const USERS_SIGNUP_VERIFY_VERIFICATION_FAILED_MSG = '验证码不正确或已失效！';
  // const USERS_LOGIN_FAILED_MSG = '用户登录失败！';
  const USERS_LOGIN_GEN_TOKEN_FAILED_MSG = '服务器处理用户登录请求失败，请重新登录！(251)';

  //
  const USERS_ACTIVATE_IDENTITY_NOT_EXISTING_MSG = ' 用户不存在';
  const USERS_ACTIVATE_USER_BEEN_ACTIVATED_MSG = ' 用户已经激活';
  const USERS_ACTIVATE_INPUT_EMAIL_INVALID_MSG = '邮箱不符，输入的邮箱地址与用户注册的邮箱 ';
  const USERS_ACTIVATE_GEN_CODE_FAILED_MSG = '服务器处理激活用户失败，请重试(303)';
  const USERS_ACTIVATE_SEND_MAIL_FAILED_MSG = '服务器发送激活邮件失败，请重试(304)';

  //
  const USERS_GET_USER_INFO_FAILED_MSG = '服务器查询用户信息失败！';
  const USERS_UPDATE_PROFILE_FAILED_MSG = '服务器更新用户信息失败！';
  const USERS_USER_INFO_INCORRECT_MSG = '提交的用户信息有误，请重新登录再更改！';
  const USERS_EMAIL_UPDATE_FAILED_MSG = '邮箱更新失败，请重试！';
  const USERS_PHONE_UPDATE_FAILED_MSG = '手机号更新失败，请重试！';
  //
  const USERS_PASSWORD_IDENTITY_NOT_EXISTING_MSG = ' 用户不存在';
  const USERS_PASSWORD_INPUT_EMAIL_INVALID_MSG = '邮箱不符，用户注册的邮箱是: ';
  const USERS_PASSWORD_GEN_CODE_FAILED_MSG = '服务器处理忘记密码失败，请重试(403)';
  const USERS_PASSWORD_SEND_MAIL_FAILED_MSG = '服务器发送邮件失败，请重试(404)';
  const USERS_PASSWORD_RESET_INVALID_MSG = '链接已失效，无法提交新密码';
  const USERS_PASSWORD_RESET_FAILED_MSG = '服务器更新密码失败，请重试';
  const USERS_PASSWORD_OLD_INVALID_MSG = '输入的旧密码错误';
  const USERS_PASSWORD_UPDATE_FAILED_MSG = '服务器更新密码失败，请重试';

  // 自定义 http header
  // const WX_HEADER_CODE = 'x-wx-code';
  // const WX_HEADER_ENCRYPTED_DATA = 'x-wx-encrypted-data';
  // const WX_HEADER_IV = 'x-wx-iv';
  // const WX_HEADER_SKEY = 'x-wx-skey';
}
