/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 20:49:35
 */

const regexSort = /^([1-9][0-9]*)$/
const regexChineseChar = /^([\u4e00-\u9fa5]){1,15}$/
const regexLowerLetterUnderline = /^[a-z_]{1,60}$/
const regexPhone = /^[1][3,4,5,7,8][0-9]{9}$/
const regexEmail = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/
// const regexChineseChar = /^[0-9]{1,2}$/
// const regexPhone = /^[0-9]{1,2}$/
// const regexEmail = /^[0-9]{1,2}$/

export function validSort(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入正整数'))
  } else if (!regexSort.test(value)) {
    return callback(new Error('请输入正整数'))
  } else {
    callback()
  }
}

export function validChineseLetter(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入中文，最多15个'))
  } else if (!regexChineseChar.test(value)) {
    return callback(new Error('请输入中文，最多15个'))
  } else {
    callback()
  }
}

export function validLowerLetterUnderline(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入小写字母或下划线，最多60个'))
  } else if (!regexLowerLetterUnderline.test(value)) {
    return callback(new Error('请输入小写字母或下划线，最多60个'))
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
