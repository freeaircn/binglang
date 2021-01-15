/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-29 13:36:29
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-15 22:10:06
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

// 请求验证码
export function apiReqVerificationCode() {
  return request({
    url: '/api/account/verification_code',
    method: 'get'
  })
}

// 提交更改安全设置
export function apiPostSecuritySetting(data) {
  return request({
    url: '/api/account/security_setting',
    method: 'post',
    data: data
  })
}
