# Wiki - App

---
### 1. php，js，Python时间戳的比较
```
1 单位问题：
  php中取时间戳时，大多通过time()方法来获得，它获取到数值是以秒作为单位的。
  
  javascript中，从Date对象的getTime()方法中获得的数值是以毫秒为单位。
  
  Python time time() 返回当前时间的时间戳（1970纪元后经过的浮点秒数）。

2 时区问题：
  在php代码中会设置好当前服务器所在的时区，如中国大陆的服务器通常会设置成东八区。
    php.ini 文件
    [Date]
    ; Defines the default timezone used by the date functions
    ; http://php.net/date.timezone
    date.timezone = "Asia/Shangha"
  time()方法获得的方法就不再是从1970年1月1日0时0分0秒起，而是从1970年1月1日8时0分0秒起。

  js中通常没有作时区相关的设置，以1970年1月1日0时0分0秒为计算的起点的，所以容易在这个地方造成不一致。

```

---
### 2. HTTP交互
```
1 axios params和data
  在使用axios时，配置选项中包含params和data两者:
  
  params是添加到url的请求字符串中的，用于get请求。 
   
  data是添加到请求体（body）中的， 用于post请求。

2 Content-Type
  在Http协议消息头中，使用Content-Type来表示具体请求中的媒体类型信息.
  
  常见的媒体格式类型如下：
    text/html ： HTML格式
    text/plain ：纯文本格式      
    text/xml ：  XML格式
    image/gif ：gif图片格式    
    image/jpeg ：jpg图片格式 
    image/png：png图片格式
  以application开头的媒体格式类型：
    application/xhtml+xml ：XHTML格式
    application/xml     ： XML数据格式
    application/atom+xml  ：Atom XML聚合格式    
    application/json    ： JSON数据格式
    application/pdf       ：pdf格式  
    application/msword  ： Word文档格式
    application/octet-stream ： 二进制流数据（如常见的文件下载）
    application/x-www-form-urlencoded ： <form encType=””>中默认的encType，form表单数据被编码为key/value格式发送到服务器（表单默认的提交数据的格式

3 axios的post 不采用qs 序列化，采用转换json
  undefined或空数组，axios post 提交时，qs不填入http body。

  // Post请求，指定转换请求方法，使用json
  axios.defaults.transformRequest = [function(data) {
    // return qs.stringify(data, { arrayFormat: 'indices' })
    return JSON.stringify(data)
  }]

  // Get请求，指定请求参数序列号方法
  axios.defaults.paramsSerializer = function(params) {
    return qs.stringify(params, { arrayFormat: 'indices' })
  }

4 axios post 的data类型是“对象”，使用JSON.stringify(data)转换后，在http的请求payload中变为对象的键值对形式，form表单的input输入都当作字符串，所以值的数据类型都是string：
  {
    key1 : "123",
    key2 : "abc"
  }

5 CI、restful、DB
  
  1 接收get请求，$client的类型是“关联数组”。
    $client = $this->get();

  2 接收post，put，delete请求，$client的类型是“关联数组”。
    $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
    
    $client = json_decode($stream_clean, true);
    
  3 写DB
    ?? php的弱类型
    DB数据类型是”int“，但写入的数据类型是”string“，例如：
    DB的sort数据类型是int，写入DB sort = "123"，DB表中也能正确存储123。
    
  4 读DB
    ci 查询数据表结果->result_array()，为二维数组结构。即使查询结果只有一行，也是二维数组。第一维索引是数字，每一行是一维数组，且是关联数组。例如：
    一条结果
    [
      [0] => [
        "id" => "2",
        "name" => "A"
      ]
    ]
    多条结果
    [
      [0] => [
        "id" => "2",
        "name" => "A"
      ],
      [1] => [
        "id" => "3",
        "name" => "B"
      [
    ]
    
    CI的DB API读取的结果，所有值的数据类型都是”string“，例如：
    DB数据表的sort数据类型int，sort：13，CI的读取结果是sort：“13”。

  5 发送响应，$res的类型是“关联数组”。
    $res['code'] = App_Code::SUCCESS;
    $res['msg']  = App_Msg::SUCCESS;

    $this->response($res, 200);
    
    同样，在http reponse中转换为json，值的“数据类型”
    {
      "key1" : "123",
      "key2" : 12
    }

6 axios接收响应，response.data的数据类型是”对象“
  console.log(response.data)
  __proto__: Object
  
```

---
### 3.  
```


```


---
### x
```

```
