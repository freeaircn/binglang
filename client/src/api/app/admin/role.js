/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-15 19:36:36
 */
import request from '@/utils/request'

export function apiGetRole(params) {
  return request({
    url: '/api/role',
    method: 'get',
    params: params
  })
}

export function apiCreateRole(formData) {
  return request({
    url: '/api/role',
    method: 'post',
    data: formData
  })
}

export function apiUpdateRole(formData) {
  return request({
    url: '/api/role',
    method: 'put',
    data: formData
  })
}

export function apiDelRole(id) {
  return request.delete('/api/role', {
    data: {
      id
    }
  })
}
