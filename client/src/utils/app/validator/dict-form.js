/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-26 19:57:16
 */
const REGEX_LABEL = /^([0-9a-zA-Z\u4e00-\u9fa5]){2,20}$/
const REGEX_CODE = /^[1-9][0-9]{0,9}$/

export function validLabel(rule, value, callback) {
  if (!value) {
    return callback(new Error('只允许输入中文，数字，字母，长度2~20'))
  } else if (!REGEX_LABEL.test(value)) {
    return callback(new Error('只允许输入中文，数字，字母，长度2~20'))
  } else {
    callback()
  }
}

export function validCode(rule, value, callback) {
  if (!value) {
    return callback()
  } else if (!REGEX_CODE.test(value)) {
    return callback(new Error('只允许输入数字，长度1~10'))
  } else {
    callback()
  }
}

export function validQueryKey(value) {
  if (!value) {
    return true
  } else if (REGEX_LABEL.test(value)) {
    return true
  } else {
    return false
  }
}
