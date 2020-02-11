<template>
  <div class="login-wrapper">
    <div class="login-container mb-4">
      <div class="login-header py-responsive is-center">
        <router-link to="/home"><span><svg-logo logo-class="be_green" /></span></router-link>
      </div>
      <el-form ref="form" :model="formData" :rules="rules" class="container-sm px-responsive" label-position="left">
        <el-form-item prop="phone">
          <el-input ref="phone" v-model="formData.phone" type="text" tabindex="1" prefix-icon="el-icon-mobile-phone" placeholder="请输入手机号" clearable />
        </el-form-item>

        <el-form-item ref="password_item" prop="password">
          <el-input
            ref="password"
            v-model="formData.password"
            tabindex="2"
            prefix-icon="el-icon-lock"
            placeholder="请输入密码"
            show-password
            clearable
          />
        </el-form-item>

        <div style="display:inline-block; margin: 0px 0px 15px 0px; color: #409EFF; float:right;"><router-link to="/forgot-password">忘记密码？</router-link></div>
        <el-form-item>
          <el-button :loading="loading" type="primary" style="width:100%;" @click="handleLogin">登 录</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { validPhone } from '@/utils/app/validator/common'

export default {
  name: 'AuthLogin',
  data() {
    return {
      formData: {
        phone: '',
        password: ''
      },
      loading: false,
      redirect: undefined,
      rules: {
        phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        password: [{ required: true, message: '请输入密码', trigger: 'change' }]
      }
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
  mounted() {
    if (this.formData.phone === '') {
      this.$refs['phone'].focus()
    } else if (this.formData.password === '') {
      this.$refs['password'].focus()
    }
  },
  methods: {
    handleLogin() {
      this.$refs['form'].validate(valid => {
        if (valid) {
          this.loading = true
          this.$store.dispatch('Login', this.formData)
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
</style>
