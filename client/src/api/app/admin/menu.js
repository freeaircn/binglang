/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-05 14:01:00
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

export function apiCreateMenu(formData) {
  return request({
    url: '/api/menu',
    method: 'post',
    data: {
      id: formData.id,
      name: formData.name,
      type: formData.type,
      pid: formData.pid,
      sort: formData.sort,
      permission: formData.permission,
      component: formData.component,
      component_name: formData.component_name,
      path: formData.path,
      icon: formData.icon,
      cache: formData.cache,
      hidden: formData.hidden,
      outlink: formData.outlink,
      create_time: formData.create_time
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
