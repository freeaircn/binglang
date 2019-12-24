# 发布前Check List

---
### 说明  
检查Check List中每一项要求，满足后打包，将server文件夹上传至服务器.   
关键字: [全局] [vue] [ci]

---
### Check List
```
1 修改VUE打包config    
  [vue]client\src\main.js
  /**
   * If you don't want to use mock-server
   * you want to use MockJs for mock api
   * you can execute: mockXHR()
   *
   * Currently MockJs will be used in the production environment,
   * please remove it before going online! ! !
   */
  // import { mockXHR } from '../mock'
  // if (process.env.NODE_ENV === 'production') {
  //   mockXHR()
  // }

2 修改生产环境账号密码  
  [ci]数据库连接config文件-server\application\config\database.php

3 修改base_url
  [ci]server\application\config\config.php
  # $config['base_url'] = 'http://127.0.0.1';
```




