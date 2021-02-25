<template>
  <div class="app-container ">
    <div v-if="isDesktop" class="pt-2">
      <!-- <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab"> -->
      <el-tabs v-model="tabIndex" tab-position="left">
        <el-tab-pane name="basic_info_tab" label="基本信息">
          <div class="px-5">
            <div class="pages-account-setting-title">基本信息</div>

            <el-row :gutter="16">
              <el-col :xs="24" :sm="24" :md="4" :lg="4" :xl="4">
                <app-avatar :avatar-url="avatarUrl" :upload-api="avatarUploadApi" @upload-success="onAvatarUploadSuccess" />
                <div class="pages-account-setting-text">
                  <p>工号: {{ user.sort }}</p>
                  <p>手机: {{ user.phone }}</p>
                  <p>邮箱: {{ user.email }}</p>
                </div>
              </el-col>

              <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
                <el-form ref="basic_info_form" :model="formData" :rules="form_rules" label-position="top" label-width="auto">
                  <el-form-item label="中文名" prop="username" class="w-40">
                    <el-input v-model="formData.username" clearable />
                  </el-form-item>
                  <el-form-item label="性别" prop="sex" class="w-40">
                    <el-radio-group v-model="formData.sex">
                      <el-radio label="0">男</el-radio>
                      <el-radio label="1">女</el-radio>
                    </el-radio-group>
                  </el-form-item>
                  <el-form-item label="身份证号" prop="identity_document_number" class="w-40">
                    <el-input v-model="formData.identity_document_number" clearable />
                  </el-form-item>

                  <el-form-item label="政治面貌" prop="attr_03_id">
                    <el-select v-model="formData.attr_03_id" placeholder="请选择" class="w-40">
                      <el-option
                        v-for="item in politic_list"
                        :key="item.id"
                        :label="item.label"
                        :value="item.id"
                      />
                    </el-select>
                  </el-form-item>

                  <el-form-item label="部门" prop="attr_01_id">
                    <el-cascader
                      v-model="formData.attr_01_id"
                      :options="dept_list"
                      :props="{ value: 'id', emitPath: false }"
                      :show-all-levels="true"
                      :placeholder="'请选择'"
                      clearable
                      class="w-40"
                    />
                  </el-form-item>

                  <el-form-item label="岗位" prop="attr_02_id">
                    <el-select v-model="formData.attr_02_id" placeholder="请选择" class="w-40">
                      <el-option
                        v-for="item in job_list"
                        :key="item.id"
                        :label="item.label"
                        :value="item.id"
                      />
                    </el-select>
                  </el-form-item>

                  <el-form-item label="职称" prop="attr_04_id">
                    <el-select v-model="formData.attr_04_id" placeholder="请选择" class="w-40">
                      <el-option
                        v-for="item in professional_title_list"
                        :key="item.id"
                        :label="item.label"
                        :value="item.id"
                      />
                    </el-select>
                  </el-form-item>
                </el-form>

                <el-button type="primary" :disabled="isUpdateBasicInfoBtnDisable" @click="handleUpdateUserBasicInfo()">更新基本信息</el-button>
              </el-col>
            </el-row>
          </div>
        </el-tab-pane>

        <el-tab-pane name="tab_two" label="安全设置">
          <div class="px-5">
            <div class="pages-account-setting-title">安全设置</div>
            <table class="w-40">
              <tbody>
                <tr>
                  <td>
                    <ul>
                      <li><span class="pages-account-setting-text-dark">绑定手机</span></li>
                      <li><span class="pages-account-setting-text-light">{{ user.phone }}</span></li>
                    </ul>
                  </td>
                  <td class="td-button"><el-button type="text" @click="handleReqChangePhone()">修改</el-button></td>
                </tr>
                <tr>
                  <td>
                    <ul>
                      <li><span class="pages-account-setting-text-dark">绑定邮箱</span></li>
                      <li><span class="pages-account-setting-text-light">{{ user.email }}</span></li>
                    </ul>
                  </td>
                  <td class="td-button"><el-button type="text" @click="handleReqChangeEmail()">修改</el-button></td>
                </tr>
                <tr>
                  <td>
                    <ul>
                      <li><span class="pages-account-setting-text-dark">账号密码</span></li>
                      <li><span class="pages-account-setting-text-light">********</span></li>
                    </ul>
                  </td>
                  <td class="td-button"><el-button type="text" @click="handleReqChangePwd()">修改</el-button></td>
                </tr>
              </tbody>
            </table>
            <div class="pages-account-setting-text w-40">
              <p>修改绑定手机号或邮箱，需通过当前绑定的邮箱验证!</p>
            </div>

            <!-- 修改手机号 -->
            <app-change-prop title="修改手机号" prop-name="phone" :visible.sync="isVisibleChangePhone" @req_code="onReqCode" @post="onPostSecuritySetting" />

            <!-- 修改Email -->
            <app-change-prop title="修改邮箱" prop-name="email" :visible.sync="isVisibleChangeEmail" @req_code="onReqCode" @post="onPostSecuritySetting" />

            <!-- 修改密码 -->
            <app-change-pwd :visible.sync="isVisibleChangePwd" @post="onPostChangePwd" />
          </div>
        </el-tab-pane>
      </el-tabs>
    </div>

    <div v-else>
      <van-tabs v-model="tabIndex">
        <van-tab title="基本信息" name="basic_info_tab">
          <div class="py-2" style="text-align: center;">
            <app-avatar :avatar-url="avatarUrl" :upload-api="avatarUploadApi" @upload-success="onAvatarUploadSuccess" />
          </div>

          <van-field :value="user.sort" name="sort" label="工号" readonly @click="clickSortField" />
          <van-field :value="user.phone" name="phone" label="手机" readonly @click="clickPhoneField" />
          <van-field :value="user.email" name="email" label="邮箱" readonly @click="clickEmailField" />

          <van-form ref="vant_user_form">
            <van-field v-model="formData.username" name="username" label="中文名" :rules="form_rules.username" />

            <van-field name="sex" label="性别">
              <template #input>
                <van-radio-group v-model="formData.sex" direction="horizontal">
                  <van-radio name="0">男</van-radio>
                  <van-radio name="1">女</van-radio>
                </van-radio-group>
              </template>
            </van-field>

            <van-field v-model="formData.identity_document_number" name="identity_document_number" label="身份证号" :rules="form_rules.identity_document_number" />

            <app-vant-select-pop label="政治面貌" :value.sync="formData.attr_03_id" :list="politic_list" />

            <app-vant-tree-select-pop label="部门" :value.sync="formData.attr_01_id" :options="dept_list" />

            <app-vant-select-pop label="岗位" :value.sync="formData.attr_02_id" :list="job_list" />

            <app-vant-select-pop label="职称" :value.sync="formData.attr_04_id" :list="professional_title_list" />

            <div style="margin: 16px;">
              <van-button block type="info" native-type="button" :disabled="isUpdateBasicInfoBtnDisable" @click="handleUpdateUserBasicInfo2()">更新基本信息</van-button>
            </div>
          </van-form>
        </van-tab>

        <van-tab title="安全设置" name="tab_two">
          <div class="py-2">
            <van-field :value="user.phone" name="phone" label="绑定手机" readonly @click="handleReqChangePhone()" />
            <van-field :value="user.email" name="email" label="绑定邮箱" readonly @click="handleReqChangeEmail()" />
            <van-field value="********" name="password" label="账号密码" readonly @click="handleReqChangePwd()" />

            <!-- 修改手机号 -->
            <app-change-prop title="修改手机号" prop-name="phone" width="90%" :visible.sync="isVisibleChangePhone" @req_code="onReqCode" @post="onPostSecuritySetting" />

            <!-- 修改Email -->
            <app-change-prop title="修改邮箱" prop-name="email" width="90%" :visible.sync="isVisibleChangeEmail" @req_code="onReqCode" @post="onPostSecuritySetting" />

            <!-- 修改密码 -->
            <app-change-pwd width="90%" :visible.sync="isVisibleChangePwd" @post="onPostChangePwd" />
          </div>

        </van-tab>
      </van-tabs>
    </div>
  </div>
</template>

<script>
// 组件
import AppAvatar from './components/avatar'
import AppChangeProp from './components/AppChangeProp'
import AppChangePwd from './components/AppChangePwd'
import AppVantSelectPop from '@/components/app/vant/AppVantSelectPop'
import AppVantTreeSelectPop from '@/components/app/vant/AppVantTreeSelectPop'
//
import * as myApp from '@/app_settings'
import * as utils from '@/utils/app/common'
import { mapGetters } from 'vuex'
// API
import { apiGetBasicInfoFormListContent, apiReqVerificationCode, apiPostPassword } from '@/api/app/account/index'
// Validate
import * as validator from '@/utils/app/validator/common'

export default {
  name: 'AccountSetting',
  components: {
    'app-avatar': AppAvatar,
    'app-change-prop': AppChangeProp,
    'app-change-pwd': AppChangePwd,
    'app-vant-select-pop': AppVantSelectPop,
    'app-vant-tree-select-pop': AppVantTreeSelectPop
  },
  data() {
    return {
      avatarUploadApi: process.env.VUE_APP_BASE_API + myApp.config.AVATAR_UPLOAD_API,

      tabIndex: 'basic_info_tab',
      isUpdateBasicInfoBtnDisable: true,

      // 表单数据
      formData: {
      },

      // form 下拉列表元素
      dept_list: [],
      job_list: [],
      politic_list: [],
      professional_title_list: [],

      form_rules: {
        username: [{ required: true, pattern: validator.chineseLetter.regex, message: validator.chineseLetter.msg }],
        identity_document_number: [{ pattern: validator.idNumber.regex, message: validator.idNumber.msg }]
      },

      isVisibleChangePhone: false,
      isVisibleChangeEmail: false,
      isVisibleChangePwd: false,

      // vant
      showPickerPolitical: false,
      politic_list2: []
    }
  },
  computed: {
    ...mapGetters([
      'user',
      'isDesktop'
    ]),
    avatarUrl: function() {
      // avatarUrl: process.env.VUE_APP_BASE_API + '/path/avatar.jpg'
      if (this.user === null) {
        return ''
      } else {
        return process.env.VUE_APP_BASE_API + '/' + this.user.avatar
      }
    }
  },
  mounted() {
    this.getBasicInfoFormListContent()
  },
  methods: {

    // 页面加载时，请求form表单各个list的下拉集合。请求数据成功，取消更新按钮的禁用状态，各个表单项赋值。
    getBasicInfoFormListContent() {
      apiGetBasicInfoFormListContent()
        .then(function(data) {
          this.copyListContent(data)
          this.isUpdateBasicInfoBtnDisable = false
          this.$nextTick(() => {
            this.formData = utils.merge(this.user)
          })
        }.bind(this))
        .catch(function(err) {
          this.isUpdateBasicInfoBtnDisable = true
          this.$message({
            message: err,
            type: 'warning'
          })
        }.bind(this))
    },

    //
    copyListContent(data) {
      this.dept_list = data.dept_list.slice(0)
      this.job_list = data.job_list.slice(0)
      this.politic_list = data.politic_list.slice(0)
      this.professional_title_list = data.professional_title_list.slice(0)
    },

    // 请求修改用户基本信息
    handleUpdateUserBasicInfo() {
      this.$refs['basic_info_form'].validate((valid) => {
        if (valid) {
          this.$store.dispatch('account/updateUserBasicInfo', this.formData)
            .then(() => {
              this.$nextTick(() => {
                this.formData = utils.merge(this.user)
              })
            })
            .catch((error) => {
              this.$message({
                type: 'warning',
                message: error
              })
            })
        }
      })
    },

    handleUpdateUserBasicInfo2() {
      this.$refs['vant_user_form'].validate()
        .then(() => {
          this.$store.dispatch('account/updateUserBasicInfo', this.formData)
            .then(() => {
              this.$nextTick(() => {
                this.formData = utils.merge(this.user)
              })
            })
            .catch((error) => {
              this.$message({
                type: 'warning',
                message: error
              })
            })
        })
    },

    // 用户头像更新成功响应
    onAvatarUploadSuccess(res) {
      this.$store.dispatch('account/updateUserAvatar', res.avatar)
    },

    // 请求修改手机号
    handleReqChangePhone() {
      this.isVisibleChangePhone = true
    },
    // 请求修改邮箱
    handleReqChangeEmail() {
      this.isVisibleChangeEmail = true
    },
    // 请求修改密码
    handleReqChangePwd() {
      this.isVisibleChangePwd = true
    },

    // 请求验证码
    onReqCode() {
      apiReqVerificationCode()
        .then(function(data) {
          if (data.email) {
            this.$message({
              type: 'success',
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
    },

    // 提交更改安全设置
    onPostSecuritySetting(propName, data) {
      const securityData = {
        prop: propName,
        phone: data.phone,
        email: data.email,
        code: data.code
      }

      this.$store.dispatch('account/updateUserSecuritySetting', securityData)
        .then((res) => {
          if (res === '') {
            this.isVisibleChangePhone = false
            this.isVisibleChangeEmail = false
            this.$nextTick(() => {
              this.formData = utils.merge(this.user)
            })
          } else if (res === 'logout') {
            this.logout()
          }
        })
        .catch((error) => {
          this.$message({
            type: 'error',
            message: error,
            duration: 3 * 1000
          })
        })
    },

    // 提交新密码
    onPostChangePwd(password) {
      const data = {
        password: password
      }
      apiPostPassword(data)
        .then(function() {
          this.isVisibleChangePwd = false
        }.bind(this))
        .catch(function(error) {
          this.$message({
            type: 'error',
            message: error,
            duration: 3 * 1000
          })
        }.bind(this))
    },

    async logout() {
      await this.$store.dispatch('account/logout')
      this.$router.push(`/login`)
    },

    clickSortField() {
      this.$toast('不允许修改！')
    },
    clickPhoneField() {
      this.$toast('请在“安全设置”修改手机号码！')
    },
    clickEmailField() {
      this.$toast('请在“安全设置”修改邮箱！')
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.pages-account-setting-title {
  margin-bottom: 12px;
  color: $title-color;
  font-weight: 500;
  font-size: 20px;
  line-height: 28px;
}

.pages-account-setting-text {
  font-size: $font-size-base;
  color: $text-color;
}

.pages-account-setting-text-dark {
  color: $text-dark-color;
}

.pages-account-setting-text-light {
  color: $text-light-color;
}

table {
  border-spacing: 0;  /*去掉单元格间隙*/
}

td {
  border-bottom:1px solid $border-gray-dark;
  padding:8px;
  font-size:$font-size-base;
}

.td-button {
  text-align: right;
}

ul {
    list-style-type: none;
    padding: 0px;
    margin: 0px;
}

li {
    padding-top: 4px;
}

</style>
