/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-21 20:49:14
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-22 15:55:10
 */
import Vue from 'vue'
import Logline from 'logline'

// Logline.clean()
Logline.using(Logline.PROTOCOL.INDEXEDDB, 'binglang')
Logline.keep(0.007)

var appLogger = new Logline('app')

Vue.config.errorHandler = function(err) {
  const {
    message,
    name,
    stack
  } = err
  const url = window.location.href

  appLogger.error('vue error', { url: url, message: message, name: name, stack: stack })
  console.error(err)
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

export function getUserAgent(url, method) {
  const ua = navigator.userAgent

  let timer5s = setInterval(() => {
    clearInterval(timer5s)
    timer5s = null
    if (method === 'blank') {
      window.open(url, '_blank')
    }
    if (method === 'replace') {
      window.location.replace(url)
    }
  }, 5000)
}
