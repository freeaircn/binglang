# Wiki - JS, PHP

---
### 1. JS 数据类型
```
1. 数据类型

  值类型(基本类型)：字符串（String）、数字(Number)、布尔(Boolean)、对空（Null）、未定义（Undefined）、Symbol。
  
  引用数据类型：对象(Object)、数组(Array)、函数(Function)。

2. 数组

  下面的代码创建名为 cars 的数组：
  var cars=new Array();
  cars[0]="Saab";
  cars[1]="Volvo";
  
  或者：var cars=new Array("Saab","Volvo","BMW");
  
  或者：var cars=["Saab","Volvo","BMW"];
  
  数组下标是基于零的，所以第一个项目是 [0]，第二个是 [1]，以此类推。

3. 对象

  对象由花括号分隔。在括号内部，对象的属性以名称和值对的形式 (name : value) 来定义。属性由逗号分隔：
  
  var person={firstname:"John", lastname:"Doe", id:5566};
  
  对象属性有两种寻址方式：
  name=person.lastname;
  name=person["lastname"];
  
  对象的方法定义了一个函数，并作为对象的属性存储。
  对象方法通过添加 () 调用 (作为一个函数)。
  可以使用以下语法创建对象方法：
  methodName : function() {
      // 代码 
  }
  你可以使用以下语法访问对象方法：
  objectName.methodName()
  
  
  原型：原型上包括了继承属性，有可以枚举的属性和不可以枚举的属性。默认对象都继承了Object。
  自身：自身属性同样包括了可枚举的属性和不可枚举的属性。
    
    Object.keys(obj)返回不包括原型上的可枚举属性，即自身的可枚举属性。
      Object.keys(data).length === 0;
    Objcet.getOwnPropertyNames(obj)返回不包括原型上的所有自身属性(包括不可枚举的属性)
      Object.getOwnPropertyNames(data)===0;


4. Undefined 和 Null 

  Undefined 这个值表示变量不含有值。
  可以通过将变量的值设置为 null 来清空变量。

5. 声明变量类型

  可以使用关键词 "new" 来声明其类型：
  var carname=new String;
  var x=      new Number;
  var y=      new Boolean;
  var cars=   new Array;
  var person= new Object;

```

---
### 2. JS 循环
```
1 For in 循环
  
  for/in 语句循环遍历对象的属性：
  
  var person={fname:"Bill",lname:"Gates",age:56}; 
  for (x in person)  // x 为属性名
  {
      txt=txt + person[x];
  }

```

---
### 3. JS typeof, null, 和 undefined
```
1 使用 typeof 操作符来检测变量的数据类型。

  typeof "John"                // 返回 string
  typeof 3.14                  // 返回 number
  typeof false                 // 返回 boolean
  typeof [1,2,3,4]             // 返回 object
  typeof {name:'John', age:34} // 返回 object

2 null是一个只有一个值的特殊类型。表示一个空对象引用。

  用 typeof 检测 null 返回是object。
  可以设置为 null 来清空对象:
  var person = null;           // 值为 null(空), 但类型为对象

  可以设置为 undefined 来清空对象:
  var person = undefined;     // 值为 undefined, 类型为 undefined

3 undefined 是一个没有设置值的变量。

  typeof 一个没有值的变量会返回 undefined。
  var person;                  // 值为 undefined(空), 类型是undefined

  任何变量都可以通过设置值为 undefined 来清空。 类型为 undefined.
  person = undefined;          // 值为 undefined, 类型是undefined

4 null 和 undefined 的值相等，但类型不等：

  typeof undefined             // undefined
  typeof null                  // object
  null === undefined           // false
  null == undefined            // true

```

---
### 4 JS import / require
```

1 require 运行时加载，因为只有运行时才能得到这个对象，不能在编译时做到静态化。
  import ES6模块不是对象，而是通过export命令显示指定输出代码，再通过import输入。
  
  加载方式	    规范	          命令	      特点
  运行时加载	  CommonJS/AMD	  require	    社区方案，提供了服务器/浏览器的模块加载方案。非语言层面的标准。只能在运行时确定模块的依赖关系及输入/输出的变量，无法进行静态优化。
  编译时加载	  ESMAScript6+	  import	    语言规格层面支持模块功能。支持编译时静态分析，便于JS引入宏和类型检验。动态绑定。


2 使用
  导出lib.js
    export function func1(x) {
        return x * x;
    }
    
    export function func2(x, y) {
        return ;
    }
  
  引用
  //方法一
  import { func1, func2 } from 'lib';
  console.log(func1());
  console.log(func2());

  //方法二
  import * as utils from 'lib';
  utils.func1();

```

---
### 5. PHP 数据类型
```
1. 数据类型

  String（字符串）, Integer（整型）, Float（浮点型）, Boolean（布尔型）, Array（数组）, Object（对象）, NULL（空值）。

2. 数组

  在 PHP 中，有三种类型的数组：
    数值数组 - 带有数字 ID 键的数组
    关联数组 - 带有指定的键的数组，每个键关联一个值
    多维数组 - 包含一个或多个数组的数组

  数值数组
    自动分配 ID 键（ID 键总是从 0 开始）：
    $cars=array("Volvo","BMW","Toyota");
    
    人工分配 ID 键：
    $cars[0]="Volvo";
    $cars[1]="BMW";
    $cars[2]="Toyota";
  
  遍历数值数组
    使用 for 循环，如下所示：
      <?php
      $cars=array("Volvo","BMW","Toyota");
      $arrlength=count($cars);
       
      for($x=0;$x<$arrlength;$x++)
      {
          echo $cars[$x];
          echo "<br>";
      }
  
  关联数组
    有两种创建关联数组的方法：
    $age=array("Peter"=>"35","Ben"=>"37","Joe"=>"43");
    
    或者
    $age['Peter']="35";
    $age['Ben']="37";
    $age['Joe']="43";
  
  遍历关联数组
    使用 foreach 循环，如下所示：
      <?php
      $age=array("Peter"=>"35","Ben"=>"37","Joe"=>"43");
       
      foreach($age as $x=>$x_value)
      {
          echo "Key=" . $x . ", Value=" . $x_value;
          echo "<br>";
      }
      ?>
  
  

3. 对象


4. Null 

  NULL 值表示变量没有值。NULL 是数据类型为 NULL 的值。
  NULL 值指明一个变量是否为空值。 同样可用于数据空值和NULL值的区别。
  可以通过设置变量值为 NULL 来清空变量数据：

5. 使用 PHP 函数对变量 $x 进行比较

  表达式	        gettype()	  empty()	  is_null()	  isset()	  boolean : if($x)
  $x = "";	      string	    TRUE	      FALSE	    TRUE	    FALSE
  $x = null;	    NULL	      TRUE	      TRUE	    FALSE	    FALSE
  var $x;	        NULL	      TRUE	      TRUE	    FALSE	    FALSE
  $x is undefined	NULL	      TRUE	      TRUE	    FALSE	    FALSE
  $x = array();	  array	      TRUE	      FALSE	    TRUE	    FALSE
  $x = FALSE;	    boolean	    TRUE	      FALSE	    TRUE	    FALSE
  $x = TRUE;	    boolean	    FALSE	      FALSE	    TRUE	    TRUE
  $x = 1;	        integer	    FALSE	      FALSE	    TRUE	    TRUE
  $x = 42;	      integer	    FALSE	      FALSE	    TRUE	    TRUE
  $x = 0;	        integer	    TRUE	      FALSE	    TRUE	    FALSE
  $x = -1;	      integer	    FALSE	      FALSE	    TRUE	    TRUE
  $x = "1";	      string	    FALSE	      FALSE	    TRUE	    TRUE
  $x = "0";	      string	    TRUE	      FALSE	    TRUE	    FALSE
  $x = "-1";	    string	    FALSE	      FALSE	    TRUE	    TRUE
  $x = "php";	    string	    FALSE	      FALSE	    TRUE	    TRUE
  $x = "TRUE";	  string	    FALSE	      FALSE	    TRUE	    TRUE
  $x = "FALSE";	  string	    FALSE	      FALSE	    TRUE	    TRUE

  多维数组入参。比如；a = [[], []], empty(a) false

6 PHP 常量

  常量值被定义后，在脚本的其他任何地方都不能被改变。
  一个常量由英文字母、下划线、和数字组成,但数字不能作为首字母出现。 (常量名不需要加 $ 修饰符)。

  设置常量，使用 define() 函数，函数语法如下：
  bool define ( string $name , mixed $value [, bool $case_insensitive = false ] )

  常量在定义后，默认是全局变量，可以在整个运行的脚本的任何地方使用。即便常量定义在函数外也可以正常使用常量。

```

---
### 6. PHP 循环
```
1 foreach  循环
  
  foreach 循环用于遍历数组。
  语法
  foreach ($array as $value)
  {
      要执行代码;
  }
  每进行一次循环，当前数组元素的值就会被赋值给 $value 变量（数组指针会逐一地移动），在进行下一次循环时，您将看到数组中的下一个值。

  foreach ($array as $key => $value)
  {
      要执行代码;
  }
  每一次循环，当前数组元素的键与值就都会被赋值给 $key 和 $value 变量（数字指针会逐一地移动），在进行下一次循环时，你将看到数组中的下一个键与值。

```

---
### x
```

```
