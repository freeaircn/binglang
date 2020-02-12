/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-02-08 16:49:52
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-12 20:49:34
 */
import Vue from 'vue'
import Vuex from 'vuex'
import app from './modules/app'
// import permission from './modules/permission'
// import api from './modules/api'
// import user from './modules/user'
// import tagsView from './modules/tagsView'
import permission from './modules/permission'
import settings from './modules/settings'
import getters from './getters'
//
import auth from './modules/auth'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    app,
    // api,
    // user,
    // tagsView,
    permission,
    settings,
    auth
  },
  getters
})

// // https://webpack.js.org/guides/dependency-management/#requirecontext
// const modulesFiles = require.context('./modules', true, /\.js$/)

// // you do not need `import app from './modules/app'`
// // it will auto require all vuex module from modules file
// const modules = modulesFiles.keys().reduce((modules, modulePath) => {
//   // set './app.js' => 'app'
//   const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1')
//   const value = modulesFiles(modulePath)
//   modules[moduleName] = value.default
//   return modules
// }, {})

// const store = new Vuex.Store({
//   modules,
//   getters
// })

export default store
