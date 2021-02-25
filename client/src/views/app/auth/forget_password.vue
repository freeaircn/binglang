<template>
  <div class="wrapper">
    <div class="container container-responsive mb-4">
      <div class="py-3 is-center">
        <router-link to="/home"><span><svg-logo logo-class="be_green" /></span></router-link>
      </div>
      <el-form ref="form" :model="formData" :rules="rules" label-position="left">
        <el-form-item ref="phone" prop="phone">
          <el-input ref="phone_input" v-model="formData.phone" name="phone" type="text" tabindex="1" placeholder="输入注册的手机号" clearable />
        </el-form-item>

        <el-form-item ref="code" prop="code">
          <el-input v-model="formData.code" name="code" type="text" tabindex="3" style="width:55%;float:left;" placeholder="输入验证码" clearable />
          <el-button type="primary" plain style="width:40%;float:right;" tabindex="2" :disabled="isBtnDisable" @click.native.prevent="handleRequestCode">{{ btnReqCodeText }}</el-button>
        </el-form-item>

        <el-form-item ref="password" prop="password">
          <el-input v-model="formData.password" name="password" type="text" tabindex="4" placeholder="输入新密码" show-password clearable />
        </el-form-item>

        <el-form-item ref="password2" prop="password2">
          <el-input v-model="formData.password2" name="password2" type="text" tabindex="5" placeholder="再次输入新密码" show-password clearable />
        </el-form-item>

        <div style="margin: 0px 0px 15px 0px; color: #409EFF;"><router-link to="/login">去登录</router-link></div>

        <el-form-item>
          <el-button type="primary" plain style="width:45%" @click.native.prevent="handleClickClear">清 空</el-button>
          <el-button :loading="loading" tabindex="6" type="primary" style="width:45%; float:right;" @click.native.prevent="handleClickBtn">忘记密码</el-button>
        </el-form-item>
      </el-form>

    </div>
  </div>
</template>

<script>
import * as validator from '@/utils/app/validator/common'
import { apiReqVerificationCode, apiResetPassword } from '@/api/app/auth'

export default {
  name: 'AuthForgetPassword',
  data() {
    const validatePassword2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'))
      } else if (value !== this.formData.password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      // 获取验证码按钮
      isBtnDisable: false,
      btnReqCodeText: '获取验证码',
      timer60: '',
      countdown: 60,

      formData: {
        phone: '',
        code: '',
        password: '',
        password2: ''
      },
      rules: {
        phone: [{ required: true, pattern: validator.phone.regex, message: validator.phone.msg }],
        code: [{ required: true, pattern: validator.verificationCode.regex, message: validator.verificationCode.msg }],
        password: [{ required: true, pattern: validator.password.regex, message: validator.password.msg }],
        password2: [{ required: true, trigger: 'change', validator: validatePassword2 }]
      },
      loading: false
    }
  },
  mounted() {
    if (this.formData.phone === '') {
      this.$refs['phone_input'].focus()
    }
  },
  methods: {
    // 1 请求验证码
    handleRequestCode() {
      this.$refs['form'].validateField('phone')
      const isFieldValid = this.$refs['phone'].validateMessage
      if (isFieldValid === '') {
        this.disableRequestCodeBtn()

        apiReqVerificationCode({ phone: this.formData.phone })
          .then(function(data) {
            if (data.email) {
              this.$message({
                type: 'info',
                message: '验证码已发送至邮箱：' + data.email,
                duration: 3 * 1000
              })
            }
          }.bind(this))
          .catch(function(err) {
            this.$message({
              type: 'error',
              message: err,
              duration: 3 * 1000
            })
          }.bind(this))
      }
    },

    // 2 disable 获取验证码button
    disableRequestCodeBtn() {
      this.isBtnDisable = true
      this.btnReqCodeText = '重新发送(' + this.countdown + ')'
      if (!this.timer60) {
        this.timer60 = setInterval(() => {
          if (this.countdown > 0 && this.countdown <= 60) {
            this.countdown--
            if (this.countdown !== 0) {
              this.btnReqCodeText = '重新发送(' + this.countdown + ')'
            } else {
              clearInterval(this.timer60)
              this.isBtnDisable = false
              this.btnReqCodeText = '获取验证码'
              this.countdown = 60
              this.timer60 = null
            }
          }
        }, 1000)
      }
    },

    // 3 提交请求
    handleClickBtn() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          this.loading = true
          apiResetPassword(this.formData)
            .then(function() {
              this.loading = false
              this.$router.replace({ path: '/login' })
            }.bind(this))
            .catch(function(err) {
              this.loading = false
              this.$refs['form'].resetFields()
              this.$message({
                type: 'error',
                message: err
              })
            }.bind(this))
        }
      })
    },

    handleClickClear() {
      this.$refs['form'].resetFields()
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.wrapper {
  width: 100%;
  height: 100%;
  background-color:  $bg-gray-light;
}
.container {
  position: absolute;
  left: 0;
  right: 0;
  top: 10%;
}
</style>
