/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-02-17 22:35:46
 * @LastEditors: freeair
 * @LastEditTime: 2020-09-08 22:19:09
 */
import { apiLogin, apiLogout, apiGetUser } from '@/api/app/auth'
import { setToken, removeToken } from '@/utils/auth'
import { resetRouter } from '@/router'

const state = {
  user: {},
  reqMenu: false
}

const mutations = {
  SET_USER: (state, user) => {
    state.user = user
  },
  SET_REQ_MENU: (state, value) => {
    state.reqMenu = value
  }
}

const actions = {
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

  clearReqMenu({ commit }) {
    return new Promise((resolve) => {
      commit('SET_REQ_MENU', false)
      resolve()
    })
  },

  // get user info
  getUser({ commit }) {
    return new Promise((resolve, reject) => {
      apiGetUser('').then(response => {
        var user = response.user
        commit('SET_USER', user)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // user logout
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
