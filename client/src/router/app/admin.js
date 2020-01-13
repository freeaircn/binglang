/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-25 16:09:53
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-13 17:11:21
 */
/** When your routing table is too long, you can split it into small modules **/

import Layout from '@/layout'

const adminRouter = {
  path: '/admin',
  component: Layout,
  redirect: 'noRedirect',
  name: 'Admin',
  meta: {
    title: '系统管理',
    icon: 'table'
  },
  children: [
    {
      path: 'menu',
      component: () => import('@/views/app/admin/menu/index'),
      name: 'AdminMenu',
      meta: { title: '菜单管理' }
    },
    {
      path: 'dept',
      component: () => import('@/views/app/admin/dept/index'),
      name: 'AdminDept',
      meta: { title: '部门管理' }
    },
    {
      path: 'job',
      component: () => import('@/views/app/admin/job/index'),
      name: 'AdminJob',
      meta: { title: '岗位管理' }
    },
    {
      path: 'dict',
      component: () => import('@/views/app/admin/dict/index'),
      name: 'AdminDict',
      meta: { title: '字典管理' }
    }
  ]
}
export default adminRouter
