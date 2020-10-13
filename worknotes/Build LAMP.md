# LAMP环境
---

### 概述(2020.06)
- CentOS 8.1 最小安装
- MariaDB 10.4.12
- Apache 2.4.37
- php 7.3.18
- phpadmin 5.0.2
---
    
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
  1 备份默认repo文件
    mv /etc/yum.repos.d/CentOS-AppStream.repo /etc/yum.repos.d/CentOS-AppStream.repo_bk
    mv /etc/yum.repos.d/CentOS-PowerTools.repo /etc/yum.repos.d/CentOS-PowerTools.repo_bk
    mv /etc/yum.repos.d/CentOS-centosplus.repo /etc/yum.repos.d/CentOS-centosplus.repo_bk
    mv /etc/yum.repos.d/CentOS-Extras.repo /etc/yum.repos.d/CentOS-Extras.repo_bk
    mv /etc/yum.repos.d/CentOS-Base.repo /etc/yum.repos.d/CentOS-Base.repo_bk
  
  2 下载阿里云镜像repo文件
    wget -O /etc/yum.repos.d/CentOS-Base.repo http://mirrors.aliyun.com/repo/Centos-8.repo
  
  3 安装 epel 配置包
    # 下载文件
    yum install -y https://mirrors.aliyun.com/epel/epel-release-latest-8.noarch.rpm
    
    # 将 repo 配置中的地址替换为阿里云镜像站地址，下面用sed命令来直接更改EPEL的地址是最高效的，当然，也可直接用vim打开文件来改。
    sed -i 's|^#baseurl=https://download.fedoraproject.org/pub|baseurl=https://mirrors.aliyun.com|' /etc/yum.repos.d/epel*
    sed -i 's|^metalink|#metalink|' /etc/yum.repos.d/epel*
    
  4 启用/禁用仓库
    yum-config-manager --set-enabled remi-safe
    yum-config-manager --set-disabled remi-safe
    
  5 查看安装的应用
    rpm -qa|grep php
```

### 3 安装MariaDB
```
  1 设置数据源
    # 新建文件
      cat /etc/yum.repos.d/mariadb.repo
    # 文件输入以下内容
      [mariadb]
      name = MariaDB
      baseurl=http://mirrors.aliyun.com/mariadb/yum/10.4/centos8-amd64/
      gpgkey=https://mirrors.aliyun.com/mariadb/yum/RPM-GPG-KEY-MariaDB 
      #enabled=1
      gpgcheck=1
    # 更新yum缓存
      yum clean all
      yum makecache
      
  2 安装
    # --disablerepo=AppStream 禁用仓库标识为 AppStream 的主软件仓库
    yum -y install galera-4
    yum -y install MariaDB-server MariaDB-client  --disablerepo=AppStream
  
  3 启动
    systemctl start mariadb
    systemctl enable mariadb
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

  6 重启进程
    systemctl restart mariadb
  
  7 查看设置结果
    mysql -uroot -p
    show variables like "%character%";
    show variables like "%collation%";
  
  8 用户
    create user app@localhost identified by 'pwd';
    grant all on db_name.* to app@localhost;
    REVOKE all ON db_name.* FROM 'app'@'localhost';
    show grants;
    show grants for app@localhost;
    
    SET password for 'root'@'localhost'=password('pwd');
```

### 4 安装Apache HTTP
```
  1 安装
    yum install httpd
  
    systemctl start httpd.service
    systemctl enable httpd.service
    systemctl restart httpd.service
  
  2 设置防火墙允许
    firewall-cmd --permanent --add-service=http 
    firewall-cmd --permanent --add-port=80/tcp
    firewall-cmd --reload 

```

### 5 安装PHP
```
  1 设置数据源
    wget https://mirrors.tuna.tsinghua.edu.cn/remi/enterprise/remi-release-8.rpm
    yum install remi-release-8.rpm
    yum module list php
    
  2 安装php7.3
    yum install yum-utils
    yum module enable php:remi-7.3
    
    yum install php
    
  3 安装模块
    yum install php-mysqli
    
    # yum install php-gd php-ldap php-odbc php-pear php-xml php-xmlrpc php-snmp php-soap curl curl-devel php-bcmath php-intl php-imagick php-curl php-imap php-ssh2 php-apcu
 
    # systemctl enable --now php-fpm
    
  4 查看启用的模块
    php --modules
  
  5 测试
    cd /var/www/html
    vi info.php
    <?php
      phpinfo();
    ?>

    systemctl restart httpd.service
    
    http://192.168.1.89/info.php
  
  6 卸载
    # 查看php相关安装包
      rpm -qa|grep php
    # 卸载
      rpm -e php-pdo-5.1.6-27.el5_5.3
      
```

### 6 安装phpMyAdmin
```
  1 下载
    cd /var/www/html/
    wget https://files.phpmyadmin.net/phpMyAdmin/5.0.2/phpMyAdmin-5.0.2-all-languages.tar.gz
  
  2 解压
    tar xvf phpMyAdmin-5.0.2-all-languages.tar.gz
    mv phpMyAdmin-5.0.2-all-languages phpmyadmin  
    
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

```

### 7 APP Web
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
    vi /etc/httpd/conf.d/vhost_app.conf
    # 写文件
      <VirtualHost *:80>
        DocumentRoot "/var/www/html/binglang/server"
        <Directory "/var/www/html/binglang/server/">
          Options -Indexes -Includes +FollowSymLinks -MultiViews
          AllowOverride All
          Require all granted 
        </Directory>
      </VirtualHost>

  4 http重定向至https，出去url中index.php
    vi /var/www/html/binglang/server/.htaccess
    # 写文件
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{SERVER_NAME}/$1 [R,L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]

  5 重启httpd
    systemctl restart httpd.service
  
  7 APP数据库写入Maria DB
    
    
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

### 8 开启自签名SSL
```
  1 参考
    # https://www.cnblogs.com/nidey/p/9041960.html
    # https://www.cnblogs.com/walk1314/p/9100019.html
    
    # https://www.cnblogs.com/jie-hu/p/8034226.html
    # https://www.cnblogs.com/xiaoleiel/p/11160661.html
    # https://www.cnblogs.com/idjl/p/9610561.html
  
  2 Appache安装ssl模块，安装完后在/etc/httpd/conf.d/会有一个ssl.conf的文件，打开文件以后找到SSLCertificateFile和SSLCertificateKeyFile2行，可以看到后面我们要生成的密钥的配置信息
    yum install mod_ssl
  
  3 安装openssl
    yum install openssl
  
  4 ca配置文件
    vi ca.conf
    [ req ]
    default_bits       = 4096
    distinguished_name = req_distinguished_name

    [ req_distinguished_name ]
    countryName                 = CN
    stateOrProvinceName         = YunNan
    localityName                = BaoShan
    organizationName            = BingLangJiang Co.,Ltd.
    organizationalUnitName      = Freeair Studio 
    commonName                  = Own CA
    commonName_max              = 64
  
    # 生成ca秘钥，得到ca.key
    openssl genrsa -out ca.key 4096
    
    # 生成ca证书签发请求，得到ca.csr
    openssl req -new -sha256 -out ca.csr -key ca.key -config ca.conf
    pwd/6669
    
    # 生成ca根证书，得到ca.crt
    openssl x509 -req -days 365 -in ca.csr -signkey ca.key -out ca.crt

  # appache侧
    # 配置文件
    vi server.conf
    [ req ]
    default_bits       = 2048
    distinguished_name = req_distinguished_name
    req_extensions     = req_ext

    [ req_distinguished_name ]
    countryName                 = Country Name (2 letter code)
    countryName_default         = CN
    stateOrProvinceName         = State or Province Name (full name)
    stateOrProvinceName_default = YunNan
    localityName                = Locality Name (eg, city)
    localityName_default        = BaoShan
    organizationName            = Organization Name (eg, company)
    organizationName_default    = BingLangJiang Co.,Ltd. 
    commonName                  = Common Name (e.g. server FQDN or YOUR name)
    commonName_max              = 64
    commonName_default          = www.be-green.com

    [ req_ext ]
    subjectAltName = @alt_names

    [alt_names]
    DNS.1   = www.be-green.com
    IP.1    = 182.247.101.235
    IP.2    = 192.168.205.60

    
    # 生成秘钥，得到server.key
    openssl genrsa -out server.key 2048
    
    # 生成证书签发请求，得到server.csr
    openssl req -new -sha256 -out server.csr -key server.key -config server.conf
    
    # 用CA证书签名证书，得到server.crt
    openssl x509 -req -days 365 -CA ca.crt -CAkey ca.key -CAcreateserial -in server.csr -out server.crt -extensions req_ext -extfile server.conf
    
    # 存放文件
    /etc/pki/tls/certs/server.crt
    /etc/pki/tls/private/server.key
  
  # 配置防火墙
  firewall-cmd --permanent --add-service=https 
  firewall-cmd --permanent --add-port=443/tcp
  firewall-cmd --reload
  
  # 用户侧主机安装ca.crt
  右键ca.crt安装，安装到“受信任的根证书颁发机构”（不然server.crt还是不受信任的）
```

### 9 工具
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

### 10 串口
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

### 11 Python
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

### 11 SELinux
```
  1 临时关闭：
    getenforce
    setenforce 0

  2 永久关闭：
    vi /etc/sysconfig/selinux
    SELINUX=enforcing 改为 SELINUX=disabledcd

  3 查看状态
    sestatus
```

### owncloud 源
```
  # https://download.owncloud.org/download/repositories/stable/owncloud/index.html
  # Run the following shell commands as root to trust the repository.
  rpm --import https://download.owncloud.org/download/repositories/production/CentOS_7/repodata/repomd.xml.key
  wget http://download.owncloud.org/download/repositories/production/CentOS_7/ce:stable.repo -O /etc/yum.repos.d/ce:stable.repo
 
 
  yum install owncloud-files

  # Appache owncloud config
  Alias /owncloud "/var/www/html/owncloud/"
  
  <Directory /var/www/html/owncloud/>
    Options +FollowSymlinks
    AllowOverride All
  <IfModule mod_dav.c>
    Dav off
  </IfModule>
  </Directory>

  # db and account
  # account - freeair/Free@321
  
  CREATE USER 'oc_admin'@'localhost' IDENTIFIED BY 'Bing@753';
  CREATE DATABASE IF NOT EXISTS owncloud;
  GRANT ALL PRIVILEGES ON owncloud.* TO oc_admin@localhost;
  # 撤销授权
  revoke all on owncloud.* from oc_admin@localhost;
  Delete FROM user Where User='oc_admin' and Host='localhost';
  

  # add selinux config
  semanage fcontext -a -t httpd_sys_rw_content_t '/stations/oc_data(/.*)?'
  semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/owncloud/data(/.*)?'
  semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/owncloud/config(/.*)?'
  semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/owncloud/apps(/.*)?'
  semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/owncloud/apps-external(/.*)?'
  semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/owncloud/.htaccess'
  semanage fcontext -a -t httpd_sys_rw_content_t '/var/www/html/owncloud/.user.ini'

  restorecon -Rv '/var/www/html/owncloud/'
  restorecon -Rv '/stations/'

  # remove selinux config
  semanage fcontext -d '/stations/oc_data(/.*)?'
  semanage fcontext -d '/var/www/html/owncloud/data(/.*)?'
  semanage fcontext -d '/var/www/html/owncloud/config(/.*)?'
  semanage fcontext -d '/var/www/html/owncloud/apps(/.*)?'
  semanage fcontext -d '/var/www/html/owncloud/apps-external(/.*)?'
  semanage fcontext -d '/var/www/html/owncloud/.htaccess'
  semanage fcontext -d '/var/www/html/owncloud/.user.ini'
  
  restorecon -Rv '/var/www/html/owncloud/'
  restorecon -Rv '/stations/'
```

### 申请免费域名
```
  # chrome 安装“谷歌访问助手”插件
  # https://www.jianshu.com/p/6086ec29c173
    # 克隆或下载仓库到本地
    # 打开Chrome浏览器的扩展程序管理器，然后勾选开发者模式。
    # 在左上角点击加载已解压的扩展程序，选在在第一步下载的谷歌访问助手文件夹
    # 当然，如果想在其他非chrome浏览器安装谷歌访问助手，怎么办呢？别担心，开发者为我们考虑到这点。同样在上面的GitHub网页上，开发者提供了一个网站http://www.ggfwzs.com
    
  # 免费域名申请，需要gmail账号（freeair.sam@gmail.com）
  https://www.freenom.com/en/index.html
  www.binglang.cf  182.247.101.235
```

### 申请免费ssl证书
```
  # 为appache安装ssl模块，安装完后在/etc/httpd/conf.d/会有一个ssl.conf的文件，打开文件以后找到SSLCertificateFile和SSLCertificateKeyFile，可以看到后面我们要生成的密钥的配置信息
  yum install mod_ssl

  # https://www.cnblogs.com/esofar/p/9291685.html
  # https://www.jianshu.com/p/3aa5cb957d9f  
  
  # 用第三方客户端 acme.sh 申请
  # 把 acme.sh 安装到当前用户的主目录$HOME下的.acme.sh文件夹中，即~/.acme.sh/，之后所有生成的证书也会放在这个目录下
  # 安装 acme.sh
  cd ~/.acme.sh
  curl https://get.acme.sh | sh
  
  # 创建了一个指令别名alias acme.sh=~/.acme.sh/acme.sh，这样我们可以通过acme.sh命令方便快速地使用 acme.sh 脚本
  # acme.sh --version确认是否能正常使用acme.sh命令。
  
  # 生成证书
  # acme.sh --issue -d xxx.cn -d www.xxx.cn -w /var/www/html
  --issue是 acme.sh 脚本用来颁发证书的指令；
  -d是--domain的简称，其后面须填写已备案的域名；
  -w是--webroot的简称，其后面须填写网站的根目录。
  
  acme.sh --issue -d binglang.cf -d www.binglang.cf -w /var/www/html
  
  # 存放证书
  /etc/pki/tls/certs/fullchain.cer
  /etc/pki/tls/private/be-green.ga.key
  
  # 配置防火墙
  firewall-cmd --permanent --add-service=https 
  firewall-cmd --permanent --add-port=443/tcp
  firewall-cmd --reload
  
  # 重启httpd
  systemctl restart httpd.service
  
  # 检测网站的安全级别：
  https://myssl.com
  https://www.ssllabs.com
  
  # 更新证书，目前 Let's Encrypt 的证书有效期是90天，时间到了会自动更新，您无需任何操作
    # 强制续签证书
    acme.sh --renew -d example.com --force
 ```   
    
    

### [备选] 开启自签名SSL
```
  # https://www.cnblogs.com/nidey/p/9041960.html
  # https://www.cnblogs.com/walk1314/p/9100019.html
  
  # https://www.cnblogs.com/jie-hu/p/8034226.html
  # https://www.cnblogs.com/xiaoleiel/p/11160661.html
  # https://www.cnblogs.com/idjl/p/9610561.html
  
  # 为appache安装ssl模块，安装完后在/etc/httpd/conf.d/会有一个ssl.conf的文件，打开文件以后找到SSLCertificateFile和SSLCertificateKeyFile2行，可以看到后面我们要生成的密钥的配置信息
  yum install mod_ssl
  
  # openssl
  yum install openssl
  
  # CA侧
    # ca配置文件
    vi ca.conf
    [ req ]
    default_bits       = 4096
    distinguished_name = req_distinguished_name

    [ req_distinguished_name ]
    countryName                 = CN
    stateOrProvinceName         = YunNan
    localityName                = BaoShan
    organizationName            = BingLangJiang Co.,Ltd.
    organizationalUnitName      = Freeair Studio 
    commonName                  = Own CA
    commonName_max              = 64
  
    # 生成ca秘钥，得到ca.key
    openssl genrsa -out ca.key 4096
    
    # 生成ca证书签发请求，得到ca.csr
    openssl req -new -sha256 -out ca.csr -key ca.key -config ca.conf
    pwd/6669
    
    # 生成ca根证书，得到ca.crt
    openssl x509 -req -days 365 -in ca.csr -signkey ca.key -out ca.crt

  # appache侧
    # 配置文件
    vi server.conf
    [ req ]
    default_bits       = 2048
    distinguished_name = req_distinguished_name
    req_extensions     = req_ext

    [ req_distinguished_name ]
    countryName                 = Country Name (2 letter code)
    countryName_default         = CN
    stateOrProvinceName         = State or Province Name (full name)
    stateOrProvinceName_default = YunNan
    localityName                = Locality Name (eg, city)
    localityName_default        = BaoShan
    organizationName            = Organization Name (eg, company)
    organizationName_default    = BingLangJiang Co.,Ltd. 
    commonName                  = Common Name (e.g. server FQDN or YOUR name)
    commonName_max              = 64
    commonName_default          = www.be-green.com

    [ req_ext ]
    subjectAltName = @alt_names

    [alt_names]
    DNS.1   = www.be-green.com
    IP.1    = 182.247.101.235
    IP.2    = 192.168.205.60

    
    # 生成秘钥，得到server.key
    openssl genrsa -out server.key 2048
    
    # 生成证书签发请求，得到server.csr
    openssl req -new -sha256 -out server.csr -key server.key -config server.conf
    
    # 用CA证书签名证书，得到server.crt
    openssl x509 -req -days 365 -CA ca.crt -CAkey ca.key -CAcreateserial -in server.csr -out server.crt -extensions req_ext -extfile server.conf
    
    # 存放文件
    /etc/pki/tls/certs/server.crt
    /etc/pki/tls/private/server.key
  
  # 配置防火墙
  firewall-cmd --permanent --add-service=https 
  firewall-cmd --permanent --add-port=443/tcp
  firewall-cmd --reload
  
  # 用户侧主机安装ca.crt
  右键ca.crt安装，安装到“受信任的根证书颁发机构”（不然server.crt还是不受信任的）
```  

### 7 APP Web
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

