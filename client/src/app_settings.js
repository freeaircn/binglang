/* eslint-disable */
/*
 * @Description: 自定义配置
 * @Author: freeair
 * @Date: 2019-07-28 17:18:09
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-24 19:16:50
 */

export const appConfig = {
  /**
   * @description unit - day, 1/48 indicated 30minutes
   */
  COOKIE_EXP: 48,

  /**
   * @description 定义正则表达式
   */
  // ! dev 测试环境使用
  REGEX_POHONE           : /^[1][0-9]$/,
  REGEX_MAIL             : /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/,
  REGEX_PASSWORD         : /^[0-9a-zA-Z]+$/,
  REGEX_CHINESE_NAME     : /^([\u4e00-\u9fa5]){2,5}$/,
  REGEX_VERIFICAITON_CODE: /^[0-9]{5}$/,

  // ! prod 生产环境使用
  // REGEX_POHONE           : /^[1][3,4,5,7,8][0-9]{9}$/,
  // REGEX_MAIL             : /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/,
  // REGEX_PASSWORD         : /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^]{8,16}$/,
  // REGEX_CHINESE_NAME     : /^([\u4e00-\u9fa5]){2,5}$/,
  // REGEX_VERIFICAITON_CODE: /^[0-9]{5}$/,

  /**
   * @description 常用url链接
   */
  MAIL_163_URL: 'https://mail.163.com',
  MAIL_126_URL: 'https://mail.126.com',
  MAIL_139_URL: 'http://mail.10086.cn/',
  MAIL_QQ_URL : 'https://mail.qq.com'
}

export const appCode = {
  /**
   * @description 后端response code定义
   * 命名元素：控制器名_流程_描述
   */
  SUCCESS            : 0,
  POST_INPUT_EMPTY   : 101,
  USERS_TOKEN_INVALID: 110,

  /**
   * @description Users Signup流程 code：201~249
   */
  USERS_SIGNUP_IDENTITY_EXISTING   : 201,
  USERS_SIGNUP_EMAIL_EXISTING      : 202,
  USERS_SIGNUP_USER_CREATE_FAILED  : 203,
  USERS_SIGNUP_LOG_USER_INFO_FAILED: 204,
  // USERS_SIGNUP_USER_ACTIVATE_FAILED: 206,

  /**
   * @description Users Login流程 code：250~299
   */
  USERS_LOGIN_FAILED          : 250,
  USERS_LOGIN_GEN_TOKEN_FAILED: 251,

  /**
   * @description Users Active Mail流程 code：300~349
   */
  USERS_ACTIVATE_IDENTITY_NOT_EXISTING: 300,
  USERS_ACTIVATE_USER_BEEN_ACTIVATED  : 301,
  USERS_ACTIVATE_INPUT_EMAIL_INVALID  : 302,
  USERS_ACTIVATE_GEN_CODE_FAILED      : 303,
  USERS_ACTIVATE_SEND_MAIL_FAILED     : 304,

  /**
   * @description Users Get user info流程 code：350~399
   */
  USERS_GET_USER_INFO_FAILED: 350
}
