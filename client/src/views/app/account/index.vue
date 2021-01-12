<template>
  <div class="app-container">
    <!-- <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab"> -->
    <el-tabs v-model="tabIndex" tab-position="left">
      <el-tab-pane name="basic_info_tab" label="基本信息">
        <div class="pages-account-setting-title">基本信息</div>

        <el-row :gutter="8">
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
                <el-input v-model="formData.username" class="w-30" clearable />
              </el-form-item>
              <el-form-item label="性别" prop="sex">
                <el-radio-group v-model="formData.sex" class="w-30">
                  <el-radio label="0">男</el-radio>
                  <el-radio label="1">女</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="身份证号" prop="identity_document_number">
                <el-input v-model="formData.identity_document_number" class="w-30" clearable />
              </el-form-item>

              <el-form-item label="政治面貌" prop="attr_03_id">
                <el-select v-model="formData.attr_03_id" class="w-30" placeholder="请选择">
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
                  class="w-30"
                  :placeholder="'请选择'"
                />
              </el-form-item>

              <el-form-item label="岗位" prop="attr_02_id">
                <el-select v-model="formData.attr_02_id" class="w-30" placeholder="请选择">
                  <el-option
                    v-for="item in job_list"
                    :key="item.id"
                    :label="item.label"
                    :value="item.id"
                  />
                </el-select>
              </el-form-item>

              <el-form-item label="职称" prop="attr_04_id">
                <el-select v-model="formData.attr_04_id" class="w-30" placeholder="请选择">
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
      </el-tab-pane>

      <el-tab-pane name="tab_two" label="安全设置">
        <div class="pages-account-setting-title">安全设置</div>
        <table class="w-30">
          <tbody>
            <tr>
              <td>
                <ul>
                  <li><span class="pages-account-setting-text-dark">绑定手机</span></li>
                  <li><span class="pages-account-setting-text-light">{{ tableData.phone }}</span></li>
                </ul>
              </td>
              <td class="td-button"><el-button type="text" @click="handleReqUpdatePhone()">修改</el-button></td>
            </tr>
            <tr>
              <td>
                <ul>
                  <li><span class="pages-account-setting-text-dark">绑定邮箱</span></li>
                  <li><span class="pages-account-setting-text-light">{{ tableData.email }}</span></li>
                </ul>
              </td>
              <td class="td-button"><el-button type="text" @click="handleReqUpdateEmail()">修改</el-button></td>
            </tr>
          </tbody>
        </table>
        <div class="pages-account-setting-text">
          <p>目前只支持通过已绑定邮箱验证成功后，修改手机号或邮箱!</p>
        </div>

        <!-- 修改手机号 -->
        <el-card v-show="isVisibleUpdatePhone" class="mt-2 w-40">
          <div slot="header">
            <span>修改绑定手机</span>
            <el-button style="float: right; padding: 3px 0" type="text" @click="handleCloseUpdatePhoneCard()">关闭</el-button>
          </div>
          <el-form ref="update_phone_form" :model="phoneForm" :rules="update_phone_form_rules" label-position="top" label-width="auto">
            <el-form-item label="新的手机号" prop="phone">
              <el-input v-model="phoneForm.phone" name="phone" type="text" tabindex="1" placeholder="输入新的手机号" clearable />
            </el-form-item>

            <el-form-item label="验证码" prop="verificationCode">
              <el-input v-model="phoneForm.verification_code" type="text" style="width:55%;float:left;" class="code-field" name="verificationCode" placeholder="输入验证码" clearable />
              <!-- <input v-model="btnReqCodeText" :disabled="isBtnDisable || isValidCode" type="button" class="btn-code" @click="handleRequestCode"> -->
              <el-button plain style="width:40%;float:right;" @click.native.prevent="handleClickBtn">获取验证码</el-button>
            </el-form-item>

            <el-form-item>
              <el-button type="primary" style="width:100%;" @click.native.prevent="handleClickBtn">提交</el-button>
            </el-form-item>
          </el-form>
        </el-card>

        <!-- 修改Email -->
        <el-card v-show="isVisibleUpdateEmail" class="mt-2 w-40">
          <div slot="header">
            <span>修改绑定Email</span>
            <el-button style="float: right; padding: 3px 0" type="text" @click="handleCloseUpdateEmailCard()">关闭</el-button>
          </div>
          <el-form ref="update_email_form" :model="emailForm" :rules="update_email_form_rules" label-position="top" label-width="auto">
            <el-form-item label="新的Email" prop="email">
              <el-input v-model="emailForm.email" name="email" type="text" tabindex="1" placeholder="输入新的Email" clearable />
            </el-form-item>

            <el-form-item label="验证码" prop="verificationCode">
              <el-input v-model="emailForm.verification_code" type="text" style="width:55%;float:left;" class="code-field" name="verificationCode" placeholder="输入验证码" clearable />
              <!-- <input v-model="btnReqCodeText" :disabled="isBtnDisable || isValidCode" type="button" class="btn-code" @click="handleRequestCode"> -->
              <el-button plain style="width:40%;float:right;" @click.native.prevent="handleClickBtn">获取验证码</el-button>
            </el-form-item>

            <el-form-item>
              <el-button type="primary" style="width:100%;" @click.native.prevent="handleClickBtn">提交</el-button>
            </el-form-item>
          </el-form>
        </el-card>

      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import * as myApp from '@/app_settings'
import * as utils from '@/utils/app/common'
import { mapGetters } from 'vuex'
import TreeSelect from '@/components/app/TreeSelect/index'
import AppAvatar from './avatar/index'
// import UserCard from './components/UserCard'
// import Activity from './components/Activity'
// import Timeline from './components/Timeline'
// import Account from './components/Account'
import { apiGetBasicInfoFormListContent } from '@/api/app/account/index'

export default {
  name: 'AccountSetting',
  // components: { UserCard, Activity, Timeline, Account },
  components: {
    'tree-select': TreeSelect,
    'app-avatar': AppAvatar
  },
  data() {
    return {
      avatarUploadApi: process.env.VUE_APP_BASE_API + myApp.config.AVATAR_UPLOAD_API,

      tabIndex: 'basic_info_tab',
      isUpdateBasicInfoBtnDisable: true,

      isVisibleUpdatePhone: false,
      isVisibleUpdateEmail: false,

      // 表单数据
      formData: {
        // username: '',
        // sex: '0',
        // identity_document_number: '',
        // attr_01_id: '',
        // attr_02_id: '',
        // attr_03_id: '',
        // attr_04_id: ''
      },

      phoneForm: {
        phone: '',
        verification_code: ''
      },

      emailForm: {
        email: '',
        verification_code: ''
      },

      tableData: {
        phone: '13812345678',
        email: '1@1.1'
      },

      // form 下拉列表元素
      dept_list: [],
      job_list: [],
      politic_list: [],
      professional_title_list: [],

      basic_info_form_rules: {
        // sort: [{ required: true, validator: validSort, trigger: 'change' }],
        // username: [{ required: true, validator: validChineseLetter, trigger: 'change' }],
        // phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        // email: [{ required: true, validator: validEmail, trigger: 'change' }]
      },

      update_phone_form_rules: {
        // sort: [{ required: true, validator: validSort, trigger: 'change' }],
        // username: [{ required: true, validator: validChineseLetter, trigger: 'change' }],
        // phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        // email: [{ required: true, validator: validEmail, trigger: 'change' }]
      },

      update_email_form_rules: {

      }
    }
  },
  computed: {
    ...mapGetters([
      'user'
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
    /**
     * @description: 页面加载时，请求form表单各个list的下拉集合。请求数据成功，取消更新按钮的禁用状态，各个表单项赋值。
     */
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

    /**
     * @description: 请求修改用户基本信息
     */
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

    /**
     * @Description: 用户头像更新成功响应
     * @Author: freeair
     * @Date: 2020-11-14 10:08:30
     * @param {*}
     * @return {*}
     */
    onAvatarUploadSuccess(res) {
      this.$store.dispatch('account/updateUserAvatar', res.avatar)
    },

    /**
     * @Description: 请求修改手机号
     * @Author: freeair
     * @Date: 2020-11-17 11:35:39
     * @param {*}
     * @return {*}
     */
    handleReqUpdatePhone() {
      this.isVisibleUpdatePhone = true
      // apiGetVerificationCode()
      //   .then(function(data) {
      //     this.isVisibleUpdatePhone = true
      //   }.bind(this))
      //   .catch(function(err) {
      //     this.isVisibleUpdatePhone = false
      //     this.$message({
      //       message: err,
      //       type: 'warning'
      //     })
      //   }.bind(this))
    },

    handleCloseUpdatePhoneCard() {
      this.isVisibleUpdatePhone = false
    },

    /**
     * @Description: 请求修改Email
     * @Author: freeair
     * @Date: 2020-11-17 11:36:08
     * @param {*}
     * @return {*}
     */
    handleReqUpdateEmail() {
      this.isVisibleUpdateEmail = true
    },

    handleCloseUpdateEmailCard() {
      this.isVisibleUpdateEmail = false
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
