<template>
  <div>
    <el-dialog :title="title" :visible.sync="visible2" :destroy-on-close="true" @close="handleClose">
      <el-form ref="update_form" :model="formData" :rules="update_form_rules" label-position="top" label-width="auto">
        <div v-if="propName === 'phone'">
          <el-form-item ref="phone" label="新的手机号" prop="phone">
            <el-input v-model="formData.phone" name="phone" type="text" tabindex="1" placeholder="输入新的手机号" clearable />
          </el-form-item>
        </div>

        <div v-if="propName === 'email'">
          <el-form-item ref="email" label="新的邮箱" prop="email">
            <el-input v-model="formData.email" name="email" type="text" tabindex="1" placeholder="输入新的邮箱" clearable />
          </el-form-item>
        </div>

        <el-form-item ref="code" label="验证码" prop="code">
          <el-input v-model="formData.code" name="code" type="text" tabindex="2" style="width:55%;float:left;" placeholder="输入验证码" clearable />
          <!-- <input v-model="btnReqCodeText" :disabled="isBtnDisable || isValidCode" type="button" class="btn-code" @click="handleRequestCode"> -->
          <el-button plain style="width:40%;float:right;" :disabled="isBtnDisable" @click.native.prevent="handleRequestCode">{{ btnReqCodeText }}</el-button>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" tabindex="3" style="width:100%;" @click.native.prevent="handlePostBtn">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import { validPhone, validEmail, validVerificationCode } from '@/utils/app/validator/common'

export default {
  name: 'AppChangeProp',
  props: {
    title: {
      type: String,
      default: () => { return '' }
    },
    propName: {
      type: String,
      default: () => { return 'phone' }
    },
    visible: {
      type: Boolean,
      default: () => { return false }
    }
  },
  data() {
    return {
      isValidCode: false,

      // 获取验证码按钮
      isBtnDisable: false,
      btnReqCodeText: '获取验证码',
      timer60: '',
      countdown: 60,

      formData: {
        phone: '',
        email: '',
        code: ''
      },
      update_form_rules: {
        phone: [{ required: true, trigger: 'change', validator: validPhone }],
        email: [{ required: true, trigger: 'change', validator: validEmail }],
        code: [{ required: true, trigger: 'change', validator: validVerificationCode }]
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

    handleRequestCode() {
      let isFieldValid = ''
      if (this.propName === 'phone') {
        this.$refs.update_form.validateField('phone')
        isFieldValid = this.$refs.phone.validateMessage
      } else if (this.propName === 'email') {
        this.$refs.update_form.validateField('email')
        isFieldValid = this.$refs.email.validateMessage
      }

      if (isFieldValid === '') {
        this.disableRequestCodeBtn()

        this.$emit('req_code')
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

    handlePostBtn() {
      let isFieldValid = ''
      if (this.propName === 'phone') {
        this.$refs.update_form.validateField('phone')
        isFieldValid = this.$refs.phone.validateMessage
      } else if (this.propName === 'email') {
        this.$refs.update_form.validateField('email')
        isFieldValid = this.$refs.email.validateMessage
      }
      this.$refs.update_form.validateField('code')
      isFieldValid = isFieldValid + this.$refs.code.validateMessage

      if (isFieldValid === '') {
        this.$emit('post', this.propName, this.formData)
      }
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
