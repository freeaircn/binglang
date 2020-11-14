/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-14 10:04:11
 */
import request from '@/utils/request'

export function apiGetBasicList(params) {
  return request({
    url: '/api/account/basic_list',
    method: 'get',
    params: params
  })
}

export function apiUpdateUserBasicInfo(formData) {
  return request({
    url: '/api/account/basic_info',
    method: 'put',
    data: formData
  })
}
