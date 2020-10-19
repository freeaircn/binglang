/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors: freeair
 * @LastEditTime: 2020-10-17 21:21:51
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/dept',
    method: 'get',
    params: params
  })
}

export function apiCreate(formData) {
  return request({
    url: '/api/dept',
    method: 'post',
    data: formData
  })
}

export function apiUpdate(formData) {
  return request({
    url: '/api/dept',
    method: 'put',
    data: formData
  })
}

export function apiDelete(id) {
  return request({
    url: '/api/dept',
    method: 'delete',
    data: { id }
  })
}
