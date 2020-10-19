<!--
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-22 23:15:14
 * @LastEditors: freeair
 * @LastEditTime: 2020-10-19 22:40:04
 -->
<template>
  <div>
    <div class="pages-account-settings-avatar-title">头像</div>
    <el-upload
      :action="uploadApi"
      :show-file-list="false"
      list-type="picture"
      :on-success="handleAvatarSuccess"
      :before-upload="beforeAvatarUpload"
    >
      <!-- <img v-if="imageUrl" :src="imageUrl" class="pages-account-settings-avatar"> -->
      <!-- <i v-else class="el-icon-plus pages-account-settings-avatar-uploader-icon" /> -->
      <el-image
        :src="imageUrl"
        class="pages-account-settings-avatar"
      >
        <div slot="error" class="pages-account-settings-avatar-img-slot">
          <i class="el-icon-picture-outline" />
        </div>
      </el-image>
    </el-upload>
  </div>

</template>

<script>
// import 第三方组件

// import 公共method

// import api

export default {
  name: 'AppAvatar',
  data() {
    return {
      imageUrl: process.env.VUE_APP_BASE_API + '/resource/avatar/px200/avatar_4.jpg',
      uploadApi: process.env.VUE_APP_BASE_API + '/api/avatar/update'
    }
  },
  created() {
    var seed = Math.floor((Math.random() * 20) + 1)
    this.imageUrl = process.env.VUE_APP_BASE_API + '/resource/avatar/px200/avatar_' + seed.toString() + '.jpg'
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

<style rel="stylesheet/scss" lang="scss" scoped>
.pages-account-settings-avatar-title {
  margin-bottom: 8px;
  font-size: $font-size-base;
  color: $text-color;
}

.pages-account-settings-avatar {
  width: 178px;
  height: 178px;
  border-radius: 50%;
  display: block;
}

.pages-account-settings-avatar-img-slot {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  color: #8c939d;
}
</style>
