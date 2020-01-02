/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-02 11:06:09
 */
import request from '@/utils/request'

export function apiGetMenu(words) {
  return request({
    url: '/api/menu',
    method: 'get',
    params: {
      words
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
