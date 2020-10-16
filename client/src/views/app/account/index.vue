<template>
  <div class="app-container">
    <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab">
      <el-tab-pane name="tab_one" label="基本信息">
        <div>基本信息</div>

        <el-row :gutter="8">
          <el-col :xs="24" :sm="24" :md="4" :lg="4" :xl="4">
            <div>头像</div>
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
              <el-form-item label="工号" prop="sort">
                <el-input v-model="formData.sort" :disabled="true" />
              </el-form-item>
              <el-form-item label="手机号" prop="phone">
                <el-input v-model="formData.phone" :disabled="true" />
              </el-form-item>
              <el-form-item label="电子邮箱" prop="email">
                <el-input v-model="formData.email" :disabled="true" />
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
// import UserCard from './components/UserCard'
// import Activity from './components/Activity'
// import Timeline from './components/Timeline'
// import Account from './components/Account'

export default {
  name: 'AccountSettings',
  // components: { UserCard, Activity, Timeline, Account },
  components: { TreeSelect },
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
        sort: '',
        attr_01_id: '',
        attr_02_id: '',
        attr_03_id: '',
        attr_04_id: '',
        user_attribute: []
      },

      // form 下拉列表元素
      role_list: [],
      dept_list: [],
      job_list: [],
      politic_list: [],
      professional_title_list: []

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
      this.user = {
        name: this.name,
        role: this.roles.join(' | '),
        email: 'admin@test.com',
        avatar: this.avatar
      }
    }
  }
}
</script>
