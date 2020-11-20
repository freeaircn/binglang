/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-31 09:11:33
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-17 14:54:44
 */

import Vue from 'vue'
import Router from 'vue-router'

// const originalPush = Router.prototype.push
// Router.prototype.push = function push(location, onResolve, onReject) {
//   if (onResolve || onReject) return originalPush.call(this, location, onResolve, onReject)
//   return originalPush.call(this, location).catch(err => { console.log('err: ' + err) })
// }

// const originalReplace = Router.prototype.replace
// Router.prototype.replace = function replace(location, onResolve, onReject) {
//   if (onResolve || onReject) return originalReplace.call(this, location, onResolve, onReject)
//   return originalReplace.call(this, location).catch(err => { console.log('err: ' + err) })
// }

Vue.use(Router)

/* Layout */
// import Layout from '@/layout'

// app
// import adminRouter from './app/admin'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if set true, the page will no be cached(default is false)
    affix: true                  if set true, the tag will affix in the tags-view
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

export const constantRoutes = [
  {
    path: '/',
    redirect: '/account/setting'
  },
  {
    path: '/login',
    component: () => import('@/views/app/auth/login'),
    hidden: true,
    meta: { title: '登录' }
  },
  {
    path: '/find_password',
    component: () => import('@/views/app/auth/find_password'),
    hidden: true,
    meta: { title: '找回密码' }
  },
  {
    path: '/404',
    component: () => import('@/views/app/error-page/404'),
    hidden: true
  },
  {
    path: '/401',
    component: () => import('@/views/app/error-page/401'),
    hidden: true
  }
]

const createRouter = () => new Router({
  mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
