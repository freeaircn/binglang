/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-12 19:48:32
 */
import router from './router'
import store from './store/app'
// import { Message } from 'element-ui'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style

import { getToken } from '@/utils/auth' // get token from cookie
import getPageTitle from '@/utils/get-page-title'

// import { buildMenus } from '@/api/system/menu'
import { apiGet as apiGetMenu } from '@/api/app/admin/menu'
import { filterAsyncRouter } from '@/store/app/modules/permission'

NProgress.configure({ showSpinner: false }) // NProgress Configuration

const whiteList = ['/login'] // no redirect whitelist

router.beforeEach(async(to, from, next) => {
  NProgress.start()
  document.title = getPageTitle(to.meta.title)

  const hasToken = getToken()
  if (hasToken) {
    if (to.path === '/login') {
      next({ path: '/admin/user' })
      NProgress.done()
    } else {
      next()
      if (store.getters.roles && store.getters.roles.length === 0) {
        if (!store.getters.menuRdy) {
          // 修改成false，防止死循环
          store.dispatch('auth/setMenuRdy')
          loadMenus(next, to)
        }
        // store.dispatch('auth/getInfo').then(res => { // 拉取user_info
        //   // 动态路由，拉取菜单
        //   store.dispatch('auth/setMenuRdy')
        //   loadMenus(next, to)
        // }).catch((err) => {
        //   console.log(err)
        //   store.dispatch('LogOut').then(() => {
        //     location.reload() // 为了重新实例化vue-router对象 避免bug
        //   })
        // })
      // 登录时未拉取 菜单，在此处拉取
      } else if (!store.getters.menuRdy) {
        // 修改成false，防止死循环
        store.dispatch('auth/setMenuRdy')
        loadMenus(next, to)
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

export const loadMenus = (next, to) => {
  apiGetMenu({ req: 'build_menu' })
    .then(function(data) {
      var menu = data.menu.slice(0)
      const asyncRouter = filterAsyncRouter(menu)
      asyncRouter.push({ path: '*', redirect: '/404', hidden: true })
      store.dispatch('permission/refreshRoutes', asyncRouter).then(() => { // 存储路由
        router.addRoutes(asyncRouter) // 动态添加可访问路由表
      })
    })
    .catch(function(err) {
      console.log('loadMenus: ')
      console.log(err)
      // Message.warning(err || 'Has Error')
    })
}

router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
