/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-08 22:37:21
 */
import request from '@/utils/request'

export function apiGetMenu(col = null, words = null) {
  return request({
    url: '/api/menu',
    method: 'get',
    params: {
      col,
      words
    }
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
