import { apiLogin, apiLogout } from '@/api/app/auth'
import { getToken, setToken, removeToken } from '@/utils/auth'
// import router, { resetRouter } from '@/router'

const state = {
  token: getToken(),
  user: {},
  roles: [],
  menuRdy: false
}

const mutations = {
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  SET_USER: (state, user) => {
    state.user = user
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles
  },
  SET_MENU_RDY: (state, menuRdy) => {
    state.menuRdy = menuRdy
  }
}

const actions = {
  // user login
  login({ commit }, user) {
    const { phone, password } = user
    return new Promise((resolve, reject) => {
      apiLogin({ phone, password }).then(data => {
        // 记住？
        setToken(data.token)
        commit('SET_TOKEN', data.token)
        commit('SET_USER', data.user)
        if (data.roles.length === 0) {
          commit('SET_ROLES', ['ROLE_SYSTEM_DEFAULT'])
        } else {
          commit('SET_ROLES', data.roles)
        }
        commit('SET_MENU_RDY', false)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  setMenuRdy({ commit }) {
    commit('SET_MENU_RDY', true)
    // return new Promise((resolve, reject) => {

    // })
  }

  // // get user info
  // getInfo({ commit, state }) {
  //   return new Promise((resolve, reject) => {
  //     getInfo(state.token).then(response => {
  //       const { data } = response

  //       if (!data) {
  //         reject('Verification failed, please Login again.')
  //       }

  //       const { roles, name, avatar, introduction } = data

  //       // roles must be a non-empty array
  //       if (!roles || roles.length <= 0) {
  //         reject('getInfo: roles must be a non-null array!')
  //       }

  //       commit('SET_ROLES', roles)
  //       commit('SET_NAME', name)
  //       commit('SET_AVATAR', avatar)
  //       commit('SET_INTRODUCTION', introduction)
  //       resolve(data)
  //     }).catch(error => {
  //       reject(error)
  //     })
  //   })
  // },

  // // user logout
  // logout({ commit, state }) {
  //   return new Promise((resolve, reject) => {
  //     apiLogout(state.token).then(() => {
  //       commit('SET_TOKEN', '')
  //       commit('SET_ROLES', [])
  //       removeToken()
  //       resetRouter()
  //       resolve()
  //     }).catch(error => {
  //       reject(error)
  //     })
  //   })
  // },

  // // remove token
  // resetToken({ commit }) {
  //   return new Promise(resolve => {
  //     commit('SET_TOKEN', '')
  //     commit('SET_ROLES', [])
  //     removeToken()
  //     resolve()
  //   })
  // },

  // // dynamically modify permissions
  // changeRoles({ commit, dispatch }, role) {
  //   return new Promise(async resolve => {
  //     const token = role + '-token'

  //     commit('SET_TOKEN', token)
  //     setToken(token)

  //     const { roles } = await dispatch('getInfo')

  //     resetRouter()

  //     // generate accessible routes map based on roles
  //     const accessRoutes = await dispatch('permission/generateRoutes', roles, { root: true })

  //     // dynamically add accessible routes
  //     router.addRoutes(accessRoutes)

  //     // reset visited views and cached views
  //     dispatch('tagsView/delAllViews', null, { root: true })

  //     resolve()
  //   })
  // }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
