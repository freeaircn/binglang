/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-01 20:34:53
 */
import request from '@/utils/request'

export function apiGetMenu(key) {
  return request({
    url: '/api/menu',
    method: 'get',
    params: {
      key
    }
  })
}

export function apiCreateMenu(id) {
  return request({
    url: '/api/menu',
    method: 'post',
    data: {
      id
    }
  })
}

export function apiUpdateMenu(id) {
  return request({
    url: '/api/menu',
    method: 'put',
    data: {
      id
    }
  })
}

export function apiDelMenu(id) {
  return request.delete('/api/menu', {
    data: {
      id
    }
  })
}
