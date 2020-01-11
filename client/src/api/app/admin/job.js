/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-11 17:42:09
 */
import request from '@/utils/request'

export function apiGetJob(params) {
  return request({
    url: '/api/job',
    method: 'get',
    params: params
  })
}

export function apiCreateJob(formData) {
  return request({
    url: '/api/job',
    method: 'post',
    data: formData
  })
}

export function apiUpdateJob(formData) {
  return request({
    url: '/api/job',
    method: 'put',
    data: formData
  })
}

export function apiDelJob(id) {
  return request.delete('/api/job', {
    data: {
      id
    }
  })
}
