/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors: freeair
 * @LastEditTime: 2020-09-16 19:25:42
 */
import router from './router'
import store from './store/app'
import { Message } from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style

import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

import { apiGet as apiGetMenu } from '@/api/app/admin/menu'
import { filterAsyncRouter } from '@/store/app/modules/permission'

NProgress.configure({ showSpinner: false }) // NProgress Configuration

const whiteList = ['/login', '/find_password'] // no redirect whitelist

router.beforeEach((to, from, next) => {
  NProgress.start()
  document.title = getPageTitle(to.meta.title)

  const hasToken = getToken()
  if (hasToken) {
    if (to.path === '/login') {
      next({ path: '/admin/user' })
      NProgress.done()
    } else {
      // next() // 调试
      if (typeof store.getters.user.phone === 'undefined') {
        // 请求用户信息，比如刷新页面，打开新窗口
        store.dispatch('auth/getUser').then(() => {
          // 请求用户已授权的页面
          // loadMenus(next, to)
          loadMenus()
            .then(() => {
              next({ ...to, replace: true })
            }).catch((err) => {
              Message({
                message: err,
                type: 'error'
              })
              store.dispatch('auth/logout')
                .then(() => {
                  next(`/login?redirect=${to.path}`)
                }).catch(() => {
                  next(`/login?redirect=${to.path}`)
                })
            })
        }).catch((err) => {
          Message({
            message: err,
            type: 'error'
          })
          // console.log(err)
          // 为了重新实例化vue-router对象 避免bug
          // store.dispatch('auth/logout').then(() => {
          //   location.reload()
          // })
          store.dispatch('auth/logout')
            .then(() => {
              next(`/login?redirect=${to.path}`)
            }).catch(() => {
              next(`/login?redirect=${to.path}`)
            })
        })
      // 登录时未拉取 菜单，在此处拉取
      } else if (store.getters.reqMenu) {
        // 修改成false，防止死循环
        store.dispatch('auth/clearReqMenu')
          .then(() => {
            // loadMenus(next, to)
            loadMenus()
              .then(() => {
                next({ ...to, replace: true })
              }).catch((err) => {
                Message({
                  message: err,
                  type: 'error'
                })
                store.dispatch('auth/logout')
                  .then(() => {
                    next(`/login?redirect=${to.path}`)
                  }).catch(() => {
                    next(`/login?redirect=${to.path}`)
                  })
              })
          })
      } else {
        next()
      }
    }
  } else {
    /* has no token*/
    if (whiteList.indexOf(to.path) !== -1) {
      next()
    } else {
      next(`/login?redirect=${to.path}`)
      NProgress.done()
    }
  }
})

export const loadMenus = () => {
  return new Promise((resolve, reject) => {
    apiGetMenu({ req: 'build_menu' })
      .then(function(data) {
        var menu = data.menu.slice(0)
        const asyncRouter = filterAsyncRouter(menu)
        asyncRouter.push({ path: '*', redirect: '/404', hidden: true })
        store.dispatch('permission/refreshRoutes', asyncRouter)
          .then(() => { // 存储路由
            router.addRoutes(asyncRouter) // 动态添加可访问路由表
            resolve()
          })
      })
      .catch(function(err) {
        reject(err)
      })
  })
}

// export const loadMenus = (next, to) => {
//   apiGetMenu({ req: 'build_menu' })
//     .then(function(data) {
//       var menu = data.menu.slice(0)
//       const asyncRouter = filterAsyncRouter(menu)
//       asyncRouter.push({ path: '*', redirect: '/404', hidden: true })
//       store.dispatch('permission/refreshRoutes', asyncRouter)
//         .then(() => { // 存储路由
//           router.addRoutes(asyncRouter) // 动态添加可访问路由表
//           next({ ...to, replace: true })
//         })
//     })
//     .catch(function(err) {
//       console.log('loadMenus 3: ')
//       console.log(err)
//     })
// }

router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
