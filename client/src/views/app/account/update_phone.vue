<template>
  <div class="find-pwd-wrapper">
    <div class="find-pwd-container mb-4">
      <div class="find-pwd-header py-responsive is-center">
        <router-link to="/home"><span><svg-logo logo-class="be_green" /></span></router-link>
      </div>

      <el-form ref="form" :model="formData" :rules="findPwdRules" class="container-sm px-responsive" label-position="left">
        <el-form-item ref="phone" prop="phone">
          <el-input ref="phone_input" v-model="formData.phone" name="phone" type="text" tabindex="1" prefix-icon="el-icon-mobile-phone" :disabled="isValidCode" placeholder="输入注册的手机号" clearable />
        </el-form-item>

        <el-form-item>
          <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleClickBtn">{{ btnText }}</el-button>
        </el-form-item>
      </el-form>

    </div>
  </div>
</template>

<script>
// import { validPhone, validVerificationCode, validPassword } from '@/utils/app/validator/common'
import { apiReqVerificationCode, apiValidVerificationCode, apiReqResetPassword } from '@/api/app/auth'

export default {
  name: 'AccountUpdatePhone',
  data() {
    // const validatePassword2 = (rule, value, callback) => {
    //   if (value === '') {
    //     callback(new Error('请再次输入密码'))
    //   } else if (value !== this.findPwdForm.newPassword) {
    //     callback(new Error('两次输入密码不一致!'))
    //   } else {
    //     callback()
    //   }
    // }
    return {
      isValidCode: false,
      btnText: '用户验证',

      // 获取验证码按钮
      isBtnDisable: false,
      btnReqCodeText: '获取验证码',
      timer60: '',
      countdown: 60,

      formData: {
        phone: '',
        verificationCode: '',
        newPassword: '',
        newPassword2: ''
      },
      findPwdRules: {
        // phone: [{ required: true, trigger: 'change', validator: validPhone }],
        // verificationCode: [{ required: true, trigger: 'change', validator: validVerificationCode }],
        // newPassword: [{ required: true, trigger: 'change', validator: validPassword }],
        // newPassword2: [{ required: true, trigger: 'change', validator: validatePassword2 }]
      },
      loading: false
    }
  },
  mounted() {
    // if (this.findPwdForm.phone === '') {
    //   this.$refs['phone_input'].focus()
    // }
  },
  methods: {
    handleRequestCode() {
      this.$refs.findPwdForm.validateField('phone')
      const isFieldValid = this.$refs.phone.validateMessage
      if (isFieldValid === '') {
        // disable 获取验证码button
        this.disableRequestCodeBtn()

        apiReqVerificationCode({ phone: this.findPwdForm.phone })
          .then(function(data) {
            if (data.email) {
              this.$message({
                type: 'info',
                message: '验证码已发送至邮箱：' + data.email,
                duration: 3 * 1000
              })
            } else {
              this.$message({
                type: 'info',
                message: data.msg,
                duration: 3 * 1000
              })
            }
          }.bind(this))
          .catch(function(err) {
            console.log(err)
            this.$message({
              type: 'info',
              message: '请重新获取验证码!',
              duration: 3 * 1000
            })
          }.bind(this))
      }
    },

    // disable 获取验证码button
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

    //
    handleClickBtn() {
      // if (this.isValidCode === false) {
      //   this.handleCheckVerificationCode()
      // } else {
      //   this.handleResetPassword()
      // }
    },

    handleCheckVerificationCode() {
      this.$refs.findPwdForm.validateField('phone')
      this.$refs.findPwdForm.validateField('verificationCode')
      const isFieldValid = this.$refs.phone.validateMessage + this.$refs.verificationCode.validateMessage

      if (isFieldValid === '') {
        this.loading = true

        const formData = {
          phone: this.findPwdForm.phone,
          verification_code: this.findPwdForm.verificationCode
        }
        apiValidVerificationCode(formData)
          .then(function() {
            // 验证码正确
            this.loading = false
            this.isValidCode = true
            this.btnText = '重置密码'
          }.bind(this))
          .catch(function(err) {
            this.loading = false
            this.$message({
              type: 'warning',
              message: err
            })
            this.isValidCode = false
            this.btnText = '用户验证'
            this.findPwdForm.verificationCode = ''
            this.findPwdForm.newPassword = ''
            this.findPwdForm.newPassword2 = ''
          }.bind(this))
      }
    },

    handleResetPassword() {
      this.$refs['findPwdForm'].validate(valid => {
        if (valid) {
          this.loading = true
          apiReqResetPassword(this.findPwdForm)
            .then(function() {
              // 重置密码成功
              this.loading = false
              this.isValidCode = false
              this.btnText = '用户验证'
              this.$router.replace({ path: '/login' })
            }.bind(this))
            .catch(function(err) {
              this.loading = false
              this.$message({
                type: 'warning',
                message: err
              })
              this.isValidCode = false
              this.btnText = '用户验证'
              this.findPwdForm.phone = ''
              this.findPwdForm.verificationCode = ''
              this.findPwdForm.newPassword = ''
              this.findPwdForm.newPassword2 = ''
              console.log(err)
            }.bind(this))
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">

/* reset element-ui css */
.find-pwd-container {
  .el-form-item {
    background: transparent;
    border: 1px solid $border_gray;
    border-radius: 5px;
  }
  .el-input {
    display: inline-block;
    width  : 85%;
    input {
      background        : transparent;
      border            : 0px;
      border-radius     : 0px;
    }
  }
  .code-field {
    @media (min-width: 320px) {
      width: 200px;
    }
    @media (min-width: 568px) {
      width: 320px;
    }
  }
}
</style>

<style rel="stylesheet/scss" lang="scss" scoped>

.find-pwd-wrapper {
  width: 100%;
  height: 100%;
  background-color:  $bg-gray-light;
}
.find-pwd-container {
  position: absolute;
  left: 0;
  right: 0;
  top: 10%;
}
.title {
  font-weight: 400;
  color: $title-color;
  letter-spacing: 6px;
}
.svg-container {
  padding: 0px 8px 0px 16px;
  vertical-align: middle;
  display: inline-block;
}
.btn-code {
  border: 0px;
  width: 90px;
  padding: 12px 4px;
  float: right;
  color: white;
  background-color:  $blue-light;
}
.show-pwd {
  width: 30px;
  padding-left: 8px;
  padding-right: 8px;
  color: $gray;
  cursor: pointer;
  user-select: none;
  float: right;
}
</style>
