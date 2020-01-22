/*
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-25 16:09:53
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-22 23:17:13
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
      path: 'role',
      component: () => import('@/views/app/admin/role/index'),
      name: 'AdminRole',
      meta: { title: '角色管理' }
    },
    {
      path: 'dict',
      component: () => import('@/views/app/admin/dict/index'),
      name: 'AdminDict',
      meta: { title: '字典管理' }
    },
    {
      path: 'dict-data',
      component: () => import('@/views/app/admin/dict/dict-data'),
      name: 'AdminDictData',
      meta: { title: '字典数据管理' }
    },
    {
      path: 'user',
      component: () => import('@/views/app/admin/user/index'),
      name: 'AdminUser',
      meta: { title: '用户管理' }
    },
    {
      path: 'avatar',
      component: () => import('@/views/app/admin/avatar/index'),
      name: 'AdminAvatar',
      meta: { title: '头像' }
    }
  ]
}
export default adminRouter
