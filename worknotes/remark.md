# 笔记

---
### 说明  
1. 记录小知识点: [全局] [vue] [ci]

---
### 1 vue-cli3 api_base_url  
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
### 2