/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 20:24:51
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-22 15:53:55
 */

export function wait5sOpenUrl(url, method) {
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
