# VS Code配置
---
### 1 共享vs setting  
```
# 使用Settings Sync 插件，实现vs setting上传至GitHub，多地共享vs setting
1. Upload Key : Shift + Alt + U
2. Download Key : Shift + Alt + D
```
---

### 2 常用插件
```
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
  