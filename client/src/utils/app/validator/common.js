/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-25 16:05:22
 */

// const regexSort = /^([1-9][0-9]*)$/
// const regexChineseLetter = /^([\u4e00-\u9fa5]){1,15}$/
// const regexEnglishChineseLetter = /^([a-zA-z\u4e00-\u9fa5]{1,40})$/u
// const regexLowerLetterUnderline = /^[a-z_]{1,60}$/
// const regexLowerLetterNumUnderline = /^[a-z_0-9]{1,60}$/
// const regexIDNumber = /^[1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/
// const regexPhone = /^[1][3,4,5,7,8][0-9]{9}$/
// const regexPassword = /^[0-9a-zA-Z]+$/
// const regexEmail = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/
// const regexVerificationCode = /^[0-9]{1,10}$/

// 正则表达式
export const chineseLetter = {
  regex: /^([\u4e00-\u9fa5]){0,15}$/,
  msg: '请输入中文，不超过15个字!'
}

export const sort = {
  regex: /^([1-9][0-9]*)$/,
  msg: '请输入正整数!'
}

export const englishChineseLetter = {
  regex: /^([a-zA-z\u4e00-\u9fa5]{0,40})$/u,
  msg: '请输入中文或英文，不超过40个字!'
}

export const lowerLetterUnderline = {
  regex: /^[a-z_]{0,60}$/,
  msg: '请输入小写字母或下划线，不超过60个字!'
}

export const lowerLetterNumUnderline = {
  regex: /^[a-z_0-9]{0,60}$/,
  msg: '请输入小写字母，数字或下划线，不超过60个字!'
}

export const idNumber = {
  regex: /^([1-9]\d{5}(18|19|20|(3\d))\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]){0,1}$/,
  msg: '请输入18位身份证号码!'
}

export const phone = {
  regex: /^[1][3,4,5,7,8][0-9]{9}$/,
  msg: '请输入11位手机号码!'
}

export const password = {
  regex: /^[0-9a-zA-Z]+$/,
  msg: '密码最小长度为8位，必须包含大写、小写字母、数字！'
}

export const email = {
  regex: /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/,
  msg: '请输入有效的电子邮箱！'
}

export const verificationCode = {
  regex: /^[0-9]{1,10}$/,
  msg: '请输入验证码！'
}
