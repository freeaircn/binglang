/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-25 16:00:49
 */

const regexSort = /^([1-9][0-9]*)$/
const regexChineseChar = /^([\u4e00-\u9fa5]){1,15}$/
const regexPhone = /^[1][3,4,5,7,8][0-9]{9}$/
const regexEmail = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/
const regexID = /^([0-9]{1,17}[0-9x])$/

export function validSpecIndividual(rule, value, callback) {
  if (!value) {
    return callback()
  } else {
    if (regexChineseChar.test(value)) {
      return callback()
    }
    if (regexEmail.test(value)) {
      return callback()
    }
    if (regexPhone.test(value)) {
      return callback()
    }
    if (regexID.test(value)) {
      return callback()
    }
    if (regexSort.test(value)) {
      return callback()
    }
    return callback(new Error('输入字符不合规'))
  }
}
