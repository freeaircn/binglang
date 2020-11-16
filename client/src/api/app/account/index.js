/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-16 15:59:44
 */
import request from '@/utils/request'

export function apiGetBasicInfoFormListContent() {
  return request({
    url: '/api/account/basic_Info_form_list_content',
    method: 'get'
  })
}

export function apiUpdateUserBasicInfo(formData) {
  return request({
    url: '/api/account/basic_info',
    method: 'put',
    data: formData
  })
}
