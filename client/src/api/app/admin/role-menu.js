/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-10 09:53:44
 */
import request from '@/utils/request'

export function apiGet(params) {
  return request({
    url: '/api/role-menu',
    method: 'get',
    params: params
  })
}

export function apiCreate(formData) {
  return request({
    url: '/api/role-menu',
    method: 'post',
    data: formData
  })
}

// export function apiUpdateRole(formData) {
//   return request({
//     url: '/api/role',
//     method: 'put',
//     data: formData
//   })
// }

// export function apiDelRole(id) {
//   return request.delete('/api/role', {
//     data: {
//       id
//     }
//   })
// }
