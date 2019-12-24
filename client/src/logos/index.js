import Vue from 'vue'
import SvgLogo from '@/components/SvgLogo' // svg组件

// register globally
Vue.component('svg-logo', SvgLogo)

const requireAll = requireContext => requireContext.keys().map(requireContext)
const req = require.context('./svg', false, /\.svg$/)
requireAll(req)
