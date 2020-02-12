/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-02-12 15:42:59
 * @LastEditors: freeair
 * @LastEditTime: 2020-02-12 16:31:25
 */
module.exports = file => () => import('@/views/app/' + file + '.vue')
