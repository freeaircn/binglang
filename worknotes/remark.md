# 改造app框架

---
### 简介  
1. 第三方模块加入app中，所需配置
```
[G] [v] [c]
```
---
### 1. GitHub
```
1. 添加用户信息

cmd /c "git config --global user.name "xxx""
cmd /c "git config --global user.email "xxx@163.com""


2. 添加SSH

ssh-keygen -t rsa -C xxx@163.com
# id_rsa是私钥，id_rsa.pub是公钥，登陆GitHub，打开“Account settings”，“SSH Keys”页面在Key文本框里粘贴id_rsa.pub文件的内容


3. 创建并推送本地仓库

1) GitHub创建binglang.git

2) 创建本地仓库，D:\www\binglang，存放项目代码
git init

3) 本地仓库关联远程仓库。项目根目录路径下，执行命令
git remote add origin git@github.com:freeaircn/binglang.git

4) 本地仓库推送至远程仓库
git push -u origin master


4. 复制GitHub仓库 

git clone git@github.com:freeaircn/binglang.git


5. 更新本地仓库  

git pull
```

---
### 2. vs code
```
1 共享vs setting  
使用Settings Sync 插件，实现vs setting上传至GitHub，多地共享vs setting
# Upload Key : Shift + Alt + U
# Download Key : Shift + Alt + D


2 常用插件
1) editorconfig
    插件功能不用手工启动。
    root = true
    [*]
    charset = utf-8
    indent_style = space
    indent_size = 2
    end_of_line = lf
    insert_final_newline = true
    trim_trailing_whitespace = true
    # end_of_line 保存文件时，触发
    # insert_final_newline 保存文件时，触发
    # trim_trailing_whitespace 保存文件时，触发

    [*.md]
    insert_final_newline = false
    trim_trailing_whitespace = false

2) Auto Close Tag
  
3) Auto Rename Tag
  
4) Prettier - Code formatter
    Using Command Palette (CMD/CTRL + Shift + P)
    1. CMD + Shift + P -> Format Document
    OR
    1. Select the text you want to Prettify
    2. CMD + Shift + P -> Format Selection
  
5) Better Align
    对齐赋值符号和注释
    Place your cursor at where you want your code to be aligned, and invoke the Align command via Command Palette or customized shortcut. Then the code will be automatically aligned
    There's no built-in shortcut comes with the extension, you have to add shotcuts by yourself:
    Open Command Palette and type open shortcuts to open keybinding settings
    Add something similar like this:
    { 
    "key": "ctrl+alt+A",  
    "command": "wwm.aligncode",
    "when": "editorTextFocus && !editorReadonly" 
    }

6) koroFileHeader
    文件头部添加注释:
    在文件开头添加注释，记录文件信息
    支持用户高度自定义注释选项
    保存文件的时候，自动更新最后的编辑时间和编辑人
    快捷键：window：ctrl+alt+i,mac：ctrl+cmd+i
    
    在光标处添加函数注释:
    在光标处自动生成一个注释模板，下方有栗子
    支持用户高度自定义注释选项
    快捷键：window：ctrl+alt+t,mac：ctrl+cmd+t

7) Better Comments
  
    注释添加颜色
      /**
       * * A
       * ! B
       * ? C
       * TODO: D
       * @param F
       */

8) Bookmarks
    鼠标右键菜单操作
  
9) Bracket Pair Colorizer

10) Code Spell Checker

11) Highlight matching tag
  
12) gitignore
  
13) Prettify JSON
  
14) String Manipulation
  
15) TODO Highlight

16) TODO Parser
    解析注释TODO
    We support both single-line and multi-line comments. For example:
    // TODO: this todo is valid
    /* TODO: this is also ok */
    /* It's a nice day today
     * Todo: multi-line TODOs are
     * supported too!
     */
    使用：
    状态栏显示当前文件的TODO数目；
    F1输入栏，输入：Parse TODOs
    
17) Vetur

18) Vscode-element-helper
  
19) Phpfmt
  
20) PHP DocBlocker

21) Settings Sync Vscode 
  0599be7e63495e01759ed12907a26cb0
```

---
### 3. vue element admin
##### 3.1 vue-cli3 api_base_url
```
https://blog.csdn.net/weixin_44134899/article/details/88178444

在与package.json同级目录下，文件：.env.production，.env.development
module.exports = { // 生产环境下配置
  NODE_ENV = 'production'
  VUE_APP_BASE_API = 'http://localhost:8085' //请求后台域名
  VUE_APP_VERSION = '0.0.1' // 版本号
}

# just a flag
ENV = 'production'

# base api
VUE_APP_BASE_API = '/prod-api'

官方说明：只有以 VUE_APP_ 开头的变量会被 webpack.DefinePlugin 静态嵌入到客户端侧的包中.
在构建过程中，process.env.VUE_APP_BASE_API 将会被相应的值所取代。

在axios中配置baseUrl:
const service = axios.create({
  baseURL: process.env.VUE_APP_BASE_API, // url = base url + request url
```

---
##### 3.2 vue-cli3 引入自定义scss样式变量
```
(https://stackoverflow.com/questions/49086021/using-sass-resources-loader-with-vue-cli-v3-x)
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
```

---
##### 3.3 添加logo-svg组件
```
模仿icons组件 https://juejin.im/post/59bb864b5188257e7a427c09
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
```

---
##### 3.4 在vue-element-admin 加入app文件目录
```
# 按照view的结构，在框架各个子目录的app目录下存放自己的文件

新建client\src\app_settings.js文件，自己所需的配置文件
新建client\src\views\app文件夹，存放app视图
新建client\src\api\app文件夹，存放app的API
新建client\src\utils\app文件夹，存放app的公共模块

```

---
### 4. codeigniter   
##### 4.1 设置时区
```
设置时区 \index.php文件开头
date_default_timezone_set('Asia/Shanghai');
````

##### 4.2 Autoloader
```
CI的index.php入口文件，末尾
require_once './vendor/autoload.php';
```

##### 4.3 在一个 CodeIgniter 下运行多个应用程序
```
例如，你要创建两个应用程序："foo" 和 "bar"，你可以像下面这样组织你的目录结构
applications/foo/
applications/foo/config/
applications/foo/controllers/
applications/foo/libraries/
applications/foo/models/
applications/foo/views/

applications/bar/
applications/bar/config/
applications/bar/controllers/
applications/bar/libraries/
applications/bar/models/
applications/bar/views/

要选择使用某个应用程序时，你需要打开 index.php 文件然后设置 $application_folder 变量。例如，选择使用 "foo" 这个应用，你可以这样
$application_folder = 'applications/foo';
```

##### 4.4 将控制器放入子目录中
```
如果你正在构建一个比较大的应用，那么将控制器放到子目录下进行组织可能会方便一点。CodeIgniter 也可以实现这一点。

你只需要简单的在 application/controllers/ 目录下创建新的目录，并将控制器文件放到子目录下
当使用该功能时，URI 的第一段必须指定目录，例如，假设你在如下位置有一个控制器:
application/controllers/products/Shoes.php

为了调用该控制器，你的 URI 应该像下面这样:
example.com/index.php/products/shoes/show/123

```

---
### 5. codeigniter-restserver   
##### 5.1 URL Methods
```
composer require chriskacerguis/codeigniter-restserver
Note that you will need to copy rest.php to your config directory (e.g. application/config)

# GET：读取（Read）
# POST：新建（Create）
# PUT：更新（Update）
# PATCH：更新（Update），通常是部分更新
# DELETE：删除（Delete）
```
##### 5.2 Http状态码
```
200状态码
表示操作成功，但是不同的方法可以返回更精确的状态码。
GET: 200 OK
POST: 201 Created
PUT: 200 OK
PATCH: 200 OK
DELETE: 204 No Content
上面代码中，POST返回201状态码，表示生成了新的资源；DELETE返回204状态码，表示资源已经不存在。
此外，202 Accepted状态码表示服务器已经收到请求，但还未进行处理，会在未来再处理，通常用于异步操作

300状态码
API 用不到301状态码（永久重定向）和302状态码（暂时重定向，307也是这个含义），因为它们可以由应用级别返回，浏览器会直接跳转，API 级别可以不考虑这两种情况。
API 用到的3xx状态码，主要是303 See Other，表示参考另一个 URL。它与302和307的含义一样，也是"暂时重定向"，区别在于302和307用于GET请求，而303用于POST、PUT和DELETE请求。收到303以后，浏览器不会自动跳转，而会让用户自己决定下一步怎么办。

4xx状态码
表示客户端错误，主要有下面几种。
400 Bad Request：服务器不理解客户端的请求，未做任何处理。
401 Unauthorized：用户未提供身份验证凭据，或者没有通过身份验证。
403 Forbidden：用户通过了身份验证，但是不具有访问资源所需的权限。
404 Not Found：所请求的资源不存在，或不可用。
405 Method Not Allowed：用户已经通过身份验证，但是所用的 HTTP 方法不在他的权限之内。
410 Gone：所请求的资源已从这个地址转移，不再可用。
415 Unsupported Media Type：客户端要求的返回格式不支持。比如，API 只能返回 JSON 格式，但是客户端要求返回 XML 格式。
422 Unprocessable Entity ：客户端上传的附件无法处理，导致请求失败。
429 Too Many Requests：客户端的请求次数超过限额。

5xx状态码
表示服务端错误。一般来说，API 不会向用户透露服务器的详细信息，所以只要两个状态码就够了。
500 Internal Server Error：客户端请求有效，服务器处理时发生了意外。
503 Service Unavailable：服务器无法处理请求，一般用于网站维护状态。
```

---
### 6. Axios  
##### 6.1 Interceptors
```
You can intercept requests or responses before they are handled by then or catch.

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    return config;
  }, function (error) {
    // Do something with request error
    return Promise.reject(error);
  });

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
  }, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
  });
  
4 请求头 Content-Type: application/x-www-form-urlencoded，传参前将数据处理成键值对形式
  service.defaults.transformRequest = [function(data) {
    let ret = ''
    let it = ''
    for (it in data) {
      ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&'
    }
    return ret
  }]
```

##### 6.2 Repsponse
```
{
  // `data` is the response that was provided by the server
  data: {},

  // `status` is the HTTP status code from the server response
  status: 200,

  // `statusText` is the HTTP status message from the server response
  statusText: 'OK',

  // `headers` the headers that the server responded with
  // All header names are lower cased
  headers: {},

  // `config` is the config that was provided to `axios` for the request
  config: {},

  // `request` is the request that generated this response
  // It is the last ClientRequest instance in node.js (in redirects)
  // and an XMLHttpRequest instance in the browser
  request: {}
}
```

##### 6.3 Repsponse error
```
.catch(function (error) {
    if (error.response) {
      // The request was made and the server responded with a status code
      // that falls out of the range of 2xx
      console.log(error.response.data);
      console.log(error.response.status);
      console.log(error.response.headers);
    } else if (error.request) {
      // The request was made but no response was received
      // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
      // http.ClientRequest in node.js
      console.log(error.request);
    } else {
      // Something happened in setting up the request that triggered an Error
      console.log('Error', error.message);
    }
    console.log(error.config);
  });
```

##### 6.4 Repsponse处理
```
1 client发送http请求后，可遇到的情况有：
  # server未运行，不处理http request  --  Error: Network Error
  # server响应超时  --  Error: timeout
  # server响应http 状态码非2xx
  # server响应http 状态码2xx
  
2 使用axios的.interceptors.response预处理
  # 收到状态码2xx，执行response => {
  # 其他情况，执行error => {
    在error中，细分失败原因，执行失败后处理
    try {
      code = error.response.data.status
    } catch (e) {
      console.log('#1 ' + error.toString())
      if (error.toString().indexOf('Error: timeout') !== -1) {
        Notification.error({
          title: '请求超时',
          duration: 2500
        })
      }
      if (error.toString().indexOf('Error: Network Error') !== -1) {
        Notification.error({
          title: '网络错误',
          duration: 2500
        })
      }
    }
    switch(code) {
      case 401:

```


---
### 7. Sql  
##### 7.1 外键
```
student和grade，学生表中的gid是学生所在的班级id，是引入了班级表grade中的主键id。
那么gid就可以作为表student表的外键。
被引用的表，即表grade是主表，使用外键的表，即student，是从表。

```