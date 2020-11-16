/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 20:24:51
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-08 23:27:04
 */
// import Layout from '@/layout/index'

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

// export const filterAsyncRouter = (routers) => { // 遍历后台传来的路由字符串，转换为组件对象
//   const accessedRouters = routers.filter(router => {
//     if (router.id) {
//       delete router.id
//     }
//     if (router.pid) {
//       delete router.pid
//     }
//     if (router.component) {
//       if (router.component === 'Layout') { // Layout组件特殊处理
//         router.component = Layout
//       } else {
//         const component = router.component
//         router.component = loadView(component)
//       }
//     }
//     if (router.children && router.children.length) {
//       router.children = filterAsyncRouter(router.children)
//     }
//     return true
//   })
//   return accessedRouters
// }

// export const loadView = (view) => { // 路由懒加载
//   var temp = '@/views/app/' + view
//   return () => import(temp)
// }
