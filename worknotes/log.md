# APP编写日志

---
### 摘要  
1. 按时间线记录
2. 包含前台和后台的代码编写
3. 关键字: [全局] [vue] [ci]

---
### 2019-12-24
```
  1 [全局][vue]修改vue.config.js文件
    # title
    const name = defaultSettings.title || 'BE' // page title
    
    # [全局]引入自定义scss样式变量
      [参考贴子](https://stackoverflow.com/questions/49086021/using-sass-resources-loader-with-vue-cli-v3-x)
      # vue.config.js文件：
        # 文件末尾添加
          css: {
            loaderOptions: {
              sass: {
                data: `
                  @import "~@/styles/app-variables.scss";
                  @import "~@/styles/app-layout.scss";
                `
              }
            }
          }
          
  2 [全局][vue]修改setting.js文件
    title: 'BE',
    showSettings: false,
    
  3 [全局][vue][ci][路由]
    [ci]路由application\config\routes.php文件，添加
    /*
    * 前端单页面，路由 history模式
    */
    $route['(:any)'] = 'home';
    // $route['(:any)/(:any)'] = 'home';
    
    [vue]路由router/index.js文件
    mode: 'history', // require service support
    
    VUE路由配置生效机制：
    a) 定义routes表，生成route对象实例
      const createRouter = () => new Router({
        mode: 'history', // require service support
        scrollBehavior: () => ({ y: 0 }),
        routes: constantRoutes
      })
      
    b) 定义路由导航守卫permission.js
        定义白名单：
        const whiteList = ['/login', '/signup', '/auth-redirect'] // no redirect whitelist
    
    c) main.js中，引入a)和b)
    
       
  4 [全局]
    新建client\src\views\app文件夹，存放app视图
    新建client\src\api\app文件夹，存放app的API
    新建client\src\utils\app文件夹，存放app的公共模块
      # 在client\src\utils\app\validate.js文件中，新加方法
      validPhone
      validEmail
      validVerificationCode
      validPassword
      validChineseName
    新建自己所需的配置文件，src/app_settings.js文件
      # 加入后端response code定义码
      # 加入正则表达式定义，常用url链接
      # 业务相关的常量定义
      # 文件开头位置，增加：/* eslint-disable */，取消eslint检查

  5 [全局]src/utils/validate.js文件，添加
    import { appConfig } from '@/app_settings'
    /**
     * @param {string} str
     * @returns {Boolean}
     * @description by freeair
     */
    export function validPhone(str) {
      const reg = appConfig.REGEX_POHONE
      return reg.test(str)
    }
  
  6 [全局]添加logo-svg组件，模仿icons组件 https://juejin.im/post/59bb864b5188257e7a427c09
    # src/components/，加入SvgLogo/
    # src/，加入logos/
    # src/main.js文件
      import './logos' // logo
    # vue.config.js文件：
      // for logo svg
      config.module
        .rule('svg')
        .exclude.add(resolve('src/logos'))
        .end()
      config.module
        .rule('logos')
        .test(/\.svg$/)
        .include.add(resolve('src/logos'))
        .end()
        .use('svg-sprite-loader')
        .loader('svg-sprite-loader')
        .options({
          symbolId: 'logo-[name]'
        })
        .end()
    # 在src/logos/中，存放自定义svg图片

  7 login页面
    # router/index.js文件
      # 修改
      {
        path: '/login',
        component: () => import('@/views/app/login/index'),
        hidden: true,
        meta: { title: '登录' }
      },
      
    # 新建src/views/login/index.vue文件  
      import { validPhone } from '@/utils/app/validate'
  
  8 signup页面
    # router/index.js文件
      # 添加
      {
        path: '/signup',
        component: () => import('@/views/app/signup/index'),
        hidden: true,
        meta: { title: '注册' }
      },
    # 新建src/views/signup/index.vue文件
      import { appCode } from '@/app_settings'
```
***TODO:***
1. 统一API风格
2. 定义用户信息数据表
3. 制定后台数据库字典
  
---
### 2019-12-
```
  1 x
  
```
***TODO:***  
1. x

