# GIT仓库
***1. 配置用户信息***
```
cmd /c "git config --global user.name "freeaircn""
cmd /c "git config --global user.email "freeaircn@163.com""
```
***2. 配置SSH***  
```
ssh-keygen -t rsa -C freeaircn@163.com
# id_rsa是私钥，id_rsa.pub是公钥，登陆GitHub，打开“Account settings”，“SSH Keys”页面在Key文本框里粘贴id_rsa.pub文件的内容
```
***3. 创建项目仓库*** 
```
1) GitHub创建binglang.git

2) 创建本地仓库，D:\www\binglang，存放项目代码
git init

3) 本地仓库关联远程仓库。项目根目录路径下，执行命令
git remote add origin git@github.com:freeaircn/binglang.git
  
4) 克隆远程仓库
git clone git@github.com:freeaircn/binglang.git