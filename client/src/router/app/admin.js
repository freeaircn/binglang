/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-25 16:09:53
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-25 16:48:40
 */
/** When your routing table is too long, you can split it into small modules **/

import Layout from '@/layout'

const adminRouter = {
  path: '/admin',
  component: Layout,
  redirect: '/admin/dict',
  name: 'Admin',
  meta: {
    title: '系统管理',
    icon: 'table'
  },
  children: [
    {
      path: 'dynamic-table',
      component: () => import('@/views/table/dynamic-table/index'),
      name: 'DynamicTable',
      meta: { title: 'Dynamic Table' }
    },
    {
      path: 'dict',
      component: () => import('@/views/app/admin/dict/index'),
      name: 'adminDict',
      meta: { title: '字典管理' }
    }
  ]
}
export default adminRouter
