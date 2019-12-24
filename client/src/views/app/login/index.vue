<template>
  <div class="login-wrapper">
    <div class="login-container mb-4">
      <div class="login-header py-responsive is-center">
        <router-link to="/home"><span><svg-logo logo-class="be_green" /></span></router-link>
        <h2 class="title">登录</h2>
      </div>
      <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="container-sm px-responsive" label-position="left">
        <el-form-item prop="userphone">
          <span class="svg-container">
            <svg-icon icon-class="icon_mobilephone" />
          </span>
          <el-input v-model="loginForm.userphone" name="userphone" type="text" placeholder="请用手机号登录" clearable />
        </el-form-item>
        <el-form-item ref="password_item" prop="password">
          <span class="svg-container">
            <svg-icon icon-class="password" />
          </span>
          <el-input v-model="loginForm.password" name="password" placeholder="请输入密码" show-password clearable />
        </el-form-item>
        <div style="display:inline-block; margin: 0px 0px 15px 0px; color: #409EFF;"><router-link to="/forgot_password">忘记密码？</router-link></div>
        <div style="display:inline-block; margin: 0px 0px 15px 0px; color: #409EFF; float:right;"><router-link to="/signup">没有账号？去注册</router-link></div>
        <!-- <div style="margin: 0px 0px 15px 0px; color: #409EFF;text-align:right"><router-link to="/active_mail">激活账号</router-link></div> -->
        <el-form-item>
          <el-button :loading="loading" type="primary" style="width:100%;" @click.native.prevent="handleLogin">登 录</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { validPhone } from '@/utils/app/validate'

export default {
  name: 'Login',
  data() {
    const validateUserphone = (rule, value, callback) => {
      if (!validPhone(value)) {
        callback(new Error('请输入11位有效的手机号！'))
      } else {
        callback()
      }
    }
    return {
      loginForm: {
        userphone: '',
        password: ''
      },
      loginRules: {
        userphone: [{ required: true, trigger: 'change', validator: validateUserphone }],
        password: [{ required: true, message: '请输入密码', trigger: 'change' }]
      },
      loading: false,
      redirect: undefined
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
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true
          this.$store.dispatch('Login', this.loginForm)
            .then((user_detailed_done) => {
              this.loading = false
              if (user_detailed_done === '0') {
                this.$message({
                  type: 'warning',
                  message: '继续使用前，请完善用户个人信息！',
                  duration: 3 * 1000
                })
                // 路由切换，replace
                this.$router.replace({ path: '/user_settings' })
              } else {
                this.$router.replace({ path: this.redirect || '/' })
              }
            })
            .catch((error) => {
              this.loading = false
              this.$refs.password_item.resetField()
              this.$message({
                type: 'info',
                message: error,
                duration: 3 * 1000
              })
            })
        } else {
          return false
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    width: 85%;
    input {
      background: transparent;
      border: 0px;
      border-radius: 0px;
      // padding: 12px 5px 12px 15px;
      // color: $dark_gray;
      // height: 47px;
      // &:-webkit-autofill {
      //   -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
      //   -webkit-text-fill-color: #fff !important;
      // }
    }
  }
  .el-form-item {
    background: transparent;
    border: 1px solid $border_gray;
    border-radius: 5px;
  }
}

</style>

<style rel="stylesheet/scss" lang="scss" scoped>

.login-wrapper {
  width: 100%;
  height: 100%;
  background-color:  $bg-gray-light;
}
.login-container {
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
</style>
