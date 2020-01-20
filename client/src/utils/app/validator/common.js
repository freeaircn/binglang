/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-20 21:01:19
 */
// ! dev
// REGEX_POHONE: /^[1][0-9]$/,
// REGEX_MAIL: /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/,
// REGEX_PASSWORD: /^[0-9a-zA-Z]+$/,
// REGEX_CHINESE_NAME: /^([\u4e00-\u9fa5]){2,5}$/,
// REGEX_VERIFICAITON_CODE: /^[0-9]{5}$/,

// ! prod
// REGEX_POHONE: /^[1][3,4,5,7,8][0-9]{9}$/,
// REGEX_MAIL: /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/,
// REGEX_PASSWORD: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^]{8,16}$/,
// REGEX_CHINESE_NAME: /^([\u4e00-\u9fa5]){2,5}$/,
// REGEX_VERIFICAITON_CODE: /^[0-9]{5}$/,

const regexChineseChar = /^([\u4e00-\u9fa5]){2,8}$/
const regexPhone = /^[1][3,4,5,7,8][0-9]{9}$/
const regexEmail = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/

const REGEX_KEYWORD = /^([0-9a-zA-Z\u4e00-\u9fa5]){2,8}$/

export function validChineseChar(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入中文，字数2~8'))
  } else if (!regexChineseChar.test(value)) {
    return callback(new Error('请输入中文，字数2~8'))
  } else {
    callback()
  }
}

export function validPhone(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入11位手机号码'))
  } else if (!regexPhone.test(value)) {
    return callback(new Error('请输入11位手机号码'))
  } else {
    callback()
  }
}

export function validEmail(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入有效的电子邮箱'))
  } else if (!regexEmail.test(value)) {
    return callback(new Error('请输入有效的电子邮箱'))
  } else {
    callback()
  }
}

export function validQueryWords(value) {
  if (!value) {
    return true
  } else if (REGEX_KEYWORD.test(value)) {
    return true
  } else {
    return '请输入中文/英文/数字，长度2~8'
  }
}

export function isValidPhone(str) {
  const regexPhone = regexPhone
  return regexPhone.test(str)
}
