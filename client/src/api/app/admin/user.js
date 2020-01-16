/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-16 23:05:40
 */
import request from '@/utils/request'

export function apiGetUser(params) {
  return request({
    url: '/api/user',
    method: 'get',
    params: params
  })
}

export function apiCreateUser(formData) {
  return request({
    url: '/api/user',
    method: 'post',
    data: formData
  })
}

export function apiUpdateUser(formData) {
  return request({
    url: '/api/user',
    method: 'put',
    data: formData
  })
}

export function apiDelUser(id) {
  return request.delete('/api/user', {
    data: {
      id
    }
  })
}
