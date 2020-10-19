<template>
  <div class="app-container">
    <!-- <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab"> -->
    <el-tabs v-model="tabIndex" tab-position="left">
      <el-tab-pane name="tab_one" label="基本信息">
        <div class="pages-account-settings-title">基本信息</div>

        <el-row :gutter="8">
          <el-col :xs="24" :sm="24" :md="4" :lg="4" :xl="4">
            <AppAvatar />

            <div class="pages-account-settings-text">
              <p>工号: 1</p>
              <p>手机: {{ user.phone }}</p>
              <p>邮箱: {{ user.email }}</p>
            </div>

          </el-col>
          <el-col :xs="24" :sm="24" :md="8" :lg="8" :xl="8">
            <el-form ref="form_tab_one" :model="formData" :rules="rules_tab_one" size="mini" label-position="top" label-width="auto">

              <el-form-item label="中文名" prop="username">
                <el-input v-model="formData.username" clearable />
              </el-form-item>
              <el-form-item label="性别" prop="sex">
                <el-radio-group v-model="formData.sex">
                  <el-radio label="0">男</el-radio>
                  <el-radio label="1">女</el-radio>
                </el-radio-group>
              </el-form-item>
              <el-form-item label="身份证号" prop="identity_document_number">
                <el-input v-model="formData.identity_document_number" clearable />
              </el-form-item>

              <el-form-item label="政治面貌" prop="attr_03_id">
                <el-select v-model="formData.attr_03_id" placeholder="请选择" clearable>
                  <el-option
                    v-for="item in politic_list"
                    :key="item.id"
                    :label="item.label"
                    :value="item.id"
                  />
                </el-select>
              </el-form-item>

              <el-form-item label="部门" prop="attr_01_id">
                <TreeSelect
                  :value.sync="formData.attr_01_id"
                  :options="dept_list"
                  :placeholder="'请选择'"
                />
              </el-form-item>

              <el-form-item label="岗位" prop="attr_02_id">
                <el-select v-model="formData.attr_02_id" placeholder="请选择" clearable>
                  <el-option
                    v-for="item in job_list"
                    :key="item.id"
                    :label="item.label"
                    :value="item.id"
                  />
                </el-select>
              </el-form-item>

              <el-form-item label="职称" prop="attr_04_id">
                <el-select v-model="formData.attr_04_id" placeholder="请选择" clearable>
                  <el-option
                    v-for="item in professional_title_list"
                    :key="item.id"
                    :label="item.label"
                    :value="item.id"
                  />
                </el-select>
              </el-form-item>
            </el-form>

            <el-button type="primary" size="mini" @click="doUpdate()">更新基本信息</el-button>
          </el-col>
        </el-row>
      </el-tab-pane>

      <el-tab-pane name="tab_two" label="安全设置" />
    </el-tabs>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import TreeSelect from '@/components/app/TreeSelect/index'
import AppAvatar from './avatar/index'
// import UserCard from './components/UserCard'
// import Activity from './components/Activity'
// import Timeline from './components/Timeline'
// import Account from './components/Account'

export default {
  name: 'AccountSettings',
  // components: { UserCard, Activity, Timeline, Account },
  components: { TreeSelect, AppAvatar },
  data() {
    return {
      tabIndex: 'tab_one',

      // form 提交数据
      formData: {
        id: '',
        username: '',
        sex: '0',
        phone: '',
        email: '',
        password: '',
        enabled: '1',
        roles: [],
        identity_document_number: '',
        sort: '1',
        attr_01_id: '',
        attr_02_id: '',
        attr_03_id: '',
        attr_04_id: ''
      },

      // form 下拉列表元素
      role_list: [],
      dept_list: [],
      job_list: [],
      politic_list: [],
      professional_title_list: [],

      rules_tab_one: {
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
    ])
  },
  created() {
    this.getUser()
  },
  methods: {
    getUser() {
      // this.user = {
      //   // name: this.name,
      //   // role: this.roles.join(' | '),
      //   // email: 'admin@test.com',
      //   // avatar: this.avatar
      // }
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
