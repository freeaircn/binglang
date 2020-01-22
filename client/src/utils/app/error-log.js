/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-21 20:49:14
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-22 21:14:57
 */
import Vue from 'vue'
import LogLine from 'logline'

// LogLine.clean()
LogLine.using(LogLine.PROTOCOL.INDEXEDDB, 'binglang')
LogLine.keep(0.007)

var appLogger = new LogLine('app')

Vue.config.errorHandler = function(err) {
  const {
    message,
    name,
    stack
  } = err
  const url = window.location.href
  const ua = navigator.userAgent
  appLogger.error('catch_vue', { url: url, message: message, name: name, stack: stack, ua: ua })

  console.error(err)
}

window.onerror = function(message, url, line, column, error) {
  const ua = navigator.userAgent
  appLogger.error('catch_window', { url: url, message: message, line: line, column: column, stack: error.stack, ua: ua })

  console.error(message, url, line, column, error)
}

// window.addEventListener('error', (message, url, line, column, error) => {
//   console.log(message, url, line, column, error.stack)
//   return true
// })

// window.addEventListener('unhandledrejection', function(e) {
//   e.preventDefault()
//   console.log('这是Promise的错误')
//   console.log(e.reason)
//   return true
// })
