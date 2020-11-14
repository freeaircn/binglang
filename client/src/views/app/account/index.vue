<template>
  <div class="app-container">
    <!-- <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab"> -->
    <el-tabs v-model="tabIndex" tab-position="left">
      <el-tab-pane name="account_basic_info_tab" label="基本信息">
        <div class="pages-account-settings-title">基本信息</div>

        <el-row :gutter="8">
          <el-col :xs="24" :sm="24" :md="4" :lg="4" :xl="4">
            <app-avatar :avatar-url="avatarUrl" :upload-api="avatarUploadApi" @upload-success="onAvatarUploadSuccess" />

            <div class="pages-account-settings-text">
              <p>工号: {{ user.sort }}</p>
              <p>手机: {{ user.phone }}</p>
              <p>邮箱: {{ user.email }}</p>
            </div>

          </el-col>
          <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
            <el-form ref="account_basic_info_form" :model="formData" :rules="account_basic_info_form_rules" size="mini" label-position="top" label-width="auto">

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

            <el-button type="primary" size="mini" :disabled="isUpdateBtnDisable" @click="handleUpdateUserBasicInfo()">更新基本信息</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>

      <el-tab-pane name="tab_two" label="安全设置" />
    </el-tabs>
  </div>
</template>

<script>
import { appConfig } from '@/app_settings'
import { mapGetters } from 'vuex'
import TreeSelect from '@/components/app/TreeSelect/index'
import AppAvatar from './avatar/index'
// import UserCard from './components/UserCard'
// import Activity from './components/Activity'
// import Timeline from './components/Timeline'
// import Account from './components/Account'
import { apiGetBasicList } from '@/api/app/account/index'

export default {
  name: 'AccountSettings',
  // components: { UserCard, Activity, Timeline, Account },
  components: {
    'tree-select': TreeSelect,
    'app-avatar': AppAvatar
  },
  data() {
    return {
      avatarUploadApi: process.env.VUE_APP_BASE_API + appConfig.AVATAR_UPLOAD_API,

      tabIndex: 'account_basic_info_tab',
      isUpdateBtnDisable: true,

      // 表单数据
      formData: {
        username: '',
        sex: '0',
        // phone: '',
        identity_document_number: '',
        attr_01_id: '',
        attr_02_id: '',
        attr_03_id: '',
        attr_04_id: ''
      },

      // form 下拉列表元素
      dept_list: [],
      job_list: [],
      politic_list: [],
      professional_title_list: [],

      account_basic_info_form_rules: {
        // sort: [{ required: true, validator: validSort, trigger: 'change' }],
        // username: [{ required: true, validator: validChineseLetter, trigger: 'change' }],
        // phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        // email: [{ required: true, validator: validEmail, trigger: 'change' }]
      }
    }
  },
  computed: {
    ...mapGetters([
      'user'
    ]),
    avatarUrl: function() {
      // avatarUrl: process.env.VUE_APP_BASE_API + '/path/avatar.jpg'
      return process.env.VUE_APP_BASE_API + this.user.avatar_file_path + this.user.avatar_file_name
    }
  },
  mounted() {
    this.getListContent()
  },
  methods: {
    /**
     * @description: 页面加载时，请求form表单各个list的下拉集合。请求数据成功，取消更新按钮的禁用状态，各个表单项赋值。
     */
    getListContent() {
      apiGetBasicList({ form: 'list' })
        .then(function(data) {
          this.copyListContent(data)
          this.updateFormData()
          //
          this.isUpdateBtnDisable = false
          this.$nextTick(() => {
            this.$refs['account_basic_info_form'].clearValidate()
          })
        }.bind(this))
        .catch(function(err) {
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

    updateFormData() {
      // this.formData = JSON.parse(JSON.stringify(data))
      this.formData.username = this.user.username
      this.formData.sex = this.user.sex
      this.formData.identity_document_number = this.user.identity_document_number
      // this.formData.phone = this.user.phone
      this.formData.attr_01_id = this.user.attr_01_id
      this.formData.attr_02_id = this.user.attr_02_id
      this.formData.attr_03_id = this.user.attr_03_id
      this.formData.attr_04_id = this.user.attr_04_id
    },

    /**
     * @description: 请求修改用户基本信息
     */
    handleUpdateUserBasicInfo() {
      this.$refs['account_basic_info_form'].validate((valid) => {
        if (valid) {
          this.$store.dispatch('account/updateUserBasicInfo', this.formData)
            .then(() => {
              this.$nextTick(() => {
                this.$refs['account_basic_info_form'].clearValidate()
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
     * @Description:
     * @Author: freeair
     * @Date: 2020-11-14 10:08:30
     * @param {*}
     * @return {*}
     */
    onAvatarUploadSuccess(res) {
      this.$store.dispatch('account/updateUserAvatar', res.avatar)
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.pages-account-settings-title {
  margin-bottom: 12px;
  color: $title-color;
  font-weight: 500;
  font-size: 20px;
  line-height: 28px;
}

.pages-account-settings-text {
  font-size: $font-size-base;
}

</style>
