/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-27 18:10:06
 * @LastEditors: freeair
 * @LastEditTime: 2020-09-04 09:00:30
 */
import Cookies from 'js-cookie'

const TokenKey = 'app_token'

export function getToken() {
  console.log('Get Cookies: ')
  console.log(Cookies.get(TokenKey))
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
