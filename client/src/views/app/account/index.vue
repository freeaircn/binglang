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
                <el-form ref="basic_info_form" :model="formData" :rules="basic_info_form_rules" label-position="top" label-width="auto">
                  <el-form-item label="中文名" prop="username">
                    <el-input v-model="formData.username" class="w-40" clearable />
                  </el-form-item>
                  <el-form-item label="性别" prop="sex">
                    <el-radio-group v-model="formData.sex" class="w-40">
                      <el-radio label="0">男</el-radio>
                      <el-radio label="1">女</el-radio>
                    </el-radio-group>
                  </el-form-item>
                  <el-form-item label="身份证号" prop="identity_document_number">
                    <el-input v-model="formData.identity_document_number" class="w-40" clearable />
                  </el-form-item>

                  <el-form-item label="政治面貌" prop="attr_03_id">
                    <el-select v-model="formData.attr_03_id" class="w-40" placeholder="请选择">
                      <el-option
                        v-for="item in politic_list"
                        :key="item.id"
                        :label="item.label"
                        :value="item.id"
                      />
                    </el-select>
                  </el-form-item>

                  <el-form-item label="部门" prop="attr_01_id">
                    <tree-select
                      :value.sync="formData.attr_01_id"
                      :options="dept_list"
                      class="w-40"
                      :placeholder="'请选择'"
                    />
                  </el-form-item>

                  <el-form-item label="岗位" prop="attr_02_id">
                    <el-select v-model="formData.attr_02_id" class="w-40" placeholder="请选择">
                      <el-option
                        v-for="item in job_list"
                        :key="item.id"
                        :label="item.label"
                        :value="item.id"
                      />
                    </el-select>
                  </el-form-item>

                  <el-form-item label="职称" prop="attr_04_id">
                    <el-select v-model="formData.attr_04_id" class="w-40" placeholder="请选择">
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
                  <td class="td-button"><el-button type="text" @click="handleReqUpdatePhone()">修改</el-button></td>
                </tr>
                <tr>
                  <td>
                    <ul>
                      <li><span class="pages-account-setting-text-dark">绑定邮箱</span></li>
                      <li><span class="pages-account-setting-text-light">{{ user.email }}</span></li>
                    </ul>
                  </td>
                  <td class="td-button"><el-button type="text" @click="handleReqUpdateEmail()">修改</el-button></td>
                </tr>
              </tbody>
            </table>
            <div class="pages-account-setting-text w-40">
              <p>修改绑定手机号或邮箱，需通过当前绑定的邮箱验证!</p>
            </div>

            <!-- 修改手机号 -->
            <app-req-update-prop v-if="isVisibleUpdatePhone" update-prop="phone" card-text="手机" @close="onCloseUpdateCard" @req_code="onReqCode" @post="onPostSecuritySetting" />

            <!-- 修改Email -->
            <app-req-update-prop v-if="isVisibleUpdateEmail" update-prop="email" card-text="邮箱" @close="onCloseUpdateCard" @req_code="onReqCode" @post="onPostSecuritySetting" />

          </div>
        </el-tab-pane>
      </el-tabs>
    </div>

    <div v-else>
      <van-cell-group>
        <van-field v-model="formData.username" label="文本" placeholder="请输入用户名" />
      </van-cell-group>
    </div>
  </div>
</template>

<script>
// 组件
import TreeSelect from '@/components/app/TreeSelect/index'
import AppAvatar from './components/avatar'
import AppReqUpdateProp from './components/AppReqUpdateProp'
//
import * as myApp from '@/app_settings'
import * as utils from '@/utils/app/common'
import { mapGetters } from 'vuex'
// API
import { apiGetBasicInfoFormListContent, apiReqVerificationCode } from '@/api/app/account/index'

export default {
  name: 'AccountSetting',
  components: {
    'tree-select': TreeSelect,
    'app-avatar': AppAvatar,
    'app-req-update-prop': AppReqUpdateProp
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

      basic_info_form_rules: {
        // username: [{ required: true, validator: validChineseLetter, trigger: 'change' }]
      },

      isVisibleUpdatePhone: false,
      isVisibleUpdateEmail: false
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
        return process.env.VUE_APP_BASE_API + this.user.avatar.path + this.user.avatar.name
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

    // 用户头像更新成功响应
    onAvatarUploadSuccess(res) {
      this.$store.dispatch('account/updateUserAvatar', res.avatar)
    },

    // 请求更改安全设置
    handleReqUpdatePhone() {
      this.isVisibleUpdateEmail = false
      this.$nextTick(() => {
        this.isVisibleUpdatePhone = true
      })
    },

    handleReqUpdateEmail() {
      this.isVisibleUpdatePhone = false
      this.$nextTick(() => {
        this.isVisibleUpdateEmail = true
      })
    },

    onCloseUpdateCard(updateProp) {
      if (updateProp === 'phone') {
        this.isVisibleUpdatePhone = false
      } else if (updateProp === 'email') {
        this.isVisibleUpdateEmail = false
      }
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
    onPostSecuritySetting(updateProp, data) {
      const securityData = {
        prop: updateProp,
        new_phone: data.phone,
        new_email: data.email,
        code: data.code
      }

      this.$store.dispatch('account/updateUserSecuritySetting', securityData)
        .then((res) => {
          if (res === '') {
            this.isVisibleUpdatePhone = false
            this.isVisibleUpdateEmail = false
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
            message: error
          })
        })

      // apiPostSecuritySetting(reqData)
      //   .then(function(data) {
      //     if (typeof data !== 'undefined') {
      //       if (typeof data.cmd !== 'undefined') {
      //         if (data.cmd === 'logout') {
      //           this.logout()
      //         }
      //       }
      //     }
      //   }.bind(this))
      //   .catch(function(err) {
      //     this.$message({
      //       type: 'error',
      //       message: err,
      //       duration: 3 * 1000
      //     })
      //   }.bind(this))
    },

    async logout() {
      await this.$store.dispatch('account/logout')
      this.$router.push(`/login`)
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
