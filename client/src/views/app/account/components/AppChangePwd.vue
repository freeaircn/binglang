<!--
 * @Description:
 * @Author: freeair
 * @Date: 2021-01-18 09:35:37
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-18 22:02:38
-->
<template>
  <div>
    <el-dialog title="修改密码" :visible.sync="visible2" :destroy-on-close="true" @close="handleClose">
      <el-form ref="update_form" :model="formData" :rules="update_form_rules" label-position="top" label-width="auto">
        <el-form-item ref="new_pwd" label="新密码" prop="new_pwd">
          <el-input v-model="formData.new_pwd" name="new_pwd" type="text" tabindex="1" placeholder="输入新密码" show-password clearable />
        </el-form-item>

        <el-form-item ref="new_pwd2" label="新密码确认" prop="new_pwd2">
          <el-input v-model="formData.new_pwd2" name="new_pwd2" type="text" tabindex="2" placeholder="再次输入新密码" show-password clearable />
        </el-form-item>

        <el-form-item>
          <el-button type="primary" style="width:100%;" @click.native.prevent="handlePostBtn">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { validPassword } from '@/utils/app/validator/common'

export default {
  name: 'AppChangePwd',
  props: {
    visible: {
      type: Boolean,
      default: () => { return false }
    }
  },
  data() {
    const validatePassword2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'))
      } else if (value !== this.formData.new_pwd) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      formData: {
        new_pwd: '',
        new_pw2: ''
      },
      update_form_rules: {
        new_pwd: [{ required: true, trigger: 'change', validator: validPassword }],
        new_pwd2: [{ required: true, trigger: 'change', validator: validatePassword2 }]
      }
    }
  },
  computed: {
    visible2: {
      get() {
        return this.visible
      },
      set(val) {
        this.$emit('update:visible', val)
      }
    }
  },
  methods: {
    handleClose() {
      this.$refs['update_form'].resetFields()
    },

    handlePostBtn() {
      this.$refs['update_form'].validate(valid => {
        if (valid) {
          this.$emit('post', this.formData.new_pwd)
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
