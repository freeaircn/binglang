/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-31 09:11:33
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-24 17:00:59
 */
import request from '@/utils/request'

export function apiCheckLogin(params) {
  return request({
    url: '/api/auth/check_login',
    method: 'get',
    params: params
  })
}

export function apiLogin(data) {
  return request({
    url: '/api/auth/login',
    method: 'post',
    data
  })
}

export function apiGetUser(params) {
  return request({
    url: '/api/auth/check_user',
    method: 'get',
    params: params
  })
}

export function apiLogout() {
  return request({
    url: '/api/auth/logout',
    method: 'post'
  })
}

export function apiReqVerificationCode(params) {
  return request({
    url: '/api/auth/verification_code',
    method: 'get',
    params: params
  })
}

export function apiResetPassword(data) {
  return request({
    url: '/api/auth/reset_password',
    method: 'post',
    data
  })
}
