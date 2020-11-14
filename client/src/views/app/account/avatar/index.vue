<!--
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-22 23:15:14
 * @LastEditors: freeair
 * @LastEditTime: 2020-11-14 18:52:21
-->
<template>
  <div>
    <div class="pages-account-settings-avatar-title">头像</div>
    <el-upload
      :action="uploadApi"
      :multiple="false"
      :show-file-list="false"
      accept="png"
      list-type="picture"
      :on-success="handleAvatarSuccess"
      :before-upload="beforeAvatarUpload"
    >
      <el-image
        :src="avatarUrl"
        fit="cover"
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
export default {
  name: 'AppAvatar',
  props: {
    avatarUrl: {
      type: String,
      default: () => { return '' }
    },
    uploadApi: {
      type: String,
      default: () => { return '' }
    }
  },
  methods: {
    /**
     * @Description: 1 检查图片文件类型和大小
     * @Author: freeair
     * @Date: 2020-11-14 09:52:41
     * @param {*} file
     * @return {*}
     */
    beforeAvatarUpload(file) {
      const isLt2M = file.size / 1024 / 1024 < 2
      const isJPG = file.type === 'image/jpeg'
      const isPNG = file.type === 'image/png'

      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!')
        return false
      }
      if (isJPG || isPNG) {
        return true
      }
      this.$message.error('上传头像图片只能是 JPG或PNG 格式!')
      return false
    },

    /**
     * @Description: 2 图片上传成功，发event通知父组件
     * @Author: freeair
     * @Date: 2020-11-14 09:53:23
     * @param {*} res
     * @param {*} file
     * @return {*}
     */
    handleAvatarSuccess(res, file) {
      this.$emit('upload-success', res)
      // this.avatarUrl = URL.createObjectURL(file.raw)
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
