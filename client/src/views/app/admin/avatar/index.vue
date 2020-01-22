<!--
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-22 23:15:14
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-23 00:08:34
 -->
<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <div>
        <el-upload
          class="avatar-uploader"
          action="http://127.0.0.1/api/avatar/update"
          :show-file-list="false"
          list-type="picture"
          :on-success="handleAvatarSuccess"
          :before-upload="beforeAvatarUpload"
        >
          <img v-if="imageUrl" :src="imageUrl" class="avatar">
          <i v-else class="el-icon-plus avatar-uploader-icon" />
        </el-upload>
      </div>
    </div>
  </div>
</template>

<script>
// import 第三方组件

// import 公共method

// import api

export default {
  name: 'AdminAvatar',
  data() {
    return {
      imageUrl: 'http://127.0.0.1/resource/avatar/1.png'
    }
  },

  methods: {
    handleAvatarSuccess(res, file) {
      console.log('#1')
      console.log(res)
      console.log('#2')
      console.log(file)
      this.imageUrl = URL.createObjectURL(file.raw)
      console.log(this.imageUrl)
    },
    beforeAvatarUpload(file) {
      const isLt2M = file.size / 1024 / 1024 < 2

      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!')
      }
      return isLt2M
    }
  }
}
</script>

<style>
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
</style>
