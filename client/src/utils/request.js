/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-12 16:13:19
 */
import axios from 'axios'
import qs from 'qs'
import { Message } from 'element-ui'
import store from '@/store/app'
import { getToken } from '@/utils/auth'

// create an axios instance
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
  withCredentials: true, // send cookies when cross-domain requests
  headers: { 'Content-Type': 'application/json' },
  timeout: 5000 // request timeout
})

// service.defaults.headers.post['Content-Type'] = 'application/json'
service.defaults.transformRequest = [function(data) {
  // return qs.stringify(data, { arrayFormat: 'indices' })
  return JSON.stringify(data)
}]

service.defaults.paramsSerializer = function(params) {
  return qs.stringify(params, { arrayFormat: 'indices' })
}

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent

    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      config.headers['X-Token'] = getToken()
    }
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  // Any status code that lie within the range of 2xx cause this function to trigger
  response => {
    console.log(response.data)
    const res = response.data
    // eslint-disable-next-line eqeqeq
    if (res.code == 0) {
      if (typeof res.msg !== 'undefined') {
        Message({
          message: res.msg,
          type: 'success'
        })
      }
      if (typeof res.data !== 'undefined') {
        return Promise.resolve(res.data)
      } else {
        return Promise.resolve()
      }
    } else {
      const msg = res.msg + ' (' + res.code.toString() + ')'
      return Promise.reject(msg)
    }
  },
  // Any status codes that falls outside the range of 2xx cause this function to trigger
  error => {
    let code = 0
    try {
      code = error.response.data.status
    } catch (e) {
      if (error.toString().indexOf('Error: timeout') !== -1) {
        Message({
          message: '请求超时',
          type: 'error'
        })
        return Promise.reject(error)
      }
      if (error.toString().indexOf('Error: Network Error') !== -1) {
        Message({
          message: '网络错误',
          type: 'error'
        })
        return Promise.reject(error)
      }
    }
    switch (code) {
      case 401:
        console.log('#2 ' + '401登录状态已过期')
        break
      case 403:
        console.log('#3 ' + '403')
        // router.push({ path: '/401' })
        break
      default:
        console.log('#4 ' + '其他')
    }
    return Promise.reject(error)
    // if (code === 401) {
    //   MessageBox.confirm(
    //     '登录状态已过期，您可以继续留在该页面，或者重新登录',
    //     '系统提示',
    //     {
    //       confirmButtonText: '重新登录',
    //       cancelButtonText: '取消',
    //       type: 'warning'
    //     }
    //   ).then(() => {
    //     store.dispatch('LogOut').then(() => {
    //       location.reload() // 为了重新实例化vue-router对象 避免bug
    //     })
    //   })
    // } else if (code === 403) {
    //   router.push({ path: '/401' })
    // } else {
    //   const errorMsg = error.response.data.message
    //   if (errorMsg !== undefined) {
    //     Notification.error({
    //       title: errorMsg,
    //       duration: 3000
    //     })
    //   }
    // }
    // return Promise.reject(error)
  }
)

export default service
