
# 初始化Web应用前端和后端的开发环境(Windows)
---

### 概述(2019.12.24)
- 前端选用vue element admin模板，基于ES2015+、vue、vuex、vue-router 、vue-cli 、axios 和 element-ui
- 后端选用CodeIgniter框架，基于php，composer。加入Ion Auth和PHP RBAC模块用于用户管理。
- 搭建配置WAMP环境
---

### PC本地项目（binglang）目录结构
- 顶层: D:\www\binglang
- 后端根目录：D:\www\binglang\server
- 前端根目录：D:\www\binglang\client
- 数据库脚本目录：D:\www\binglang\db
- 前端打包输出目录：D:\www\binglang\server\resource
---

### wamp安装&配置
```
1. 修改DB root账号
use mysql;  
update user set authentication_string=PASSWORD('B@123456') where user='root';  
UPDATE user SET password=password('B@123456') WHERE user='root';  
flush privileges; 
  
2. 修改phpadmin config
\wamp64\apps\phpmyadmin4.5.2\config.inc.php
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'B@123456';

3. 修改appche httpd-vhosts
<VirtualHost *:80>
  ServerName localhost
  ServerAlias localhost
  DocumentRoot "D:/www/binglang/server"
  <Directory "D:/www/binglang/server/">
    Options -Indexes -Includes +FollowSymLinks -MultiViews
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>  

4. 系统环境变量添加PHP
wamp集合多个php版本，选定PHP 版本.
将对应路径C:\wamp64\bin\php\php7.2.18\添加至系统环境PATH变量
CMD验证：php -v
```
---

### 初始化vue element admin

***1. 安装nodejs, webpack, vue-cli***
```
# 安装nodejs

# 修改npm模块路径和cache路径
将global模块和cache缓存路径修改到D盘目录下，手动创建文件加node_cache和node_global  
执行命令：
npm config set prefix "D:\nodejs\node_global"
npm config set cache "D:\nodejs\node_cache"
系统变量，新建"NODE_PATH"，输入D:\nodejs\node_global\node_modules
用户变量，PAHT添加D:\nodejs\node_global

# 修改国内 镜像地址
npm config set registry http://registry.npm.taobao.org

# npm全局安装webpack，4.0版本后还需要webpack-cli
npm install -g webpack
npm install -g webpack-cli
# 验证
webpack -v
  
# npm全局安装vue-cli
npm install -g @vue/cli
# 卸载vue-cli
npm uninstall -g vue-cli
# 验证
vue --version
```  

***2. 复制vue-element-admin包***
```
# 复制vue-element-admin-4.2.1至D:\www\binglang，修改文件夹名为client
  
# 修改package.json
"axios": "0.18.1",
"element-ui": "2.13.0",
"vue-router": "3.1.3",
"vuex": "3.1.1",
  
# 安装依赖
npm install
  
# build for test environment
npm run dev

# build for production environment
npm run build:prod
``` 
---

### 初始化CI
***0. 安装php***[跳过]
```
  # 安装php  http://www.php.net/downloads.php
  # 解压至C:\Program Files\php
  # 将php.ini-development改为php.ini，并将extension_dir = "ext"修改为PHP根目录下的php/ext目录，修改为：extension_dir = "C:/Program Files/php/ext"
  # 将C:\Program Files\php添加至系统环境PATH变量，添加完成，CMD验证：php -v
```
***1. 安装php-Composer***
```
# 设置国内镜像
composer config -g repo.packagist composer https://packagist.phpcomposer.com

# [跳过，设置PHP环境变量解决]修改composer.bat指定WAMP使用的PHP版本
  # C:\ProgramData\ComposerSetup\bin\composer.bat
  @C:\wamp64\bin\php\php7.2.18\php.exe "%~dp0composer.phar" %*
```

***2. 修改CI包***
```
1) 下载CI包，复制到D:\www\binglang，修改文件夹名为server

2) 修改composer.json：
  "require": {
    "php": ">=5.3.7",
    "lcobucci/jwt": "3.3.*",
    "owasp/phprbac": "2.0.*"
  },
  "config": {
        "optimize-autoloader": true
  },
  "autoload": {
    "psr-4": {
        "App_Settings\\": "settings/"
    }
  },
  
3) composer install
  
4) 使用php命名空间
  use 只是进行声明就好像说明这里使用了某个命名空间下面的类，并不能实际载入该类。
  在 use之前，先require。
  通过composer生成autoloader文件，引入该文件就能完成自动导入需要的类，require 'vendor/autoload.php';
  CI的index.php是其入口文件，在index.php末尾引入'vendor/autoload.php'
  
  require_once './vendor/autoload.php';

  /*
   * --------------------------------------------------------------------
   * LOAD THE BOOTSTRAP FILE
   * --------------------------------------------------------------------
   *
   * And away we go...
   */
  
5) 设置时区 \index.php文件开头
  <?php
   /**
    * set time-zone
    */
   date_default_timezone_set('Asia/Shanghai');

6) 去掉url中index.php
  # \.htaccess
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ index.php/$1 [L]
  
  $config['index_page'] = '';
```  
---

### 设置VUE打包输出
```
# 新建resource文件夹 D:\www\binglang\server\resource
  
# 修改前端根目录中vue.config.js文件
# 资源文件（js,css,ico…）输出到CI的resource目录下，全部存放于dist子文件夹；
# 首页视图文件（home.html）输出到CI的views目录下；取名home.html，因views目录下存在缺省index.html。
  publicPath: '/resource/dist/',
  outputDir: 'D:/www/binglang/server/resource/dist',
  assetsDir: 'static',
  indexPath: 'D:/www/binglang/server/application/views/home.html',
  lintOnSave: process.env.NODE_ENV === 'development',
    
# 前端打包后，将dist和home.html上传至服务器的对应目录下。
```
---

### 设置CI Config
```
# 修改routes
  # \application\config\routes.php文件，增加：
  $route['default_controller'] = 'home';
  
# 新建 Home控制器
  # \application\controllers，

# 修改\application\config\config.php文件
  $config['base_url'] = 'http://127.0.0.1';
  // $config['base_url'] = 'http://182.247.101.235';
```
---
***到此，VUE和CI已适配WAMP环境，启动WAMP，可浏览admin页面***

---
### 其他
```
  # 修改routes  
    /**
     * api
     */
    // users Controller
    $route['users/(:any)'] = 'users/$1';
    // generators Controller
    $route['generators/(:any)'] = 'generators/$1';

    /**
     * 前端路由
     */
    // 重置密码url，reset_password/uid/post/hash_code
    $route['reset_password/(:any)/post/(:any)'] = 'home';
    $route['(:any)'] = 'home';
    $route['(:any)/(:any)'] = 'home';
    $route['(:any)/(:any)/(:any)'] = 'home';

    //
    $route['404_override'] = '';
    $route['translate_uri_dashes'] = FALSE;
  
  # 去掉url中index.php
    # \.htaccess
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
    
    # 去掉url中index.php，且重定向至https
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{SERVER_NAME}/$1 [R,L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]

    $config['index_page'] = '';
    
    
    
    # 加入Ion Auth和PHP RBAC模块用于用户管理
    # 配置RBAC数据库信息
      # 使用适当的权限创建数据库和数据库用户
        # db account     
        CREATE USER 'users_admin'@'localhost' IDENTIFIED BY 'B@123456';
        CREATE DATABASE IF NOT EXISTS db_users;
        GRANT ALL PRIVILEGES ON db_users.* TO users_admin@localhost;
        
        # 撤销授权
        revoke all on db_users.* from users_admin@localhost;
        Delete FROM user Where User='users_admin' and Host='localhost';
        
      # 使用提供的* .sql文件导入数据库表和初始数据。MySQL：'/ path / to / PhpRbac / database / mysql.sql'
      # 确保将SQL语句中的“PREFIX_”更改为您要使用的前缀！
      # 编辑'/path/to/PhpRbac/database/database.config'，输入正确的凭据，选择正确的$ adapter和$ tablePrefix
        <?php
        $host="localhost";
        $user="users_admin";
        $pass="B@123456";

        $dbname="db_users";
        $adapter="mysqli";

        $tablePrefix = "rbac_";
```
    