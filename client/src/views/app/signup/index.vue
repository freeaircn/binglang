<template>
  <div class="signup-wrapper">
    <div class="signup-container mb-4">
      <div class="signup-header py-responsive is-center">
        <router-link to="/home"><span><svg-logo logo-class="be_green" /></span></router-link>
        <h2 class="title">注册</h2>
      </div>

      <el-form ref="signupForm" :model="signupForm" :rules="signupFormRules" class="container-sm px-responsive" label-position="left" >
        <el-form-item ref="email" prop="email" >
          <span class="svg-container">
            <svg-icon icon-class="icon_dmail_fill" />
          </span>
          <!-- <el-input v-model="signupForm.email" :disabled="isValidCode === true" name="email" type="text" placeholder="电子邮箱" clearable @blur="checkInfo('email')" /> -->
          <el-input v-model="signupForm.email" :disabled="isValidCode === true" name="email" type="text" placeholder="电子邮箱" clearable />
        </el-form-item>

        <el-form-item v-show="isValidCode === false" ref="verificationCode" prop="verificationCode">
          <span class="svg-container">
            <svg-icon icon-class="verification_code" />
          </span>
          <el-input v-model="signupForm.verificationCode" type="text" class="code-field" name="verificationCode" placeholder="输入验证码" clearable />
          <input v-model="btnCodeConent" :disabled="isBtnDisable" type="button" class="btn-code" @click="handleRequestCode">
        </el-form-item>

        <el-form-item v-show="isValidCode === true" ref="userphone" prop="userphone" >
          <span class="svg-container">
            <svg-icon icon-class="icon_mobilephone" />
          </span>
          <el-input v-model="signupForm.userphone" name="userphone" type="text" placeholder="手机号" clearable @blur="checkInfo('phone')" />
        </el-form-item>

        <el-form-item v-show="isValidCode === true" prop="password">
          <span class="svg-container">
            <svg-icon icon-class="password" />
          </span>
          <el-input v-model="signupForm.password" name="password" placeholder="密码" show-password clearable />
        </el-form-item>

        <el-form-item v-show="isValidCode === true" prop="confirmPassword">
          <span class="svg-container">
            <svg-icon icon-class="password2" />
          </span>
          <el-input v-model="signupForm.confirmPassword" name="confirmPassword" placeholder="再次输入密码" show-password clearable />
        </el-form-item>

        <div style="margin: 0px 0px 15px 0px; color: #409EFF;"><router-link to="/login">已有账号？去登录</router-link></div>

        <div v-show="isValidCode === false">
          <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleVerification">验 证</el-button>
        </div>
        <div v-show="isValidCode === true">
          <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleCreateUser">注 册</el-button>
        </div>
        <div class="tips">
          <p>1. 验证码将发送到输入的邮箱</p>
          <p>2. 密码请勿包含银行账号或支付软件密码</p>
          <p>3. 注册成功后，请使用手机号登录</p>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
// import { Message } from 'element-ui'
import { appCode } from '@/app_settings'
import { validEmail, validVerificationCode, validPhone, validPassword } from '@/utils/app/validate'
import { isPhoneExisting, requestCode, checkVerificationCode, createUser } from '@/api/app/signup'
import { getMailServerUrl, wait5sOpenUrl } from '@/utils/app/common'

export default {
  name: 'Signup',
  data() {
    const validateEmail = (rule, value, callback) => {
      if (!validEmail(value)) {
        callback(new Error('请输入有效的邮箱！'))
      } else {
        callback()
      }
    }
    const validateCode = (rule, value, callback) => {
      if (!validVerificationCode(value)) {
        callback(new Error('请输入5位验证码！'))
      } else {
        callback()
      }
    }
    const validateUserphone = (rule, value, callback) => {
      if (!validPhone(value)) {
        callback(new Error('请输入11位有效的手机号！'))
      } else {
        callback()
      }
    }
    const validatePssword = (rule, value, callback) => {
      if (!validPassword(value)) {
        callback(new Error('密码最小长度为8位，必须包含大写、小写字母、数字！'))
      } else {
        if (this.signupForm.confirmPassword !== '') {
          this.$refs.signupForm.validateField('confirmPassword')
        }
        callback()
      }
    }
    const validateconfirmPassword = (rule, value, callback) => {
      if (value !== this.signupForm.password) {
        callback(new Error('两次输入密码不一致!'))
      } else {
        callback()
      }
    }
    return {
      isValidCode: false,
      isBtnDisable: false,
      btnCodeConent: '获取验证码',
      timer60: '',
      countdown: 60,

      signupForm: {
        email: '',
        verificationCode: '',
        userphone: '',
        password: '',
        confirmPassword: ''
      },
      signupFormRules: {
        email: [{ required: true, trigger: 'change', validator: validateEmail }],
        verificationCode: [{ required: true, trigger: 'change', validator: validateCode }],
        userphone: [{ required: true, trigger: 'change', validator: validateUserphone }],
        password: [{ required: true, trigger: 'change', validator: validatePssword }],
        confirmPassword: [{ required: true, trigger: 'change', validator: validateconfirmPassword }]
      },
      loading: false,
      redirect: undefined
    }
  },
  computed: {
    emailLowcase: function() {
      return this.signupForm.email.toLowerCase()
    }
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect
      },
      immediate: true
    }
  },
  methods: {
    checkInfo(infoItem) {
      const userPhone = this.signupForm.userphone
      if (infoItem === 'phone') {
        if (validPhone(userPhone)) {
          isPhoneExisting(userPhone)
            .then(function(data) {
              if (data.code === appCode.USERS_SIGNUP_IDENTITY_EXISTING) {
                this.$refs.userphone.resetField()
                this.$message({
                  type: 'error',
                  showClose: true,
                  message: userPhone + data.msg,
                  duration: 3 * 1000
                })
              }
            }.bind(this))
            .catch(function(err) {
              console.log(err)
            })
        }
      }
    },

    // disable 获取验证码button
    diableRequestCodeBtn() {
      this.isBtnDisable = true
      this.btnCodeConent = '重新发送(' + this.countdown + ')'
      if (!this.timer60) {
        this.timer60 = setInterval(() => {
          if (this.countdown > 0 && this.countdown <= 60) {
            this.countdown--
            if (this.countdown !== 0) {
              this.btnCodeConent = '重新发送(' + this.countdown + ')'
            } else {
              clearInterval(this.timer60)
              this.isBtnDisable = false
              this.btnCodeConent = '获取验证码'
              this.countdown = 60
              this.timer60 = null
            }
          }
        }, 1000)
      }
    },

    handleRequestCode() {
      this.$refs.signupForm.validateField('email')
      const isFieldValid = this.$refs.email.validateMessage
      if (isFieldValid === '') {
        // disable 获取验证码button
        this.diableRequestCodeBtn()

        // 请求Server 发送验证码邮件
        const email = this.emailLowcase
        requestCode(email)
          .then(function(data) {
            if (data.code === appCode.SUCCESS) {
              const url = getMailServerUrl(this.emailLowcase)
              if (url) {
                this.$message({
                  type: 'info',
                  message: '验证码已发送，5秒后将在新窗口打开邮箱登录页面',
                  duration: 4 * 1000
                })
                //
                wait5sOpenUrl(url, 'blank')
              } else {
                this.$message({
                  type: 'info',
                  message: '验证码已发送，请用户登录邮箱查看验证码',
                  duration: 3 * 1000
                })
              }
            // } else if (data.code === appCode.USERS_SIGNUP_EMAIL_EXISTING) {
            //   this.$message({
            //     type: 'info',
            //     message: email + data.msg,
            //     duration: 3 * 1000
            //   })
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
              message: '发送验证码失败，请重新获取!!',
              duration: 3 * 1000
            })
          }.bind(this))
      }
    },

    handleVerification() {
      this.$refs.signupForm.validateField('email')
      this.$refs.signupForm.validateField('verificationCode')
      const isFieldValid = this.$refs.email.validateMessage + this.$refs.verificationCode.validateMessage
      if (isFieldValid === '') {
        const email = this.emailLowcase
        const code = this.signupForm.verificationCode

        checkVerificationCode(email, code)
          .then(function(data) {
            if (data.code === appCode.SUCCESS) {
              this.$message({
                type: 'info',
                message: '验证码正确',
                duration: 3 * 1000
              })
              this.isValidCode = true
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
              message: '验证码校验失败!!',
              duration: 3 * 1000
            })
          }.bind(this))
      }
    },

    handleCreateUser() {
      this.$refs.signupForm.validate((valid) => {
        if (valid) {
          const phone = this.signupForm.userphone
          const email = this.emailLowcase
          const password = this.signupForm.password
          this.$confirm('请确认手机号“' + phone + '”是否正确?', '提示', {
            confirmButtonText: '正确',
            cancelButtonText: '返回修改',
            type: 'warning'
          }).then(() => {
            createUser(phone, email, password)
              .then(function(data) {
                if (data.code === appCode.SUCCESS) {
                  this.$router.push({ name: 'login' })
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
              })
          }).catch(() => {
            this.$message({
              type: 'info',
              message: '已取消注册'
            })
          })
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">

/* reset element-ui css */
.signup-container {
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

.signup-wrapper {
  width: 100%;
  height: 100%;
  background-color:  $bg-gray-light;
}
.signup-container {
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

.btn-code {
  border: 0px;
  width: 90px;
  padding: 12px 4px;
  float: right;
  color: white;
  background-color:  $blue-light;
}

.svg-container {
  padding: 0px 8px 0px 16px;
  vertical-align: middle;
  display: inline-block;
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

.tips {
  font-size: 14px;
  color: $text-light-color;
  text-align: left;
}
</style>
