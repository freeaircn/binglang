/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-13 17:09:41
 */
import request from '@/utils/request'

export function apiGetDict(params) {
  return request({
    url: '/api/dict',
    method: 'get',
    params: params
  })
}

export function apiCreateDict(formData) {
  return request({
    url: '/api/dict',
    method: 'post',
    data: formData
  })
}

export function apiUpdateDict(formData) {
  return request({
    url: '/api/dict',
    method: 'put',
    data: formData
  })
}

export function apiDelDict(id) {
  return request.delete('/api/dict', {
    data: {
      id
    }
  })
}
