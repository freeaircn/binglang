/* eslint-disable */
/*
 * @Description: 自定义配置
 * @Author: freeair
 * @Date: 2019-07-28 17:18:09
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-21 20:51:32
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
}

export const appCode = {
  /**
   * @description 后端response code
   */
  SUCCESS            : 0
}
