# LAMP环境
---

### 概述
(2021.01)
- CentOS 7 最小安装
- MariaDB
- Apache
- php
- phpadmin
---

### 0 常用命令
```
  1 查看、卸载程序
    rpm -qa|grep php
    rpm -e php-pdo-5.1.6-27.el5_5.3
    
  2 重建yum仓库
    yum clean all
    yum makecache
    
    yum-config-manager --disable 'remi-php*'
    yum-config-manager --enable remi-php74
    
  3 应用服务启停
    systemctl restart sshd
    systemctl start sshd
    systemctl stop sshd
    systemctl enable sshd
    systemctl disable sshd

```
  
### 1 更改ssh端口号   
```
  # 找到行 #Port 22，取消注释，并添加自定义的ssh端口,6669端口(BE ASCII)，保留22端口是以防万一，万一端口新增失败，22端口还是能用的，大不了用回22端口上服务器重新设置
  vi /etc/ssh/sshd_config
  Port 22
  Port 6669
  
  # 查看ssh端口
  semanage port -l|grep ssh

  # SELINUX开放端口
  semanage port -a -t ssh_port_t -p tcp 6669
  # SELINUX关闭端口
  semanage port -d -t ssh_port_t -p tcp 2122

  # 防火墙添加端口  
  firewall-cmd --add-port=6669/tcp --permanent
  # 防火墙去除端口  
  firewall-cmd --remove-port=2122/tcp --permanent
 
  firewall-cmd --reload
  
  # 重启
  systemctl restart sshd
  reboot now
  
  # 新端口发起链接，注释22端口: #Port 22
  vi /etc/ssh/sshd_config
  systemctl restart sshd
```


### 2 aliyun 源镜像
```
  https://developer.aliyun.com/

  0 安装wget
    yum install wget 
  
  1 备份默认repo文件
    mv /etc/yum.repos.d/CentOS-AppStream.repo /etc/yum.repos.d/CentOS-AppStream.repo_bk
    mv /etc/yum.repos.d/CentOS-PowerTools.repo /etc/yum.repos.d/CentOS-PowerTools.repo_bk
    mv /etc/yum.repos.d/CentOS-centosplus.repo /etc/yum.repos.d/CentOS-centosplus.repo_bk
    mv /etc/yum.repos.d/CentOS-Extras.repo /etc/yum.repos.d/CentOS-Extras.repo_bk
    mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo_bk
  
  2 下载阿里云镜像repo文件
    wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-8.repo
  
    # 更新yum缓存
      yum clean all
      yum makecache
      
  3 安装 epel 配置包
    # 下载文件
    wget -O /etc/yum.repos.d/epel.repo http://mirrors.aliyun.com/repo/epel-7.repo
    
    # 更新yum缓存
      yum clean all
      yum makecache
```

### 3 设置NTP对时
```
  1 安装
    yum install ntp
   
  2 配置服务器
    vi /etc/ntp.conf
    # server 3.centos.pool.ntp.org iburst
    server ntp.aliyun.com iburst
    server ntp1.aliyun.com iburst
  
  3 启用
    systemctl enable ntpd
    systemctl start ntpd
    
    chkconfig ntpd on
  
  3 查看
    ntpstat 查看NTP同步结果
    timedatectl

```

### 4 安装MariaDB
```
  1 设置数据源
    # 新建文件
      vi /etc/yum.repos.d/mariadb.repo
    # 文件输入以下内容
      [mariadb]
      name = MariaDB
      baseurl=http://mirrors.aliyun.com/mariadb/yum/10.5/centos/7/x86_64/
      gpgkey=https://mirrors.aliyun.com/mariadb/yum/RPM-GPG-KEY-MariaDB 
      #enabled=1
      gpgcheck=1
    # 更新yum缓存
      yum clean all
      yum makecache
      
  2 安装
    # --disablerepo=AppStream 禁用仓库标识为 AppStream 的主软件仓库
    # yum -y install galera-4
    # yum -y install MariaDB-server MariaDB-client  --disablerepo=AppStream
    yum -y install mariadb mariadb-server
  
  3 启动
    systemctl enable mariadb
    systemctl start mariadb
    systemctl restart mariadb
    systemctl stop mariadb.service
  
  4 secure配置
    mysql_secure_installation
    App@4321
    mysql -uroot -p

  5 配置MariaDB的字符集
    vi /etc/my.cnf.d/server.cnf
    [mysqld]
    init_connect='SET collation_connection = utf8_unicode_ci' 
    init_connect='SET NAMES utf8' 
    character-set-server=utf8 
    collation-server=utf8_unicode_ci 
    skip-character-set-client-handshake

    vi /etc/my.cnf.d/client.cnf
    [client]
    default-character-set=utf8

    vi /etc/my.cnf.d/mysql-clients.cnf
    [mysql]
    default-character-set=utf8
  
  6 设置时区
    /etc/my.cnf
    在 [mysqld] 之下加
    default-time-zone = '+8:00'
  
  7 重启进程
    systemctl restart mariadb
  
  8 查看设置结果
    mysql -uroot -p
    show variables like "%character%";
    show variables like "%collation%";
  
  9 用户
    create user app@localhost identified by 'Sql@1234';
    grant all on binglang.* to app@localhost;
    REVOKE all ON binglang.* FROM 'app'@'localhost';
    show grants;
    show grants for app@localhost;
    
    SET password for 'root'@'localhost'=password('pwd');
```

### 5 安装Apache HTTP
```
  1 安装
    yum install httpd
  
    systemctl start httpd.service
    systemctl enable httpd.service
    systemctl restart httpd.service
  
  2 设置防火墙允许
    firewall-cmd --permanent --add-service=http 
    firewall-cmd --permanent --add-port=80/tcp
    firewall-cmd --permanent --add-port=8080/tcp
    firewall-cmd --reload 

  3 配置
    /etc/httpd/conf/httpd.conf
    # Listen 12.34.56.78:80
    Listen 192.168.1.92:80
    Listen 192.168.1.92:8080
    
```

### 6 安装PHP
```
  http://rpms.remirepo.net/wizard/
  
  1 设置数据源
    wget https://mirrors.tuna.tsinghua.edu.cn/remi/enterprise/remi-release-7.rpm
    yum install remi-release-7.rpm
    yum module list php
    
  2 安装php7.4
    yum install yum-utils
    
    yum-config-manager --disable 'remi-php*'
    yum-config-manager --enable remi-php74
    
    yum clean all
    yum makecache
    
    yum install php php-cli php-fpm php-mysqlnd php-zip php-devel php-gd php-pecl-mcrypt php-mbstring php-curl php-xml php-pear php-bcmath php-json php-redis
       
  3 查看启用的模块
    php -v
    
    php --modules
    
    systemctl restart httpd.service
    
  4 启用php-fpm
    # PHP-FPM(PHP FastCGI Process Manager)意：PHP FastCGI 进程管理器，用于管理PHP 进程池的软件，用于接受web服务器的请求。
    # PHP-FPM提供了更好的PHP进程管理方式，可以有效控制内存和进程、可以平滑重载PHP配置。
    
    /etc/php.ini，修改 cgi.fix_pathinfo=1 为 cgi.fix_pathinfo=0
    
    systemctl enable php-fpm
    
    systemctl start php-fpm
    
    systemctl disable php-fpm
  
  5 设置时区
    /etc/php.ini 文件中，搜索“timezone”，添加
    date.timezone ="Asia/Shanghai"
  
  6 测试
    cd /var/www/html
    vi info.php
    <?php
      phpinfo();
    ?>

    systemctl restart httpd.service
    
    http://192.168.1.92/info.php
  
  7 卸载
    # 查看php相关安装包
      rpm -qa|grep php
    # 卸载
      rpm -e php-pdo-5.1.6-27.el5_5.3
      
```

### 7 安装phpMyAdmin
```
  1 下载
    cd /var/www/html/
    wget https://files.phpmyadmin.net/phpMyAdmin/5.0.4/phpMyAdmin-5.0.4-all-languages.tar.gz
  
  2 解压
    tar xvf phpMyAdmin-5.0.4-all-languages.tar.gz
    mv phpMyAdmin-5.0.4-all-languages phpmyadmin  
    
  3 配置
    cd /var/www/html/phpmyadmin
    cp config.sample.inc.php config.inc.php
    
  4 在appache配置路径下，增加phpadmin别名访问配置 /etc/httpd/conf.d/路径下新建配置文件：phpmyadmin.conf
    cd /etc/httpd/conf.d/
    vi phpmyadmin.conf
    # 新加文件内容：
      # phpMyAdmin - Web based MySQL browser written in php
      # 
      # Allows only localhost by default
      #
      # But allowing phpMyAdmin to anyone other than localhost should be considered
      # dangerous unless properly secured by SSL

      Alias /phpmyadmin /var/www/html/phpmyadmin

      <Directory /var/www/html/phpmyadmin/>
         AddDefaultCharset UTF-8
        Options -Indexes -Includes +FollowSymLinks -MultiViews
              AllowOverride All

         <IfModule mod_authz_core.c>
           # Apache 2.4
           <RequireAny>
            # Require ip 127.0.0.1
            # Require ip ::1
            Require all granted
           </RequireAny>
         </IfModule>
         <IfModule !mod_authz_core.c>
           # Apache 2.2
           Order Deny,Allow
           Deny from All
           Allow from 127.0.0.1
           Allow from ::1
         </IfModule>
      </Directory>

      <Directory /var/www/html/phpmyadmin/setup/>
         <IfModule mod_authz_core.c>
           # Apache 2.4
           <RequireAny>
            # Require ip 127.0.0.1
            # Require ip ::1
            Require all granted
           </RequireAny>
         </IfModule>
         <IfModule !mod_authz_core.c>
           # Apache 2.2
           Order Deny,Allow
           Deny from All
           Allow from 127.0.0.1
           Allow from ::1
         </IfModule>
      </Directory>

      # These directories do not require access over HTTP - taken from the original
      # phpMyAdmin upstream tarball
      #
      <Directory /var/www/html/phpmyadmin/libraries/>
          Order Deny,Allow
          Deny from All
          Allow from None
      </Directory>

      <Directory /var/www/html/phpmyadmin/setup/lib/>
          Order Deny,Allow
          Deny from All
          Allow from None
      </Directory>

      <Directory /var/www/html/phpmyadmin/setup/frames/>
          Order Deny,Allow
          Deny from All
          Allow from None
      </Directory>

      # This configuration prevents mod_security at phpMyAdmin directories from
      # filtering SQL etc.  This may break your mod_security implementation.
      #
      #<IfModule mod_security.c>
      #    <Directory /usr/share/phpMyAdmin/>
      #        SecRuleInheritance Off
      #    </Directory>
      #</IfModule>

  5 使用
    systemctl restart httpd.service
    
    http://192.168.1.92/phpmyadmin
    
```

### 8 APP Web
```
  1 目录结构
    Appache Document Root：/var/www/html
    APP根目录：/var/www/html/binglang (CI框架)
  
  2 修改httpd配置文件
    vi /etc/httpd/conf/httpd.conf
    # 写文件
      Listen 80
      Listen 443 https
    # 把 Require all denied默认拒绝访问设置为允许访问： Require all granted
  
  3 新建app 的vhost配置文件
    vi /etc/httpd/conf.d/binglang.conf
    # 写文件
      <VirtualHost *:8080>
        DocumentRoot "/var/www/html/binglang/server"
        <Directory "/var/www/html/binglang/server/">
          Options -Indexes -Includes +FollowSymLinks -MultiViews
          AllowOverride All
          Require all granted 
        </Directory>
      </VirtualHost>

  4 http重定向至https，出去url中index.php
    vi /var/www/html/binglang/server/.htaccess
    
    # 写文件 HTTP
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
    
    # 写文件 HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{SERVER_NAME}/$1 [R,L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]

  5 重启httpd
    systemctl restart httpd.service
  
  6 APP数据库导入Maria DB
  
  7 测试
       
    http://192.168.1.92
    
  8 FAQ - appache rewrite
    RewriteRule Pattern Substitution [flags]
    参数解析:
    1.Pattern：作用于当前URL的正则表达式；此url不包含协议、域名和查询字符串部分。
    2.Substitution：当RewriteCond满足时，用来替换原始URL指定内容的字符串，还可以包括以下扩展：
    （1）.$N：RewriteRule后向引用。$N引用紧跟在RewriteCond之后的RewriteRule中Pattern的小括号中的规则在当前URL中匹配的内容。N是0 <= N <= 9之间的整数。
    （2）.%N：RewriteCond后向引用 。%N引用最后一个RewriteCond的Pattern中的小括号中的规则在当前URL中匹配的内容。N是0 <= N <= 9之间的整数。
    3.[flags]：多个标志之间用逗号分隔，下面是常见的一些flag：
    （1）.R：表示重定性，[R=301]表示301重定向，默认是302重定向。
    （2）.F：强制当前URL为被禁止的，即，立即反馈一个HTTP响应代码403(被禁止的)。
    （3）.G：强制当前URL为已废弃的，即，立即反馈一个HTTP响应代码410(已废弃的)。
    （4）.L：立即停止重写操作，并不再应用其他重写规则。 
    （5）.N：重新执行重写操作(从第一个规则重新开始). 这时再次进行处理的URL已经不是原始的URL了，而是经最后一个重写规则处理的URL。
    （6）.C：此标记使当前规则与下一个(其本身又可以与其后继规则相链接的， 并可以如此反复的)规则相链接。 它产生这样一个效果: 如果一个规则被匹配，通常会继续处理其后继规则， 即，这个标记不起作用；如果规则不能被匹配，则其后继的链接的规则会被忽略。
    （7）.NC：忽略大小写。
    （8）.QSA：此标记强制重写引擎在已有的替换串中追加一个请求串，而不是简单的替换。
```

### 9 SELinux
```
  0 概述
    # https://blog.csdn.net/weixin_41078837/article/details/80571065
    SELinux则是基于MAC（强制访问机制），简单的说，就是程序和访问对象上都有一个安全标签（即selinux上下文）进行区分，只有对应的标签才能允许访问。否则即使权限是777，也是不能访问的。
    
    在SELinux中，访问控制属性叫做安全上下文。所有客体（文件、进程间通讯通道、套接字、网络主机等）和主体（进程）都有与其关联的安全上下文。
    一个安全上下文由三部分组成：用户（u）、角色(r)和类型(t)标识符。但我们最关注的是第三个部分。
    
    
  1 临时关闭：
    getenforce
    setenforce 0

  2 永久关闭：
    vi /etc/sysconfig/selinux
    SELINUX=enforcing 改为 SELINUX=disabledcd

  3 查看状态
    sestatus
    
  4 apache 发送邮件 fsockopen() Permission denied
    # https://blog.csdn.net/pennyliang/article/details/7342042
    fsockopen()  unable to connect to 127.0.0.1:80 (Permission denied)" error 
    
    用以下命令，不需重启
    setsebool -P httpd_can_network_connect 1
    
  5 apache php 上传文件，没有可写权限
    chown -R apache:apache /var/www/html/binglang/server/resource/avatar
    chmod -R 777 /var/www/html/binglang/server/resource/avatar

    # https://blog.csdn.net/weixin_34014555/article/details/92025349
    所有进程及文件都拥有一个 SELinux 的安全性脉络,可以用ls -Z查看
    
    把我准备写入的文件夹的权限角色从httpd_sys_content_t 改成httpd_sys_rw_content_t
    chcon -R -t httpd_sys_rw_content_t  /var/www/html/binglang/server/resource/avatar
    
    用chcon你可以做一次暂时的变更，它在重启后消失；用followed by紧跟着的semanage，做永久的变更。
    semanage fcontext -a -t httpd_sys_rw_content_t  /var/www/html/binglang/server/resource/avatar
    cat /etc/selinux/targeted/contexts/files/file_contexts.local
  

```


### 10 工具
```
  1 netstat
    1 安装
      yum install net-tools
    
    2 使用
      # 查看监听(Listen)的端口
      netstat -lntp 
      # 查看所有建立的TCP连接
      netstat -antp 
  
  2 htop任务管理器
    yum install htop
    
  3 semanage
    yum install policycoreutils-python
    
```

### 11 串口
```
  1 检查系统是否支持串口，出现以上console  enabled表示支持
    dmesg |grep tty

  2 查看串口信息的方法，发送了信息则tx上回显示字节在增长，如果接收到信息rx：会显示接受的字节信息
    cat /proc/tty/driver/serial

  3 查看当前的串口波特率
    stty -F /dev/ttyS0
    
  4 设置波特率，8数据位 1停止位 无校验
    stty -F/dev/ttyS0 speed 9600 cs8 -cstopb -parenb
    
```

### 12 Python
```
  1 安装（centos 最小安装，需要另外单独再安装python），可选择安装的python版本，python2，python36
    yum install python

  2 进入python客户端
    python3

  3 安装python 串口模块
    # 指定某用户安装模块（用户app）
    pip3 install --user pyserial
  
  4 安装numpy包
    pip3 install --user numpy
    
  5 给某用户开通读写串口权限
    usermod -aG dialout app
    
  6 安装requests包 
    pip3 install --user requests
    
```

### APP Web
```
  # Appache Document Root：/var/www/html
  # APP根目录：/var/www/html/green.ga/CI框架
  # 为自开发应用配置Virtual Host，为owncloud配置alias。去除mod_ssl默认的/etc/httpd/conf.d/ssl.conf
  # 启用SSL（https），在应用根目录下的.htaccess文件中，设置http重定向https。
  
  # httpd.conf
  # 把 Require all denied默认拒绝访问设置为允许访问： Require all granted
  vi /etc/httpd/conf/httpd.conf
  Listen 80
  Listen 443 https
  ServerAdmin freeaircn@163.com
  #ServerName www.be-green.ga
  
  # /etc/httpd/conf.d/owncloud.conf
  Alias /owncloud "/var/www/html/owncloud"
  <Directory /var/www/html/owncloud>
    Options +FollowSymlinks
    AllowOverride All
    <IfModule mod_dav.c>
      Dav off
    </IfModule>  
  </Directory>
  
  # owncloud/.htaccess
  RewriteEngine on
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{SERVER_NAME}/owncloud [R,L]
  
  # /etc/httpd/conf.d/vhost_be.conf
  <VirtualHost *:80>
    DocumentRoot "/var/www/html/green.ga"
    <Directory "/var/www/html/green.ga">
      Options -Indexes -Includes +FollowSymLinks -MultiViews
      AllowOverride All
      Require all granted 
    </Directory>
  </VirtualHost>
  
  SSLPassPhraseDialog exec:/usr/libexec/httpd-ssl-pass-dialog
  SSLSessionCache         shmcb:/run/httpd/sslcache(512000)
  SSLSessionCacheTimeout  300
  SSLRandomSeed startup file:/dev/urandom  256
  SSLRandomSeed connect builtin
  SSLCryptoDevice builtin

  <VirtualHost *:443>
    DocumentRoot "/var/www/html/green.ga"

    ErrorLog logs/ssl_error_log
    TransferLog logs/ssl_access_log
    LogLevel warn

    SSLEngine on
    SSLProtocol all -SSLv2 -SSLv3
    SSLCipherSuite HIGH:3DES:!aNULL:!MD5:!SEED:!IDEA

    SSLCertificateFile /etc/pki/tls/certs/fullchain.cer
    SSLCertificateKeyFile /etc/pki/tls/private/be-green.ga.key

    <Files ~ "\.(cgi|shtml|phtml|php3?)$">
      SSLOptions +StdEnvVars
    </Files>
    <Directory "/var/www/cgi-bin">
      SSLOptions +StdEnvVars
    </Directory>

    <Directory "/var/www/html/green.ga">
      Options -Indexes -Includes +FollowSymLinks -MultiViews
      AllowOverride All
      Require all granted 
    </Directory>

    BrowserMatch "MSIE [2-5]" \
           nokeepalive ssl-unclean-shutdown \
           downgrade-1.0 force-response-1.0

    CustomLog logs/ssl_request_log \
            "%t %h %{SSL_PROTOCOL}x %{SSL_CIPHER}x \"%r\" %b"
  </VirtualHost>
  
  # /var/www/html/green.ga/.htaccess
  # http重定向至https，出去url中index.php
  RewriteEngine On
  RewriteCond %{HTTPS} off
  RewriteRule ^(.*)$ https://%{SERVER_NAME}/$1 [R,L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)$ index.php/$1 [L]

  # 重启httpd
  systemctl restart httpd.service

  # appache rewrite
    RewriteRule Pattern Substitution [flags]
    参数解析:
    1.Pattern：作用于当前URL的正则表达式；此url不包含协议、域名和查询字符串部分。
    2.Substitution：当RewriteCond满足时，用来替换原始URL指定内容的字符串，还可以包括以下扩展：
    （1）.$N：RewriteRule后向引用。$N引用紧跟在RewriteCond之后的RewriteRule中Pattern的小括号中的规则在当前URL中匹配的内容。N是0 <= N <= 9之间的整数。
    （2）.%N：RewriteCond后向引用 。%N引用最后一个RewriteCond的Pattern中的小括号中的规则在当前URL中匹配的内容。N是0 <= N <= 9之间的整数。
    3.[flags]：多个标志之间用逗号分隔，下面是常见的一些flag：
    （1）.R：表示重定性，[R=301]表示301重定向，默认是302重定向。
    （2）.F：强制当前URL为被禁止的，即，立即反馈一个HTTP响应代码403(被禁止的)。
    （3）.G：强制当前URL为已废弃的，即，立即反馈一个HTTP响应代码410(已废弃的)。
    （4）.L：立即停止重写操作，并不再应用其他重写规则。 
    （5）.N：重新执行重写操作(从第一个规则重新开始). 这时再次进行处理的URL已经不是原始的URL了，而是经最后一个重写规则处理的URL。
    （6）.C：此标记使当前规则与下一个(其本身又可以与其后继规则相链接的， 并可以如此反复的)规则相链接。 它产生这样一个效果: 如果一个规则被匹配，通常会继续处理其后继规则， 即，这个标记不起作用；如果规则不能被匹配，则其后继的链接的规则会被忽略。
    （7）.NC：忽略大小写。
    （8）.QSA：此标记强制重写引擎在已有的替换串中追加一个请求串，而不是简单的替换。
```    

