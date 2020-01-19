# APP日志
---
### 摘要  
1. 按TODO排序
2. 缩略字: [G]-global [v]-vue [c]-ci @-框架根目录

---
### TODO  
1. [done]初始适配vue和ci框架
2. [done]适配框架文件夹结构
3. [done]设定API和response
4. [done]设定页面crud流程
5. [done]编写 用户管理页面  

. 参照用户管理页面，更新 app其他页面文件  
. 编写用户头像功能   

. 页面 检索功能，适应多查询条件组合  
. 页面 数据显示区分页功能，新增，编辑，删除操作，如何刷新定位 数据显示区  
. 编写动态路由，权限管理  

---
### 1 初始适配vue和ci框架
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

---
### 2 适配框架文件夹结构
```
  1 前端框架 vue-element-admin
  # 按照app的页面结构，在框架各个子目录的app目录下存放app的文件
    新建client\src\app_settings.js文件，自己所需的配置文件
    新建client\src\views\app文件夹，存放app页面
    新建client\src\api\app文件夹，存放app的API
    新建client\src\utils\app文件夹，存放app的公共模块
    新建client\src\router\app文件夹，存放app的公共模块
    新建client\src\component\app文件，存放自用的组件。

  2 后端
    # 新建applications/app 文件夹
      ci/applications/app/
      ci/applications/app/config/
      ci/applications/app/controllers/
      ci/applications/app/libraries/
      ci/applications/app/models/
      ci/applications/app/views/
      
    # 新建app setting文件夹
      ci/app_settings
      使用：composer.json中设置加载
      "autoload": {
        "psr-4": {
            "App_Settings\\": "app_settings/"
        }
      },

    # 修改 index.php 文件
      设置：$application_folder = 'applications/app';
      
    # 控制器划分子目录管理
      # 新建api 控制器子目录
        ci/applications/app/controllers/api
      # 添加routes config
        // api
        $route['api/menu']      = 'api/menu';
      # 使用：
        URI 的第一段必须指定目录，例如，
        控制器：ci/applications/app/controllers/api/Menu.php
        访问该控制器，你的 URI 应该像下面这样:
        example.com/api/menu
```

---
### 3 设定API和response
```  
  1 前端http request引入axios
    1.1 设置 base url
        const service = axios.create({
          baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
        
        .env.production 文件设置
        # 调试用
        VUE_APP_BASE_API = 'http://127.0.0.1'
        
    1.2 引入Qs 序列化组件，序列化Get请求params，Post请求form
        service.defaults.transformRequest = [function(data) {
          return qs.stringify(data, { arrayFormat: 'indices' })
        }]

        service.defaults.paramsSerializer = function(params) {
          return qs.stringify(params, { arrayFormat: 'indices' })
        }
  
  2 CI框架引入restful api组件。
    # composer require chriskacerguis/codeigniter-restserver
    # 注意 手动复制 rest.php 文件至app config目录 (e.g. application/app/config)
    # [optional]用post方法模拟put 和 delete的方法
      POST的form, 通过隐藏的input传， 比如rails里就是
      input name=\"_method\" type=\"hidden\" value=\"put\"
      网络服务器默认没有开启PUT和DELETE，我们可以在header中使用X-HTTP-Method-Override，通过POST来发送PUT请求。这样的话，服务器会把它当做一个POST请求，而REST服务器会把它作为PUT操作处理。
    
  3 API接口
    2.1 前端api四种：apiGetxxx, apiPostxxx, apiPutxxx, apiDelxxx 对应crud操作
        # apiGetxxx，要适应多种查询组合场景
        
  4 response定义
    $this->response($res, http_status_code);
    # 
    $res['code'] - must，见ci/app_settings/App_Code.php
    $res['msg'] - optional，后端处理失败/错误，提示字符串，见ci/app_settings/App_Msg.php，供前端message显示提示用户信息。
    $res['data'] - optional，用户请求服务端的数据，查询数据库的输出为 关联数组。
    
    组合：
      # 成功 
        res['code'] = App_Code::SUCCESS
        res['data'] =
      # 成功，比如 删除操作
        res['code'] = App_Code::SUCCESS
        res['msg'] =
      # DB操作失败
        res['code'] = App_Code::FAILED_CODE
        res['msg'] = 
      # 流程失败
        res['code'] = App_Code::FAILED_CODE
        res['msg'] = 
  
  5 Http状态码
    # 200 表示操作成功，但是不同的方法可以返回更精确的状态码。
      GET: 200 OK
      POST: 201 Created
      PUT: 200 OK
      PATCH: 200 OK
      DELETE: 204 No Content
      上面代码中，POST返回201状态码，表示生成了新的资源；DELETE返回204状态码，表示资源已经不存在。
      此外，202 Accepted状态码表示服务器已经收到请求，但还未进行处理，会在未来再处理，通常用于异步操作

    # 300
      API 用不到301状态码（永久重定向）和302状态码（暂时重定向，307也是这个含义），因为它们可以由应用级别返回，浏览器会直接跳转，API 级别可以不考虑这两种情况。
      API 用到的3xx状态码：
      303 See Other，表示参考另一个 URL，是"暂时重定向"，用于POST、PUT和DELETE请求，收到303以后，浏览器不会自动跳转，而会让用户自己决定下一步怎么办。
      302和307也是"暂时重定向"，用于GET请求。

    # 4xx，表示客户端错误。
      400 Bad Request：服务器不理解客户端的请求，未做任何处理。
      401 Unauthorized：用户未提供身份验证凭据，或者没有通过身份验证。
      403 Forbidden：用户通过了身份验证，但是不具有访问资源所需的权限。
      404 Not Found：所请求的资源不存在，或不可用。
      405 Method Not Allowed：用户已经通过身份验证，但是所用的 HTTP 方法不在他的权限之内。
      410 Gone：所请求的资源已从这个地址转移，不再可用。
      415 Unsupported Media Type：客户端要求的返回格式不支持。比如，API 只能返回 JSON 格式，但是客户端要求返回 XML 格式。
      422 Unprocessable Entity ：客户端上传的附件无法处理，导致请求失败。
      429 Too Many Requests：客户端的请求次数超过限额。

    # 5xx，表示服务端错误。一般来说，API 不会向用户透露服务器的详细信息，所以只要两个状态码就够了。
      500 Internal Server Error：客户端请求有效，服务器处理时发生了意外。
      503 Service Unavailable：服务器无法处理请求，一般用于网站维护状态。
    
  6 axios响应拦截
    service.interceptors.response.use(
      response => { 
        // http状态码在2xx内的处理
      },
      error => {
        // http状态码在2xx外的处理
      }
    
    # 示例 http状态码2xx：
      # 后端：$this->response($res, 200);
      # axios响应拦截：
        service.interceptors.response.use(
          response => {
            const res = response.data
            // eslint-disable-next-line eqeqeq
            if (res.code == 0) {
              if (typeof res.msg !== 'undefined') {
                this.$message({
                  message: res.msg,
                  type: 'success'
                })
              }
              if (typeof res.data !== 'undefined') {
                return Promise.resolve(res.data)
              } else {
                return Promise.resolve()
              }
            } else {
              const msg = res.msg + ' (' + res.code.toString() + ')'
              return Promise.reject(msg)
            }
          },
      
      # api调用处理：
        apiGetUser(params)
          .then(function(data) {
            // 页面取回服务端data
            this.data = data
          }.bind(this))
          .catch(function(err) {
            // 服务端失败/错误，提示
            this.$message({
              message: err,
              type: 'warning'
            })
          })
    
```

---
### 4 设定页面crud流程
```
  1 页面操作顺序：
    1.0 首次显示，刷新，或点击搜索按钮，查询数据表，更细数据显示区。
    1.1 新增1条记录，点击新增按钮，弹出对话框表单，preCreate。
        对话框表单，提交doCreate。响应处理：提示，更新数据显示区。
    1.2 修改1条记录，点击修改按钮，弹出对话框表单，preUpdate。
        对话框表单，提交doUpdate。响应处理：提示，更新数据显示区。
    1.3 删除1条记录，点击删除按钮，提示，确认，doDelete。响应处理：提示，更新数据显示区。
```

---
### 5 编写 用户管理页面
```
  1 编写user页面
  
  # 约定
  1 dict.name 字段，含user_attr_ 表示 user表 附加属性项
  2 user_attribute表
    `user_id` 
    `dict_data_id`

  3 相关table:
      user，user_attribute，users_roles
        dict_data，dict
        role

  4 新建：
    4.1 步骤：
      检索dict表name字段含user_attr_，得到dict.id, dict.label （array-1）
      dict.label作为form单项label
      通过dict.id 检索dict_data 表，得到dict_data.id, dict_data.label （array-2）
      （array-2）作为form表单项select content
      
      读dict, dict_data，role，dept, job得到select 列表
      写user，user_attribute，users_roles
      
      # 确保额外属性项在 dict 和 user_attribute 的读，写 顺序一致，与页面显示一致，特别核对“编辑”
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
  
  5 页面用户信息包含多条，单个表单显示，画面较长，划分两个表单，使用el-tabs 切换表单。
    # 注意，单个表单的validate和tab的跳转。
  
  6 db操作，比如 批量insert多条，其中一条失败后，事务处理测试
  
  7 确保额外属性项在 dict 和 user_attribute 的读，写 顺序一致，与页面显示一致，特别核对“编辑”
    # 新增时，id递增；查询时，order by id保证顺序；外键引用id，约束保证删除一致。更新时，id不变，仅label变化，对应顺序一致。
      分析如下：
      # 读 dict 按ida升序
      # 读 dict_data 按idb升序
      # 页面
      ida-1
        idb-1
        idb-2
      ida-2
        idb-3
        idb-4
        
      # 提交user_attribute table
      uid  (ida-1) idb-2
      uid  (ida-2) idb-3

      # preUpdate读 user_attribute table 按idb升序
      (ida-1) idb-2
      (ida-2) idb-3

      删除ida或idb
      user_attribute table 引用 dict_data table 引用 dict table  外键约束
  
  8. 编辑：
  8.1 步骤：
      检索dict表name字段含user_attr_，得到dict.id, dict.label （array-1）
      dict.label作为form单项label
      通过dict.id 检索dict_data 表，得到dict_data.id, dict_data.label （array-2）
      （array-2）作为form表单项select content
      
      读dict, dict_data，role，dept, job得到select 列表，读user，user_attribute，users_roles
      写user，user_attribute，users_roles
  
  8.2 API:
    apiGetUser, params: wanted: current_form
    
  9 insert, update 入参检查
    # 数据表 主键，外键字段 不能为空，且注意唯一性
    # 注意检查多维数组入参。比如；a = [[], []], empty(a)不为空
    
  10 删除: 
      开启事务
      user_attribute，users_roles, user
```

---
### 
```

```
