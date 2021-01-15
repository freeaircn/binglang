<template>
  <div class="wrapper">
    <div class="container container-responsive mb-4">
      <div class="login-header py-3 is-center">
        <router-link to="/home"><span><svg-logo logo-class="be_green" /></span></router-link>
      </div>
      <el-form ref="form" :model="formData" :rules="rules" label-position="left">
        <el-form-item prop="phone">
          <el-input ref="phone" v-model="formData.phone" type="text" tabindex="1" prefix-icon="el-icon-mobile-phone" placeholder="请输入手机号" clearable />
        </el-form-item>

        <el-form-item prop="password">
          <el-input
            ref="password"
            v-model="formData.password"
            tabindex="2"
            prefix-icon="el-icon-lock"
            placeholder="请输入密码"
            show-password
            clearable
            @keyup.enter.native="handleLogin"
          />
        </el-form-item>

        <div style="margin: 0px 0px 15px 0px; color: #409EFF; "><router-link to="/find_password">忘记密码？</router-link></div>
        <!-- <div style="display:inline-block; margin: 0px 0px 15px 0px; color: #409EFF; float:right;"><router-link to="/find_password">忘记密码？</router-link></div> -->
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
        password: '',
        remember: false
      },
      loading: false,
      redirect: undefined,
      rules: {
        phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        password: [{ required: true, message: '请输入密码', trigger: 'change' }]
      },
      treeExpandedKeys: [],
      value: undefined
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
  beforeMount() {
    this.$store.dispatch('account/clearStoreUser')
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
          this.$store.dispatch('account/login', this.formData)
            .then(() => {
              this.loading = false
              this.$router.replace({ path: this.redirect || '/' })
                .catch((err) => {
                  if (typeof err !== 'undefined') {
                    console.log('Navigate err: ')
                    console.log(err)
                  }
                })
            })
            .catch((error) => {
              this.loading = false
              this.$message({
                type: 'warning',
                message: error
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
