/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-15 09:13:33
 */
import request from '@/utils/request'

export function apiGetDictData(params) {
  return request({
    url: '/api/dict-data',
    method: 'get',
    params: params
  })
}

export function apiCreateDictData(formData) {
  return request({
    url: '/api/dict-data',
    method: 'post',
    data: formData
  })
}

export function apiUpdateDictData(formData) {
  return request({
    url: '/api/dict-data',
    method: 'put',
    data: formData
  })
}

export function apiDelDictData(id) {
  return request.delete('/api/dict-data', {
    data: {
      id
    }
  })
}
