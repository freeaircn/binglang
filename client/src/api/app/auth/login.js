/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-31 09:11:33
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-11 17:43:18
 */
import request from '@/utils/request'

export function login(phone, password) {
  return request({
    url: '/api/auth/login',
    method: 'post',
    data: {
      phone,
      password
    }
  })
}

export function getInfo(token) {
  return request({
    url: '/api/auth/info',
    method: 'post',
    data: { token }
  })
}

export function logout() {
  return request({
    url: '/api/auth/logout',
    method: 'post'
  })
}

export function requestActiveMail(userphone, email) {
  return request({
    url: '/api/auth/request_active_mail',
    method: 'post',
    data: {
      userphone,
      email
    }
  })
}

export function forgotPassword(userphone, email) {
  return request({
    url: '/api/auth/forgot_password',
    method: 'post',
    data: {
      userphone,
      email
    }
  })
}

export function resetPassword(hash_code, password) {
  return request({
    url: '/api/auth/reset_password',
    method: 'post',
    data: {
      hash_code,
      password
    }
  })
}
