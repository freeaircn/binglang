# APP编写日志

---
### 摘要  
1. 按时间线记录
2. 包含前台和后台的代码编写
3. 缩略字: [G]-global  [v]-vue [c]-ci @-框架根目录

---
### 2019-12-24
```
  1 [v]修改vue.config.js文件
    # title
    const name = defaultSettings.title || 'BE' // page title
              
  2 [v]修改setting.js文件
    title: 'BE',
    showSettings: false,
    
  3 [G][v][c][路由]
    [c]路由application\config\routes.php文件，添加
    /*
    * 前端单页面，路由 history模式
    */
    $route['(:any)'] = 'home';
    // $route['(:any)/(:any)'] = 'home';
    
    [v]路由router/index.js文件
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
    
  4 [v]
    # 在client\src\utils\app\validate.js文件中，新加方法
      validPhone
      validEmail
      validVerificationCode
      validPassword
      validChineseName
    # src/app_settings.js文件
      # 加入后端response code定义码
      # 加入正则表达式定义，常用url链接
      # 业务相关的常量定义
      # 文件开头位置，增加：/* eslint-disable */，取消eslint检查

  5 [v]src/utils/validate.js文件，添加
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
1. 规范API
2. 定义用户信息数据表
3. 编写数据库字典管理功能
  
---
### 2019-12-26  
```
  1 编写字典管理功能
    1.1 [v]新建dict view，位置：client\src\views\app\admin\dict\
    1.2 [v]新建系统管理（admin）路由，字典管理（dict）子路由，位置：client\src\router\app\admin.js
        client\src\router\index.js中 import
        // by freeair
        import adminRouter from './app/admin'
    1.3 [v]页面包含：字典检索区 和 词条检索区两个子页面。
        子页面功能相似，考虑单个子页面采用component方式编写，见dict.vue和dict-item.vue。
        在index页面import 子页面component，组合为整体页面。
    1.4 [v][G]新建表单输入validator汇总文件目录，见client\src\utils\app\validator\
        已编写字典管理页面中表单的validator，见dict_form.js
  
  2 [G]页面操作顺序：
    2.0 查询数据表，首次显示，刷新，或点击搜索按钮，获取数据更细数据显示区。
    2.1 新增1条记录，点击新增按钮，弹出对话框表单。
        对话框表单，提交create。返回新增记录，更新显示。
    2.2 修改1条记录，点击修改按钮，弹出对话框表单。
        对话框表单，提交update。不返回修改记录，更新显示。
    2.3 删除1条记录，点击删除按钮，提示信息，确认提交delete
```
***TODO:***  
1. 编写页面路由管理功能
2. 规范API
3. 编写字典管理功能API
4. 定义用户信息数据表

---
### 2019-12-27
```
  1 编写菜单管理功能
    1.1 [v]新建菜单管理 view，位置：client\src\views\app\admin\menu\
    1.2 [v]系统管理（admin）路由，添加菜单管理（dict）子路由，见：client\src\router\app\admin.js
    1.3 [G]在client\src\component\新建app文件，存放自用的组件。
        存放了第三方icon select 组件
        修改icon路径（requireIcons.js）：require.context('@/icons/svg', false, /\.svg$/)
    1.4 从github添加第三方组件 vue-tree
        npm install --save @riophae/vue-treeselect
        
        // import the component
        import Treeselect from '@riophae/vue-treeselect'
        // import the styles
        import '@riophae/vue-treeselect/dist/vue-treeselect.css'
```
***TODO:***  
1. x

---
### 2019-12-29
```
  1 编写restful api模块。
    [c]composer 引入
    composer require chriskacerguis/codeigniter-restserver
    Note that you will need to copy rest.php to your config directory (e.g. application/config)
    1.1 打算不用put 和 delete方法，用post方法模拟
    POST的form, 通过隐藏的input传， 比如rails里就是
    input name=\"_method\" type=\"hidden\" value=\"put\"
    网络服务器默认没有开启PUT和DELETE，我们可以在header中使用X-HTTP-Method-Override，通过POST来发送PUT请求。这样的话，服务器会把它当做一个POST请求，而REST服务器会把它作为PUT操作处理。
    
  2 [v]编写菜单管理功能的api。
    [G]按照view文件结构，创建api文件结构。
    client\source\api\app目录，创建admin\menu目录，存放菜单管理的api文件。
    
```
***TODO:***  
1. 规范API 


---
### 2020-01-01
```
  1 编写API模块，先编写menu管理的api。
    # 前端页面路由，根据页面目录菜单的层级，定义前端路由，例如：
    系统管理目录(admin)，包含：词典管理(dict)，菜单管理页面(menu)。
    url：
    //site/admin/dict
    //site/admin/menu
    # 前后端API接口，根据后端存储的资源类型定义api名，url路径中统一加入api子路径，可与前端路由的区分开来，例如：
    菜单管理页面，与后端的api接口，url：//site/api/menu
    
    1.1 [v][G]按照view文件结构，创建api文件结构。
        # @\src\api\app目录，创建admin\menu目录，存放菜单管理的api文件。
        # api函数命名加前缀api，例如apiGetXXX()
          export function apiGetMenu(id) {
            return request({
              url: '/api/menu',
              method: 'get',
              params: {
                id
              }
            })
          }
    
    1.2 [c]@\application\controllers目录下新建api文件夹，统一存放api接口文件。
        # 每一类资源单独建一个controller文件，基于restful。例如，菜单-Menu.php等。
        # routes.php中，定义路由控制器映射：$route['api/menu'] = 'api/menu'
        # @\application\controllers\api\Menu.php编写控制器
          public function index_post()
          {
            $id = $this->post( 'id' );

            $data = ['id' => $id];
            $this->response( $data, 201 );
          }
          
  2 编写menu数据表，用户用api从后端获取，返回更新页面。
    [G]数据库：
    # 创建数据库binglang
    # 创建数据表，加前缀app_
  
```
***TODO:***  
1. 编写新增数据表功能
2. 编写修改数据表功能
3. 编写删除数据表功能

---
### 2020-01-05
```
1 以menu管理页面，编写新增数据表功能
```
***TODO:***  
1. 编写修改数据表功能
2. 编写删除数据表功能
3. 编写动态路由，权限管理

---
### 2020-01-16
```
  1 编写user页面
  
  # 约定
  1. dict.name 字段，含user_attr_ 表示 user表 附加属性项
  2. user_attribute表
    `user_id` 
    `dict_data_id`

  3. 相关table:
      app_user，app_user_attribute，app_users_roles
        app_dict_data，app_dict
        app_role

  4. 新建：
  4.1 步骤：
      检索dict表name字段含user_attr_，得到dict.id, dict.label （array-1）
      dict.label作为form单项label
      通过dict.id 检索dict_data 表，得到dict_data.id, dict_data.label （array-2）
      （array-2）作为form表单项select content
      
      读app_dict, app_dict_data，app_role，app_dept, app_job
      写app_user，app_user_attribute，app_users_roles
  4.2 数据格式：
    [
      {
        dict.label,
        [
          {
            dict_data.id,
            dict_data.label
          },
          {
            dict_data.id,
            dict_data.label
          }
        ]
      },
      {
        dict.label,
        [
          {
            dict_data.id,
            dict_data.label
          },
          {
            dict_data.id,
            dict_data.label
          }
        ]
      },
    ]
    
    extraAttribute: []
    
    4.3 API:
    apiGetUser, params: wanted: new_form
    
  
  5. 编辑：
  5.1 步骤：
      检索dict表name字段含user_attr_，得到dict.id, dict.label （array-1）
      dict.label作为form单项label
      通过dict.id 检索dict_data 表，得到dict_data.id, dict_data.label （array-2）
      （array-2）作为form表单项select content
      
      读app_dict, 读app_dict_data，读app_role，读app_user，读app_user_attribute，读app_users_roles
      写app_user，写app_user_attribute，写app_users_roles
  
  5.2 API:
    apiGetUser, params: wanted: current_form
```
***TODO:***  
1. x


---
### 2020-01-
```
  1 a
```
***TODO:***  
1. x
