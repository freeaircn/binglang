/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-24 09:56:03
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-25 20:00:40
 */
import Vue from 'vue'

// import './utils/app/error-log' // error log

import Cookies from 'js-cookie'

import 'normalize.css/normalize.css' // a modern alternative to CSS resets

import Element from 'element-ui'
import './styles/element-variables.scss'

import '@/styles/index.scss' // global css

import App from './App'
import store from '@/store/app'
import router from './router'

import './icons' // icon
import './logos' // logo by freeair
import './permission' // permission control

import * as filters from './filters' // global filters

Vue.use(Element, {
  size: Cookies.get('size') || 'medium' // set element-ui default size
})

// 引入 Vant
import { Button as VanButton } from 'vant'
import { Cell, CellGroup } from 'vant'
import { Field } from 'vant'
import { Tab as VanTab, Tabs as VanTabs } from 'vant'
import { RadioGroup as VanRadioGroup, Radio as VanRadio } from 'vant'
import { Popup as VanPopup } from 'vant'
import { Picker as VanPicker } from 'vant'
import { Form as VanForm } from 'vant'
import { Cascader } from 'vant'
// import { Image as VanImage } from 'vant'
import { Toast } from 'vant'
// import { Uploader as VanUploader } from 'vant'
Vue.use(Cell)
Vue.use(CellGroup)
Vue.use(Field)
Vue.component(VanButton.name, VanButton)
Vue.component(VanTab.name, VanTab)
Vue.component(VanTabs.name, VanTabs)
Vue.component(VanRadioGroup.name, VanRadioGroup)
Vue.component(VanRadio.name, VanRadio)
Vue.component(VanPopup.name, VanPopup)
Vue.component(VanPicker.name, VanPicker)
Vue.component(VanForm.name, VanForm)
Vue.use(Cascader)
// Vue.component(VanImage.name, VanImage)
Vue.use(Toast)
// Vue.component(VanUploader.name, VanUploader)

// 引入antd
// import Antd from 'ant-design-vue'
// import 'ant-design-vue/dist/antd.css'
// Vue.use(Antd)
// import { Input as AInput } from 'ant-design-vue'
// import { Select as ASelect } from 'ant-design-vue'
// import { Tree as ATree } from 'ant-design-vue'
// import { TreeSelect as ATreeSelect } from 'ant-design-vue'
// Vue.component(AInput.name, AInput)
// Vue.component(ASelect.name, ASelect)
// Vue.component(ATree.name, ATree)
// Vue.component(ATreeSelect.name, ATreeSelect)

// register global utility filtersA
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key])
})

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
