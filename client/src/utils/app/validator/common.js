/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-24 17:14:53
 */

// const regexChineseChar = /^([\u4e00-\u9fa5]){2,8}$/
// const regexPhone = /^[1][3,4,5,7,8][0-9]{9}$/
// const regexEmail = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/
const regexChineseChar = /^[0-9]{1,2}$/
const regexPhone = /^[0-9]{1,2}$/
const regexEmail = /^[0-9]{1,2}$/
const regexSort = /^[0-9]{1,2}$/
const regexQueryWord = /^([0-9a-zA-Z\u4e00-\u9fa5]){1,40}$/

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

export function validSort(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入数字'))
  } else if (!regexSort.test(value)) {
    return callback(new Error('请输入数字'))
  } else {
    callback()
  }
}

export function validQueryWords(value) {
  if (!value) {
    return true
  } else if (regexQueryWord.test(value)) {
    return true
  } else {
    return '请输入中文/英文/数字，长度1~40'
  }
}

export function isValidPhone(str) {
  const regexPhone = regexPhone
  return regexPhone.test(str)
}
