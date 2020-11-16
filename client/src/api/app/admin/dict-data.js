/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-16 21:51:20
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/dict_data',
    method: 'get',
    params: params
  })
}

export function apiCreate(formData) {
  return request({
    url: '/api/dict_data',
    method: 'post',
    data: formData
  })
}

export function apiUpdate(formData) {
  return request({
    url: '/api/dict_data',
    method: 'put',
    data: formData
  })
}

export function apiDelete(id) {
  return request({
    url: '/api/dict_data',
    method: 'delete',
    data: { id }
  })
}
