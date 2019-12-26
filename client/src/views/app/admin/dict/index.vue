<!--
 * @Description: 
 * @Author: freeair
 * @Date: 2019-12-25 16:05:49
 * @LastEditors  : freeair
 * @LastEditTime : 2019-12-26 17:03:13
 -->
<template>
  <div class="app-container">

    <!-- 页面上半部分：显示字典 list -->
    <el-divider><i class="el-icon-download">字典检索区</i></el-divider>
    <dict ref="dict" />
  
    <!-- 页面下半部分：显示词条 list -->
    <el-divider><i class="el-icon-download">词条检索区</i></el-divider>
    <dictItem ref="dictItem" />

  </div>
</template>

<script>
// Import API

// import component
import dict from './dict'
import dictItem from './dict-item'

export default {
  name: 'admin_dict',
  components: { dict, dictItem },
    data() {
    return {
      showF: false,     
    }
  },
  methods: {
    
  }
}
</script>
