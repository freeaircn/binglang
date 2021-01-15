/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-31 09:11:33
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-15 22:24:15
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

export function apiValidVerificationCode(data) {
  return request({
    url: '/api/auth/valid_verification_code',
    method: 'post',
    data
  })
}

export function apiReqResetPassword(data) {
  return request({
    url: '/api/auth/req_reset_password',
    method: 'post',
    data
  })
}
