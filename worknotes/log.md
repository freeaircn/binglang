# APP编写日志
---

### 摘要  
1. 按时间线记录
2. 包含前台和后台的代码编写
---

### 2019-07-27
```
  # 全局引入自定义scss样式变量
  # https://stackoverflow.com/questions/49086021/using-sass-resources-loader-with-vue-cli-v3-x
  # vue.config.js文件：
    # 添加
      css: {
        loaderOptions: {
          sass: {
            data: `
              @import "~@/styles/_free_variables.scss";
              @import "~@/styles/_layout.scss";
            `
          }
        }
      }
```
---

### 2019-07-28
```
  # 增加自己所需的配置文件，src/app_settings.js文件
  # 1. 加入后端response code定义码
  # 2. 加入正则表达式定义，常用url链接
  # 3. 业务相关的常量定义
  # 4. 文件开头位置，增加：/* eslint-disable */，取消eslint检查
```
---

### 2019-12-24
```
  1 修改vue.config.js文件
    const name = defaultSettings.title || 'BE' // page title
      
  2 修改setting.js文件
    title: 'BE',
    showSettings: false,
    
  3 修改路由router/index.js文件
    mode: 'history', // require service support
      
  4 修改login.vue
    4.1 router/index.js文件：
        # 添加meta
        {
          path: '/login',
          component: () => import('@/views/login/index'),
          hidden: true,
          meta: { title: '登录' }
        },
    
    4.2 替换src/views/login/index.vue文件

# 2019-07-28
  # src/utils/validate.js文件，添加
    import { Config } from '@/app_settings'
    export function validPhone(str) {
      const reg = Config.REGEX_POHONE
      return reg.test(str)
    }
  
  # 添加logo-svg组件 https://juejin.im/post/59bb864b5188257e7a427c09
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
  
  
#++++ 2019-10-16 +++++
  1 新建client\src\view\app文件夹，放置app视图
    signup.vue
  2 新建client\src\app_config.js文件，保存应用常量和配置参数。
  3 在client\src\utils\validate.js文件中，新加方法
    isValidEmail
    isValidVerificationCode
    isValidPhoneNum
    isValidPassword
    isValidChineseName
  4 新建client\src\api\app文件夹，放置app的API
    signup.js
```


