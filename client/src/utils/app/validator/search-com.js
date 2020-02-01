/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 20:42:11
 */

const regexSort = /^([1-9][0-9]*)$/
const regexChineseChar = /^([\u4e00-\u9fa5]){1,15}$/
const regexEnglishChineseChar = /^([a-zA-z\u4e00-\u9fa5]{1,40})$/u
const regexLowerLetterUnderline = /^[a-z_]{1,60}$/
const regexPhone = /^[1][3,4,5,7,8][0-9]{9}$/
const regexEmail = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/
const regexID = /^([0-9]{1,17}[0-9x])$/
// const regexChineseChar = /^[0-9]{1,2}$/
// const regexPhone = /^[0-9]{1,2}$/
// const regexEmail = /^[0-9]{1,2}$/

// export function validSort(rule, value, callback) {
//   if (!value) {
//     return callback(new Error('请输入正整数'))
//   } else if (!regexSort.test(value)) {
//     return callback(new Error('请输入正整数'))
//   } else {
//     callback()
//   }
// }

export function validChineseLetter(rule, value, callback) {
  if (!value) {
    return callback()
  } else if (!regexChineseChar.test(value)) {
    return callback(new Error('请输入中文'))
  } else {
    callback()
  }
}

export function validEnglishChineseLetter(rule, value, callback) {
  if (!value) {
    return callback()
  } else if (!regexEnglishChineseChar.test(value)) {
    return callback(new Error('请输入中文或英文'))
  } else {
    callback()
  }
}

export function validLowerLetterUnderline(rule, value, callback) {
  if (!value) {
    return callback()
  } else if (!regexLowerLetterUnderline.test(value)) {
    return callback(new Error('允许输入小写英文或下划线'))
  } else {
    callback()
  }
}

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

// export function validPhone(rule, value, callback) {
//   if (!value) {
//     return callback(new Error('请输入11位手机号码'))
//   } else if (!regexPhone.test(value)) {
//     return callback(new Error('请输入11位手机号码'))
//   } else {
//     callback()
//   }
// }

// export function validEmail(rule, value, callback) {
//   if (!value) {
//     return callback(new Error('请输入有效的电子邮箱'))
//   } else if (!regexEmail.test(value)) {
//     return callback(new Error('请输入有效的电子邮箱'))
//   } else {
//     callback()
//   }
// }
