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
### 4. axios
##### 4.1 概念
```
https://jingyan.baidu.com/article/a501d80cb60400ac630f5ed6.html
1 GET一般是从服务器上获取数据，POST是向服务器提交数据。
2 GET通过URL提交数据，数据在URL中可以看到，POST则是在HEADER内提交。
3 GET提交的数据不能大于2KB，而POST不受限制。
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

---
### 5. codeigniter   
##### 5.1 设置时区
```
设置时区 \index.php文件开头
date_default_timezone_set('Asia/Shanghai');
````

##### 5.2 Autoloader
```
CI的index.php入口文件，末尾
require_once './vendor/autoload.php';
```

##### 5.3 控制器文件夹下开加子文件夹
```
controllers下再细分子文件夹。例如：controllers/pj,controllers/xxk等。
1.在controllers下添加相关的子文件夹，例如pj。
2.在application/config/routes.php中添加一条路由规则：
3.$route['pj/(^/)(^/)'] = "pj/$1/$2";


```

---
### 6. codeigniter-restserver   
##### 6.1 引入
```
composer require chriskacerguis/codeigniter-restserver
Note that you will need to copy rest.php to your config directory (e.g. application/config)


```

