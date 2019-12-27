/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-27 20:51:59
 * @LastEditors: freeair
 * @LastEditTime: 2019-12-27 20:57:18
 */

const req = require.context('@/icons/svg', false, /\.svg$/)
const requireAll = requireContext => requireContext.keys()

const re = /\.\/(.*)\.svg/

const icons = requireAll(req).map(i => {
  return i.match(re)[1]
})

export default icons
