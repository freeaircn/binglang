/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 16:19:55
 */
import request from '@/utils/request'

export function apiGetMenu(params) {
  return request({
    url: '/api/menu',
    method: 'get',
    params: params
  })
}

export function apiCreateMenu(formData) {
  return request({
    url: '/api/menu',
    method: 'post',
    data: formData
  })
}

export function apiUpdateMenu(formData) {
  return request({
    url: '/api/menu',
    method: 'put',
    data: formData
  })
}

export function apiDelMenu(id) {
  return request.delete('/api/menu', {
    data: {
      id
    }
  })
}
