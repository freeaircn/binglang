/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 21:38:23
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/role',
    method: 'get',
    params: params
  })
}

export function apiCreate(formData) {
  return request({
    url: '/api/role',
    method: 'post',
    data: formData
  })
}

export function apiUpdate(formData) {
  return request({
    url: '/api/role',
    method: 'put',
    data: formData
  })
}

export function apiDelete(id) {
  return request.delete('/api/role', {
    data: {
      id
    }
  })
}
