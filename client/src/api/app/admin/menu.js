/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 21:51:24
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/menu',
    method: 'get',
    params: params
  })
}

export function apiCreate(formData) {
  return request({
    url: '/api/menu',
    method: 'post',
    data: formData
  })
}

export function apiUpdate(formData) {
  return request({
    url: '/api/menu',
    method: 'put',
    data: formData
  })
}

export function apiDelete(id) {
  return request.delete('/api/menu', {
    data: {
      id
    }
  })
}
