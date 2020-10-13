/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-02-12 09:11:11
 * @LastEditors: freeair
 * @LastEditTime: 2020-09-07 15:50:11
 */
import { constantRoutes } from '@/router'
import Layout from '@/layout/index'
const _import = require('@/utils/app/_import_' + process.env.NODE_ENV) // 获取组件的方法

const state = {
  routes: constantRoutes,
  addRoutes: []
}

const mutations = {
  SET_ROUTES: (state, routes) => {
    state.addRoutes = routes
    state.routes = constantRoutes.concat(routes)
  }
}

const actions = {
  refreshRoutes({ commit }, asyncRouter) {
    return new Promise((resolve) => {
      commit('SET_ROUTES', asyncRouter)
      resolve()
    })
  }
}

export const filterAsyncRouter = (routers) => { // 遍历后台传来的路由字符串，转换为组件对象
  return routers.filter(router => {
    if (router.id) {
      delete router.id
    }
    if (router.pid) {
      delete router.pid
    }
    if (router.redirect === '') {
      delete router.redirect
    }
    //
    if (router.hidden) {
      router.hidden = router.hidden === '1'
    }
    if (router.alwaysShow) {
      router.alwaysShow = router.alwaysShow === '1'
    }
    // meta - title, icon, noCache, breadcrumb
    if (router.meta.noCache) {
      router.meta.noCache = router.meta.noCache === '1'
    }
    if (router.meta.breadcrumb) {
      router.meta.breadcrumb = router.meta.breadcrumb === '1'
    }
    //
    if (router.component) {
      if (router.component === 'Layout') { // Layout组件特殊处理
        router.component = Layout
        router.path = '/' + router.path
      } else {
        router.component = _import(router.component)
      }
    }
    if (router.children && router.children.length) {
      router.children = filterAsyncRouter(router.children)
    }
    return true
  })
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
