/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-02-08 16:49:52
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-12 20:58:41
 */
const getters = {
  token: state => state.auth.token,
  user: state => state.auth.user,
  roles: state => state.auth.roles,
  menuRdy: state => state.auth.menuRdy,
  permission_routes: state => state.permission.routes,
  //
  sidebar: state => state.app.sidebar,
  device: state => state.app.device
  // sidebar: state => state.app.sidebar,
  // device: state => state.app.device,
  // token: state => state.user.token,
  // visitedViews: state => state.tagsView.visitedViews,
  // cachedViews: state => state.tagsView.cachedViews,
  // roles: state => state.user.roles,
  // user: state => state.user.user,
  // loadMenus: state => state.user.loadMenus,
  // permission_routers: state => state.permission.routers,
  // addRouters: state => state.permission.addRouters,
  // socketApi: state => state.api.socketApi,
  // imagesUploadApi: state => state.api.imagesUploadApi,
  // updateAvatarApi: state => state.api.updateAvatarApi,
  // qiNiuUploadApi: state => state.api.qiNiuUploadApi,
  // sqlApi: state => state.api.sqlApi,
  // swaggerApi: state => state.api.swaggerApi
}
export default getters
