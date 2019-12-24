<!--
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-24 11:34:46
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-24 18:50:15
 -->
# GIT仓库
***1. 配置用户信息***
```
cmd /c "git config --global user.name "xxx""
cmd /c "git config --global user.email "xxx@163.com""
```
***2. 配置SSH***  
```
ssh-keygen -t rsa -C xxx@163.com
# id_rsa是私钥，id_rsa.pub是公钥，登陆GitHub，打开“Account settings”，“SSH Keys”页面在Key文本框里粘贴id_rsa.pub文件的内容
```
***3. 创建并推送本地仓库*** 
```
1) GitHub创建binglang.git

2) 创建本地仓库，D:\www\binglang，存放项目代码
git init

3) 本地仓库关联远程仓库。项目根目录路径下，执行命令
git remote add origin git@github.com:freeaircn/binglang.git

4) 本地仓库推送至远程仓库
git push -u origin master
```
***4. 复制GitHub仓库***   
```
git clone git@github.com:freeaircn/binglang.git
```
***5. 更新本地仓库***   
```
git pull
```