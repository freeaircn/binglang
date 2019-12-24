/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-24 20:15:43
 */
import { appConfig } from '@/app_settings'

/**
 * @param {string} str
 * @returns {Boolean}
 */
export function validPhone(str) {
  const reg = appConfig.REGEX_POHONE
  return reg.test(str)
}

export function validEmail(str) {
  const regexMail = appConfig.REGEX_MAIL
  return regexMail.test(str)
}

export function validVerificationCode(str) {
  const regexCode = appConfig.REGEX_VERIFICAITON_CODE
  return regexCode.test(str)
}

export function validPassword(str) {
  const regexPassword = appConfig.REGEX_PASSWORD
  return regexPassword.test(str)
}

export function validChineseName(str) {
  const regexChineseName = appConfig.REGEX_CHINESE_NAME
  return regexChineseName.test(str)
}
