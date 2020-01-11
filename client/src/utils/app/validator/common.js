/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 15:14:57
 */
const REGEX_LABEL = /^([0-9a-zA-Z\u4e00-\u9fa5]){2,20}$/
const REGEX_KEYWORD = /^([0-9a-zA-Z\u4e00-\u9fa5]){2,8}$/
const REGEX_CODE = /^[1-9][0-9]{0,9}$/

export function validLabel(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入中文/英文/数字，长度2~20'))
  } else if (!REGEX_LABEL.test(value)) {
    return callback(new Error('请输入中文/英文/数字，长度2~20'))
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

export function validQueryWords(value) {
  if (!value) {
    return true
  } else if (REGEX_KEYWORD.test(value)) {
    return true
  } else {
    return '请输入中文/英文/数字，长度2~8'
  }
}
