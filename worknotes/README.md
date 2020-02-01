# APP Worknotes
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
5. 用户管理页面  
   [done]数据显示区分页，新增，编辑，删除操作，显示变化如何呈现用户  
   [done]用户管理页面 检索功能，适应多条件组合  
   [done]后端输入验证  
   [done]前端post, put, delete请求 content-type ；application/json，后端需支持json输入
   埋点  
6. 后端log  
7. 前端log  
8. [done]编写用户头像功能   
9. [done]table列动态显示/隐藏功能  
10. 参照用户管理页面，更新 app其他页面文件
    创建search组件
    [done]search 组件，watch输入框，输入字母时，触发多次change事件，导致发往后端多条无效请求。  
    [done]search组件传递表单验证rule。   

.  dict data 已测试read，待测试create，update，del，增加检索字段。     
. 【待测试】有A，但没有A1，A2，user_attribute_dynamic_list去除A的部分   
. 【待测试】场景：A，B属性，已添加user。新增C属性，查询，新建，编辑user功能   
. 参照用户管理页面，更新 app其他页面文件  
. 编写动态路由，权限管理  
  用户认证，访问api权限认证  
. 埋点  
. 后端，用户数据/文件的存放文件位置，和访问权限。  
  数据库权限，centos文件路径权限  

create时，没有选择job_id，前端‘’，后端处理，当‘’时，insert语句不含job_id，而table定义时，job_id default null, 则读取时，后端返回前端 job_id null
update时

---
### 置顶
```
# JS
  1 Undefined类型只有一个值，即undefined。当声明的变量还未被初始化时，变量的默认值为undefined。
    Null类型也只有一个值，即null。null用来表示尚未存在的对象，常用来表示函数企图返回一个不存在的对象。
  2 对象属性的访问一般是通过obj.attr的方式来访问的，或者是obj[attr]的方式来进行操作.
    属性访问的时候，当对象存在而对象中没有这一属性的时候，如果程序中访问了当前的属性的话我们，js的解析器将会返回undefined的数值.
    但是如果当前需要访问的对象是不存在的，这个时候js就会返回一个类型错误
  3 JS 空对象
    空引用：obj=null 是指变量值指向null变量，当然在js默认不赋值的情况下，一个变量为undefined.
    原型：原型上包括了继承属性，有可以枚举的属性和不可以枚举的属性。默认对象都继承了Object。
    自身：自身属性同样包括了可枚举的属性和不可枚举的属性。
    
    Object.keys(obj)返回不包括原型上的可枚举属性，即自身的可枚举属性。
      Object.keys(data).length === 0;
    Objcet.getOwnPropertyNames(obj)返回不包括原型上的所有自身属性(包括不可枚举的属性)
      Object.getOwnPropertyNames(data)===0;
  
  4 qs 序列化
    undefined或空数组，axios post 提交时，qs不填入http body。

# PHP
  1 检查多维数组入参。比如；a = [[], []], empty(a) false
  2 isset - 检测变量是否已设置并且非 NULL。比如；isset('') true
  3 empty - 判断一个变量是否被认为是空的。当一个变量并不存在，或者它的值等同于FALSE，那么它会被认为不存在。如果变量不存在的话，empty()并不会产生警告。
    以下的东西被认为是空的：
      "" (空字符串)
      0 (作为整数的0)
      0.0 (作为浮点数的0)
      "0" (作为字符串的0)
      NULL
      FALSE
      array() (一个空数组)
      $var; (一个声明了，但是没有值的变量)
  4 未申明变量，empty($a) - true, isset($a) - false
  
  5 后端api验证client数据
    1 client数据中，包含规定的字段，valide 检查合法。
    2 client数据中，不包含规定的字段，valide 检查存在。
    3 client数据中，包含不规定的字段，只取valide 通过的规定字段。
  
# CI  
  1 ci 查询数据表结果->result_array()，为数组结构。
    示例1 table：id - 1, 查询结果：array(1) [{id:1}] 
    示例2 table：id - 1, id - 2，查询结果：array(2) [{id:1}, {id:2}]

  2 RestController
      # 场景一：
        $this->get('a');  
        当client没有参数a时，返回NULL
      # 场景二：
        $data = $this->get();
        $data['a'];
        当client没有参数a时，php提示Undefined index: a。 一般作为正式的网站会把提示关掉的，甚至连错误信息也被关掉。
  
# DB
  1 外键字段，比如外键某个id，前端表单没有输入，后端收到的是''，写数据表时，将报错。
  2 select语句完整语法
      SELECT 
      DISTINCT <select_list>
      FROM <left_table>
      <join_type> JOIN <right_table>
      ON <join_condition>
      WHERE <where_condition>
      GROUP BY <group_by_list>
      HAVING <having_condition>
      ORDER BY <order_by_condition>
      LIMIT <limit_number>
      
      from →join →on →where →group by→having→select→order by→limit
      
# VUE
  1 vue子组件props类型-Object，是父组件传入对象的引用。
  2 vue 修饰符sync的功能是：当一个子组件改变了一个 prop 的值时，这个变化也会同步到父组件中所绑定。
  
```

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
  
  2 新增页面
    新增页面，添加文件如下：
    # 前端：
    1. src/router/路由.js
    2. src/views/app/视图.vue
    3. src/api/app/接口.js

    # 后端：
    1. application/app/config/routes.php
    2. application/app/config/app_config.php 加数据表
    3. application/app/controller/api/控制器.php   修改post, put参数
    4. application/app/model/模型.php  数据库接口
```

---
### 5 编写用户管理页面
```
  1 编写user页面
  
  # 约定
  1 dict.name 字段和dict_data.name，含user_attr_前缀 表示 user表 附加属性项
  2 user_attribute表
    `user_id` 
    `dict_data_id`

  3 相关table:
      user，user_attribute，users_roles
        dict_data，dict
        role
  
  4 页面用户信息包含多条，单个表单显示，画面较长，划分两个表单，使用el-tabs 切换表单。
    # 注意，单个表单的validate和tab的跳转。
  
  5 db操作，比如 批量insert多条，其中一条失败，事务处理测试
  
  6 确保额外属性项在 dict 和 user_attribute 的读，写 顺序一致，与页面显示一致，特别核对“编辑”
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
  
  7 流程：
      dict table:
      attribute 类
      
      dict data table       fk dict
      attribute 可选值
      
      user_extra table:     fk dict data
      uid, dict data id
      
      示例：
      A，B两类
      A类，可选值 有A1 A2
      B类，可选值 有B1 B2
      
      dict table：
      1 A
      2 B
      
      dict data table：
      1 A1 (fk 1)
      2 A2 (fk 1)
      3 B1 (fk 2)
      4 B2 (fk 2)
      
      新加用户form显示正确。
      当A域不选，B域选B1，前台提交 extra_attributes: [null, 4]
      # 影响查询，新建，编辑方法
      
      
      约定：
      dict 表name字段 -- user_attr_AA
      dict_data 表name字段 -- user_attr_AA_BBBB
      
      方案：
      1 pre create: form
        后端
        user_attribute_category = select id, label from dict where name like user_attr_% order by id
        foreach user_attribute_category
          user_attribute_data = select id, label from dict data where fk = user_attribute_category.id
          
        【response】user_attribute_dynamic_list = [
                                [label: user_attribute_category.label,
                                 sub_list: user_attribute_data
                                ],
                              ]
        示例：
        [
          [A,
           [[1, A1], [2, A2]]
          ],
          [B,
           [[3, B1], [4, B2]]
          ]
        ]
        
        【待测试】有A，但没有A1，A2，user_attribute_dynamic_list去除A的部分
        【待测试】场景：A，B属性，已添加user。新增C属性，查询，新建，编辑user功能？？？？
      
        前端：
        length = user_attribute_dynamic_list
        【表单v-model】user_attribute = array(length).fill('')
        
      2 do create: 
        前端:
        提交 user_attribute
        
        示例：
        ['', ''] 或 ['', B2] 或 [A1, ''] 或 [A2, B1]
        
        后端：
        '' 不写入table
        
      3 查询：
        后端：
        【response】dynamic_columns = select label, name from dict where name like user_attr_%

        uid = select id from user where ... order by sort
        
        uid_to_attribute = select dict data id from user attribute where user id = uid
        
        user_attribute_data = slect label, name from dict data where id in uid_to_attribute.id
        
        foreach ($dynamic_columns as $category) {
            $user[$category['name']] = '';
        }
        foreach ($dynamic_columns as $category) {
            foreach ($user_attribute_data as $item) {
                // 转小写字母
                if (strpos(strtolower($item['name']), strtolower($category['name'])) !== false) {
                    $user[$category['name']] = $item['label'];
                }
            }
        }
        
      4 pre update:
        前端：
        提交uid
        
        后端：
        pre create: form
        
        //
        dynamic_columns = select label, name from dict where name like user_attr_%
        
        uid_to_attribute = select dict data id from user attribute where user id = uid
        
        user_attribute_data = slect id, name from dict data where id in uid_to_attribute.id
        
        $i = 0;
        foreach ($dynamic_columns as $category) {
            foreach ($user_attribute_data as $item) {
                // 转小写字母
                if (strpos(strtolower($item['name']), strtolower($category['name'])) !== false) {
                    $user_attribute[$i] = $item['id'];
                }
            }
            $i++;
        }
        
        【response】$user_attribute
  
  8 password
      不是必改项，后端识别password = ‘’，跳过hash_password
  
  10. 删除: 
      开启事务
      user_attribute，users_roles, user
  
  11. 前端post, put, delete请求 content-type ；application/json，后端需支持json输入
      1 背景：当params含空数组时，qs序列化，自动去除空数组，扰乱了后端基于前后端 数据定义的输入验证。调整前端post, put, delete请求body使用json格式，get请求仍使用qs序列化参数。
      2 调整点：
        1 前端，@/src/utils/request.js文件，headers: { 'Content-Type': 'application/json' },
          const service = axios.create({
            baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
            withCredentials: true, // send cookies when cross-domain requests
            headers: { 'Content-Type': 'application/json' },
            timeout: 5000 // request timeout
          })
          
          JSON.stringify(data)
          service.defaults.transformRequest = [function(data) {
            return JSON.stringify(data)
          }]
          
        2 后端CI api，采用chriskacerguis restful组件(不支持解析json输入)，在控制器post,put,delete方法入口
          $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
          $client       = json_decode($stream_clean, true);
          
  12. 后端验证
      1 CI库提供了form validation library，处于以下需求，手动修改了CI原生form validation适应GET,POST,PUT,DELETE请求数据，且检查数据为空数组。
        1 在前后端分离模式下，原生实现中判断了HTTP method post，导致相同输入数据，在post api和put api的验证输出不一致。
        2 当PUT数据为空数组时，比如PUT []，原生跳过验证。前后端分离和restful api场景，前端更新表单，PUT方法提交数据，需校验字段是否存在。
      2 修改form validation library
        1 复制form validation.php 文件至@/application/app/library，命名为App_form validation.php，同步修改class名App_form_validation
        2 校验规则集文件，@/application/app/config/app_form_validation.php，CI机制，load library时，类的construct函数会自己动加载config路径下同名配置文件。
        3 修改App_form_validation类
          1 去掉入口的if
            public function set_rules($field, $label = '', $rules = array(), $errors = array())
            {
              // No reason to set rules if we have no POST data
              // or a validation array has not been specified
              if ($this->CI->input->method() !== 'post' && empty($this->validation_data))
              {
                return $this;
              }
          2 直接赋值$this->validation_data，不用POST数组。
            public function run($group = '')
            {
              $validation_array = empty($this->validation_data)
                ? $_POST
                : $this->validation_data;
            .
            .
            .
            屏蔽 $this->_reset_post_array()
            // Now we need to re-set the POST data with the new, processed data
            empty($this->validation_data) && $this->_reset_post_array();
            
          
          【注意】修改后，当输入某个域的值为[]时，比如PUT ['idx': []]，规则检查idx域返回true。
          
      3 后端验证约定：
        1 前后端协商定义每个api 请求的数据结构
        2 根据请求数据结构，定义校验规则集
        3 api入口，获取提交的数据
        4 进行校验，分场景：
          1 client数据中，包含规定的字段，valide 字段合法。
          2 client数据中，不包含规定的字段，valide 字段存在。
          3 client数据中，包含不规定的字段（不在验证规则集中，不会检查），只取valide 通过的规定字段，忽略超出规定的字段。
        5 流程：
          获取输入 -- 执行数据验证 -- 验证失败，响应client -- 验证通过，业务处理，失败，响应client。
          当验证通过，业务处理结束，api结束时，response合理的信息。
        
      4 示例：
        1 api方法入口：
            public function index_get()
            {
                $client = $this->get();
                $valid = $this->common_tools->valid_client_data($client, 'user_index_get');
            
            public function index_post()
            {
                $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
                $client       = json_decode($stream_clean, true);
                $valid = $this->common_tools->valid_client_data($client, 'user_index_post');
        
        2 @/application/app/libraries/Common_tools.php 
            public function valid_client_data($array = [], $rules = '')
            {
                if (empty($rules)) {
                    return false;
                }
                $this->form_validation->reset_validation();
                $this->form_validation->set_data($array);
                if ($this->form_validation->run($rules) == false) {
                    return $this->form_validation->error_string();
                } else {
                    return true;
                }
            }
        
        3 @/application/app/config/app_form_validation.php  
          支持域值为 数组类型，当某个域的值为[]时，比如PUT ['idx': []]，规则检查idx域返回true。
            $config = [
                'user_index_post' => [
                    [
                        'field'  => 'role_ids[]',
                        'label'  => 'role_ids',
                        'rules'  => [
                            ['valid_role_ids',
                                function ($str = null) {
                                    // field is required
                                    if (!isset($str)) {
                                        return false;
                                    }
                                    // e.g. number no zero
                                    return ($str != 0 && ctype_digit((string) $str));
                                },
                            ],
                        ],
                        'errors' => ['valid_role_ids' => '请求参数非法！role_ids'],
                    ],


            $config['error_prefix'] = '';
            $config['error_suffix'] = '';
            
      5 新建时，job域不填，提交域值''，后端写DB时判断===''，跳过该字段，而table该字段default null.
        编辑时，读取到job域值null，不影响页面显示含义。保持job域不变，提交编辑，后端验证不通过。
        原因：查询时后端返回null，不影响页面显示，但提交null，验证不通过。
        方案：前端接收''，不影响页面显示的含义，后端查询返回前，遍历结果，将null 替换为''。
          @/application/app/model/user_model.php  
            public function get_form_by_user_edit
              $user = $this->_replace_null_field_in_user_array($query->result_array()[0]);
            
            protected function _replace_null_field_in_user_array($array = [])
            {
                if (empty($array)) {
                    return true;
                }

                foreach ($array as &$v) {
                    if ($v === null) {
                        $v = '';
                    }
            }
            return $array;
            }
            
        对于dept_id域，不能按此方案处理。
        原因：dept_id域绑定 treeselect组件（不选时，组件返回undefined），而undefined域值，在json序列化时，去除了此域，则后端接收不到此域。
        方案：前端dept域必填，初始默认值。
        
  
  12. table 增加显示attribute列
      # 流程
        查询user table
        !empty(user)
          获取列表头prop(extra_columns)：查询dict表 select label, name like(name, user_attr_)，order by(id)
          获取user extra attr：
            查询user_attribute表 select dict_data_id where user_id, order by dict_data_id;
            查询dict_data表 select label where_in dict_data_id
      
      # 适配动态隐藏列
        因为 用户extra attribute 从后端读取，有延时，添加标志initTableDone，收到extra attribute后，只更新一次[mixin]updateColumns(),支持pre-hide。
        使用Vue.set( 用于组件中data 对象，追加新属性。
        使用this.$nextTick(() => {， 用于等待view更新完毕，再读取view中元素。
        使用v-for  v-if，用于隐藏控制。
  
  13. 数据显示区分页，新增，编辑，删除操作，显示变化如何呈现用户（分页，limit）
      # 前端 
        api 入参：limit: string e.g. num_offset
        
        pagechange事件，调用refreshTblDisplay()
        
      # 后端：
        if (!empty($limit)) {
            $limit_temp = explode('_', $limit);
            $num        = (int) $limit_temp[0];
            $offset     = (int) $limit_temp[1];
            $this->db->limit($num, $offset);
        }
        $total_rows = $this->db->count_all($this->tables['user']);
        返回 table总行数。
        
      # 新增一行
        对于DB table，新行肯定在表的末尾。查询时order by(sort)，结果集新行不一定在最后一行。
        对于user 表，有“工号”列，insert时，工号既不是递增，也不是递减，则工号列 乱序。比如：insert了小强-1，小猪-5，小芳-3
        
        【不处理】insert 小明-4，怎么让页面显示 定位在 用户刚新增的那一行？？
        # insert增加分页数
        # 有3个分页，在任意分页，点击新增按钮
        
        操作成功response
          .then(function() {
              this.tableTotalRows = this.tableTotalRows + 1
              this.$nextTick(() => {
                this.refreshTblDisplay()
              })
      
      # 最后一分页，最后一行，删除处理
        1 current-page添加.sync修饰符
          <el-pagination
            :page-sizes="[5, 10, 30]"
            :page-size="pageSize"
            :current-page.sync="pageIdx"
            
        2 删除操作成功response
          .then(function() {
              if (this.tableTotalRows > 0) {
                this.tableTotalRows = this.tableTotalRows - 1
              }
              this.$nextTick(() => {
                this.refreshTblDisplay()
              })
          this.tableTotalRows - 1 改变总行数，触发el-pagination组件内更新current-page，借助current-page.sync，更新反馈给父组件。
          this.$nextTick 等待current-page更新，再调用api刷新table显示区        
  
  14. 页面 检索功能，适应多条件组合
      # 约定
        1 前端提交查询条件数据，后端验证
        2 前端使用GET方法场景：
          1 新加用户时，请求后端填表信息。
          2 编辑用户时，请求后端用户信息。
          3 查询时，将查询条件提交后端。
        3 基于GET方法场景，约定：
          1 不同场景下GET方法，params不一样，含不同字段。
          2 每个场景，每个字段需包含在params内。若页面输入框等，用户不填写，前端处理对应字段为''。
          3 后端验证：
            1 规则定义：每个字段isset() == false or == ''，return true
            2 业务func：前端提交的数据通过valid后，业务func对接收到的前端数据再判断，isset() == true，执行业务处理。
          
          
      1 查询语句
        SELECT 
        <select_list>  -- 需适配 select: string
        FROM <left_table>  -- api指定访问的table
        <join_type> JOIN <right_table>
        ON <join_condition>
        WHERE <where_condition> -- 需适配 where, where_in, like, ...
        GROUP BY <group_by_list>
        HAVING <having_condition>
        ORDER BY <order_by_condition>
        LIMIT <limit_number>  -- 需适配 分页读取 limit: string e.g. num_offset
      
      2 查询条件分类；
        1 A 个性，比如：工号，中文名，手机号，身份证号，邮箱
        2 B1，B2，B3 共性，多条件组合“且”关系，比如：性别，部门，岗位，党派，职称
        
        # 示例：
          select * from user where 工号 like A or where 中文名 like A
          select * from user where 性别 like B1 and where 部门 like B2 and where 岗位 like B3
          
          分页，页面跳转如何携带 查询条件？？
      
      3 场景：
        1 A ！= ''，（ where 工号 like A or 中文名 like A ）
        2 B1 ！= ''， （ where 工号 like A or 中文名 like A ） and  性别 = B1
        3 B2 ！= ''， （ where 工号 like A or 中文名 like A ） and  性别 = B1 and 部门 like B2
        4 ...
        
      4 实现：
        1 A ！= ''，（ where 工号 like A or 中文名 like A ）
          【去重】？？？
        
        2 字段-部门，树形结构，比如，工作室以下有小组1，小组2。当查询 工作室时，需要其下所有子节点的user。
          # 查询select id from dept like label %str% group by id  【去重】
          # 查询子节点，合并id，并去重
            如果没有匹配的部门，说明 最终查询结果是空，不用往后执行查询语句，提前返回前台。
          # 添加( dept_id in id集合)
          
        3 字段-岗位，比如，不同部门下有相同岗位，开发组1，开发组2下都有开发员。当查询 开发员时，列出各个部门user。
          # 查询select id from job like label %str% group by id  【去重】
            如果没有匹配的岗位，说明 最终查询结果是空，不用往后执行查询语句，提前返回前台。
          # 添加( job_id in id集合)
          
        4 字段-党派。
          # 查询select id from dict_data where name like 'user_attr_politic%' and label like %str% group by id  【去重】
          # 查询select user_id from user_attribute where dict_data_id in () group by user_id  【去重】
            如果没有匹配的，说明 最终查询结果是空，不用往后执行查询语句，提前返回前台。
          # 添加( id in id集合)
```

---
### 6 后端log
```
  6.1 后端
      PHP引入Seaslog
      # php.ini文件
        extension=seaslog
        seaslog.default_basepath="D:/www/binglang/server/application/app/logs"
        seaslog.default_logger=default
        seaslog.default_datetime_format = "Y-m-d H:i:s"
        seaslog.default_template = "%T (%t) [%L] [%F] [%C] %P | %Q | %R | %m | %M "
        seaslog.disting_folder = 1
        seaslog.disting_type=0
        seaslog.disting_by_hour=0
        seaslog.use_buffer=1
        seaslog.buffer_size=100
        seaslog.level=8
        seaslog.trace_error=1
        seaslog.trace_exception=0
        
        2020-01-20 19:45:28 (1579520728.125) [WARNING] [User.php:32] [User::index_get] 9096 | 5e2592d80fc9e | /api/user?wanted=all | GET | hello log! 
        # 格式
          seaslog.default_template = "%T (%t) [%L] [%F] [%C] %P | %Q | %R | %m | %M "
      
      # level
        seaslog.level = 8 记录的日志级别.默认为8,即所有日志均记录。
        seaslog.level = 0 记录EMERGENCY。
        seaslog.level = 1 记录EMERGENCY、ALERT。
        seaslog.level = 2 记录EMERGENCY、ALERT、CRITICAL。
        seaslog.level = 3 记录EMERGENCY、ALERT、CRITICAL、ERROR。
        seaslog.level = 4 记录EMERGENCY、ALERT、CRITICAL、ERROR、WARNING。
        seaslog.level = 5 记录EMERGENCY、ALERT、CRITICAL、ERROR、WARNING、NOTICE。
        seaslog.level = 6 记录EMERGENCY、ALERT、CRITICAL、ERROR、WARNING、NOTICE、INFO。
        seaslog.level = 7 记录EMERGENCY、ALERT、CRITICAL、ERROR、WARNING、NOTICE、INFO、DEBUG。
      
      CI DB调试error显示控制
        application/app/config/database.php文件
        ['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
        production - db_debug = FALSE
        
        获取DB error
        $error = $this->db->error(); // Has keys 'code' and 'message'
        
  TODO: 
    log展示
```

---
### 7 前端log
```
  逻辑
  1 流程：
    # error钩子：window，vue， pormiss
    # client缓存log，indexDB
    # client清理log缓存
    # 需用户手动提交缓存log。考虑日志数据多，取最近x条log。后台下发x，x暂定10条。
    # 后端响应存储log，写文件，写db？
    
  2 log字段
    # 用户会话ID，与后台对应
      Seaslog::getRequestID
    
  3 log页面
  
    isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
  
  格式：
  timestamp | 会话 | level | description | log信息
    log信息 {
      url
      message
      name
      stack
      ua
    }
  
  实现
  1 vue errorHandler ：
    # Vue.config.errorHandler = function(err) {
      const {
        message, // 异常信息
        name, // 异常名称
        stack // 异常堆栈信息
      } = err
  
    # 示例打印：
      {message: "b is not defined", name: "ReferenceError", stack: "ReferenceError: b is not defined↵    at a.handleQu…e/dist/static/js/chunk-libs.9e2f0126.js:18:51770)"}
        message : "b is not defined"
        name : "ReferenceError"
        stack : "ReferenceError: b is not defined↵    at a.handleQuery (http://127.0.0.1/resource/dist/static/js/chunk-b7754a92.7d401a55.js:1:13133)↵    at ne (http://127.0.0.1/resource/dist/static/js/chunk-libs.9e2f0126.js:18:11664)↵    
      window.location.href : http://127.0.0.1/admin/user
      
  2 client缓存log
    # npm引入logline
      npm install logline
      
    # js文件 引入logline
      import logLine from 'logline';
      
    # 使用
      Logline.using(Logline.PROTOCOL.INDEXEDDB, 'binglang')
      Logline.keep(1)

      var appLogger = new Logline('app')
      appLogger.info('description')
      appLogger.error('description', { a: '' })
      appLogger.warn('description')
      appLogger.critical('description', { b: '' })
      
    # 示例打印：
      appLogger.error('vue error', { message: message, name: name, stack: stack, url: url })
      
      data: {message: "b is not defined", name: "ReferenceError", stack: "ReferenceError: b is not defined↵    at a.handleQu…e/dist/static/js/chunk-libs.fc1beddb.js:18:51770)", url: "http://127.0.0.1/admin/user"}
      descriptor: "vue error"
      level: "error"
      namespace: "app"
      time: 1579620015353
  
  TODO: 
    手动上报log至后端
```

---
### 8. 编写用户头像功能
```
  # 前端
    # 引入identicon.js，crypto
      npm install identicon.js --save
      npm install crypto --save 
      
      import crypto from 'crypto'
      import Identicon from 'identicon.js'
      
      var seed = Math.floor((Math.random() * 100) + 1)
      var hash = crypto.createHash('md5')
      hash.update(seed.toString())
      const data = new Identicon(hash.digest('hex'), 178).toString()
      this.imageUrl = 'data:image/png;base64,' + data
    
    # 使用el-upload
        <el-upload
          class="avatar-uploader"
          action="http://127.0.0.1/api/avatar/update" // 服务端地址
          :show-file-list="false"
          list-type="picture"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <img v-if="imageUrl" :src="imageUrl" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon" />
        </el-upload>

  # 后端
    使用library->upload
      public function update_post() // 控制器使用restserver类
      {
          $config['upload_path']   = './resource/avatar/';   // 存放文件相对路径，注：路径是相对于你网站的 index.php 文件的，而不是相对于控制器或视图文件。
          $config['allowed_types'] = 'gif|jpg|png';
          $config['max_size']      = 100;
          $config['max_width']     = 1024;
          $config['max_height']    = 768;

          $this->load->library('upload', $config);

          if (!$this->upload->do_upload('file')) {  // 接收上传
              $res['code'] = 300;
              $res['msg']  = $this->upload->display_errors();

          } else {
              $res['code'] = App_Code::SUCCESS;
              $res['data'] = $this->upload->data();   // 接收完毕的结果
          }

          $this->response($res, 200);
      }
    
    # CI路径定义  位于index.php
      APPPATH: "D:\www\binglang\server\application\app\"
      BASEPATH: "D:\www\binglang\server\system\"
      FCPATH: "D:\www\binglang\server\"
      SELF: "index.php"
      SYSDIR: "system"
  
```

---
### 9. table列动态显示/隐藏功能
```
  1 组件文件：src\components\app\TableOptions
    view: index.vue
      # 子组件属性，类型-Object，其实是父组件传入对象的引用。对应mixin的columns。
      props: { tableColumns: {
        type: Object,
        default: function() {
          return {}
        }
      }},
    mixin: hide-columns.js
      data() {
        return {
          columnOpt: obColumns(), // 混入显示判断函数
          columns: {} // 混入 table列属性 {label, visble}
        }
      },
    
  
  2 父组件引入：
    import TableOptions from '@/components/app/TableOptions/index'
    import hideColumns from '@/components/app/TableOptions/hide-columns'

    components: { TableOptions },
    mixins: [hideColumns()],
    
    # 使用如下，借用column-key="pre-hide"，初始化时，默认隐藏。
      <el-table-column v-if="columnOpt.visible('last_login')" column-key="pre-hide" :show-overflow-tooltip="true" prop="last_login" label="登录日期" />
      
    # 修改table表，只需要<el-table-column属性的修改。
```

---
### 10. 参照用户管理页面，更新 app其他页面文件
```
  1 前端：
    1 模板
      1 添加表头search区，定义search字段，验证规则
      2 添加分页
      3 调整table和dialog-form
    2 JS
      1 import 组件，输入框验证方法，修改api方法名
      2 调整CRUD方法
      3 调整分页，查询方法
  
  2 后端：
    1 定义前端数据验证规则
    2 调整api方法：输入验证，响应code和msg定义
    3 调整model，CRUD
      
  3 search 组件，watch输入框，输入字母时，触发多次change事件，导致发往后端多条无效请求。 
    # 方案：
      去掉watch，使用el-input change，clear事件。组件mounted 发一次change事件，页面刷新自动向后端请求数据。
  
  4 search组件传递表单验证rule
    blur 触发验证
  
```

---
### 
```

```

---
### 
```

```