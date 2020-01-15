/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-15 20:39:33
 */
import request from '@/utils/request'

export function apiGetRoleMenu(params) {
  return request({
    url: '/api/role-menu',
    method: 'get',
    params: params
  })
}

export function apiCreateRoleMenu(formData) {
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
