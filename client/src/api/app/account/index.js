/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-13 20:10:41
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/account',
    method: 'get',
    params: params
  })
}

export function apiUpdateUser(formData) {
  return request({
    url: '/api/account',
    method: 'put',
    data: formData
  })
}
