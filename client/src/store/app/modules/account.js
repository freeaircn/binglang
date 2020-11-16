/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-02-17 22:35:46
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-16 21:13:35
 */
import { apiLogin, apiLogout, apiGetUser } from '@/api/app/auth'
import { apiUpdateUserBasicInfo } from '@/api/app/account/index'
import { setToken, removeToken } from '@/utils/auth'
import { resetRouter } from '@/router'
import * as utils from '@/utils/app/common'

const state = {
  user: {},
  reqMenu: false
}

const mutations = {
  SET_USER: (state, user) => {
    state.user = utils.merge(user)
  },
  SET_USER_AVATAR: (state, avatar) => {
    state.user.avatar = utils.merge(avatar)
  },
  SET_REQ_MENU: (state, value) => {
    state.reqMenu = value
  }
}

const actions = {
  // 1 请求登录
  login({ commit }, user) {
    const { phone, password } = user
    return new Promise((resolve, reject) => {
      apiLogin({ phone, password }).then(data => {
        // if (data.expireTime) {
        //   var expireTime = new Date(data.expire_time * 1000)
        //   setToken(data.expire_code, expireTime)
        // } else {
        //   setToken(data.expire_code)
        // }

        if (data.expire_code) {
          setToken(data.expire_code)
        }

        commit('SET_USER', data.user)
        commit('SET_REQ_MENU', true)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // 2 动态路由请求标志位
  clearReqMenu({ commit }) {
    return new Promise((resolve) => {
      commit('SET_REQ_MENU', false)
      resolve()
    })
  },

  // 3 当刷新页面，或打开新窗口，请求用户信息，请求后端检查用户登录状态
  getUser({ commit }) {
    return new Promise((resolve, reject) => {
      apiGetUser('').then(data => {
        commit('SET_USER', data.user)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // 4 请求登出
  logout({ commit }) {
    return new Promise((resolve, reject) => {
      apiLogout().then(() => {
        logoutProcess(commit)
        resolve()
      }).catch(error => {
        logoutProcess(commit)
        reject(error)
      })
    })
  },

  // 5 请求更改用户基本信息
  updateUserBasicInfo({ commit }, user_info) {
    return new Promise((resolve, reject) => {
      apiUpdateUserBasicInfo(user_info).then(data => {
        commit('SET_USER', data.user)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // 6 更新用户头像响应
  updateUserAvatar({ commit }, avatar) {
    commit('SET_USER_AVATAR', avatar)
  }
}

export const logoutProcess = (commit) => {
  commit('SET_USER', {})
  commit('SET_REQ_MENU', false)
  removeToken()
  resetRouter()
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
