/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-29 20:22:16
 */
import request from '@/utils/request'

export function apiGetMenus(id) {
  return request({
    url: '/menus_api/get',
    method: 'get',
    data: {
      id
    }
  })
}

export function apiCreateMenus(id) {
  return request({
    url: '/menus_api/post',
    method: 'post',
    data: {
      id
    }
  })
}

export function apiUpdateMenus(id) {
  return request({
    url: '/menus_api2',
    method: 'post',
    data: {
      id
    }
  })
}

export function testApi(msg) {
  return request({
    url: '/menus_api/post',
    method: 'post',
    data: {
      msg
    }
  })
}

export function testRestApi(msg) {
  return request({
    url: '/api/menus',
    method: 'post',
    data: {
      msg
    }
  })
}
