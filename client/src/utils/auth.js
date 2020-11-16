/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-27 18:10:06
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-15 21:16:08
 */
import Cookies from 'js-cookie'

const TokenKey = 'app_token'

export function getToken() {
  return Cookies.get(TokenKey)
}

export function setToken(token, expireTime) {
  return Cookies.set(TokenKey, token, {
    expires: expireTime
  })
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}
