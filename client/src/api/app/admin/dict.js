/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 20:46:45
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/dict',
    method: 'get',
    params: params
  })
}

export function apiCreate(formData) {
  return request({
    url: '/api/dict',
    method: 'post',
    data: formData
  })
}

export function apiUpdate(formData) {
  return request({
    url: '/api/dict',
    method: 'put',
    data: formData
  })
}

export function apiDelete(id) {
  return request.delete('/api/dict', {
    data: {
      id
    }
  })
}
