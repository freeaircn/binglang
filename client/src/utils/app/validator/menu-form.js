/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-06 21:43:29
 */
const REGEX_NAME = /^([0-9a-zA-Z\u4e00-\u9fa5]){2,15}$/
const REGEX_PATH = /^([0-9a-zA-Z]){2,15}$/

export function validName(rule, value, callback) {
  if (!value) {
    return callback(new Error('只允许输入中文/英文/数字，长度2~15'))
  } else if (!REGEX_NAME.test(value)) {
    return callback(new Error('只允许输入中文/英文/数字，长度2~15'))
  } else {
    callback()
  }
}

export function validPath(rule, value, callback) {
  if (!value) {
    return callback(new Error('只允许输入英文/数字，长度2~15'))
  } else if (!REGEX_PATH.test(value)) {
    return callback(new Error('只允许输入英文/数字，长度2~15'))
  } else {
    callback()
  }
}
