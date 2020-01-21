/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-21 20:49:14
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-21 23:19:23
 */
import Vue from 'vue'
import Logline from 'logline'

Logline.clean()
Logline.using(Logline.PROTOCOL.INDEXEDDB, 'binglang')
Logline.keep(1)

var appLogger = new Logline('app')

Vue.config.errorHandler = function(err) {
  const {
    message,
    name,
    stack
  } = err
  const url = window.location.href
  appLogger.error('vue error', { message: message, name: name, stack: stack, url: url })
  Logline.all(function(logs) {
    console.log('# 1')
    console.log(logs)
    console.log('# 2')
  })
  // console.error(err)
}

window.onerror = function(message, url, line, column, error) {
  console.log(message, url, line, column, error.stack)
}

window.addEventListener('error', (message, url, line, column, error) => {
  console.log(message, url, line, column, error.stack)
  return true
})

// window.addEventListener('unhandledrejection', function(e) {
//   e.preventDefault()
//   console.log('这是Promise的错误')
//   console.log(e.reason)
//   return true
// })
