/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 16:20:21
 */
import request from '@/utils/request'

export function apiGetDept(params) {
  return request({
    url: '/api/dept',
    method: 'get',
    params: params
  })
}

export function apiCreateDept(formData) {
  return request({
    url: '/api/dept',
    method: 'post',
    data: formData
  })
}

export function apiUpdateDept(formData) {
  return request({
    url: '/api/dept',
    method: 'put',
    data: formData
  })
}

export function apiDelDept(id) {
  return request.delete('/api/dept', {
    data: {
      id
    }
  })
}
