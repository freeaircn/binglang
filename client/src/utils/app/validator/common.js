/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 21:07:57
 */

const regexSort = /^([1-9][0-9]*)$/
const regexChineseLetter = /^([\u4e00-\u9fa5]){1,15}$/
const regexEnglishChineseLetter = /^([a-zA-z\u4e00-\u9fa5]{1,40})$/u
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
  } else if (!regexChineseLetter.test(value)) {
    return callback(new Error('请输入中文，最多15个'))
  } else {
    callback()
  }
}

export function validEnglishChineseLetter(rule, value, callback) {
  if (!value) {
    return callback(new Error('请输入中文或英文，最多40个'))
  } else if (!regexEnglishChineseLetter.test(value)) {
    return callback(new Error('请输入中文或英文，最多40个'))
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
