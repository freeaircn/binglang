<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <div>
        <!-- 搜索 -->
        <el-input v-model="query.words" clearable size="small" placeholder="搜索" style="width: 200px;" class="filter-item" @keyup.enter.native="handleQuery" />
        <el-button class="filter-item" size="mini" type="success" icon="el-icon-search" @click="handleQuery">查询</el-button>
        <el-button class="filter-item" size="mini" type="primary" icon="el-icon-plus" @click="preCreate">新增</el-button>
      </div>
    </div>
    <el-divider><i class="el-icon-arrow-down" /></el-divider>
    <!--表格渲染-->
    <el-table
      ref="table"
      v-loading="tableLoading"
      :data="tableToPage"
      row-key="id"
      size="small"
      :header-cell-style="{background:'#F2F6FC', color:'#606266'}"
    >
      <el-table-column prop="employee_number" label="工号" />
      <el-table-column :show-overflow-tooltip="true" prop="username" label="中文名" />
      <el-table-column :show-overflow-tooltip="true" prop="sex" label="性别" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.sex == '1'">女</span>
          <span v-else>男</span>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="phone" label="手机号" />
      <el-table-column :show-overflow-tooltip="true" prop="email" label="电子邮箱" />
      <el-table-column :show-overflow-tooltip="true" prop="identity_document_number" label="身份证号" />
      <el-table-column :show-overflow-tooltip="true" prop="dept_label" label="部门" />
      <el-table-column :show-overflow-tooltip="true" prop="job_label" label="岗位" />

      <el-table-column prop="enabled" label="是否启用" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.enabled == '1'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>

      <el-table-column :show-overflow-tooltip="true" prop="last_login" label="登录日期" />
      <el-table-column :show-overflow-tooltip="true" prop="ip_address" label="登录IP" />

      <el-table-column prop="update_time" label="更新日期" />

      <el-table-column label="操作" width="130px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="preUpdate(row.id)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="doDelete(row.id)" />
        </template>
      </el-table-column>
    </el-table>

    <!--分页-->
    <el-pagination
      :page-sizes="[5, 10, 30, 50]"
      :page-size="pageSize"
      :current-page="pageIdx"
      layout="total, prev, pager, next, sizes"
      :total="pageTotalContent"
      @size-change="pageSizeChange"
      @current-change="pageIdxChange"
    />

    <!--表单渲染-->
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="480px">
      <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab">
        <el-tab-pane name="tab_one" label="基本信息">
          <el-form ref="form_tab_one" :model="formData" :rules="rules_tab_one" size="mini" label-width="80px">
            <el-form-item label="中文名" prop="username">
              <el-input v-model="formData.username" />
            </el-form-item>
            <el-form-item label="性别" prop="sex">
              <el-radio-group v-model="formData.sex">
                <el-radio label="0">男</el-radio>
                <el-radio label="1">女</el-radio>
              </el-radio-group>
            </el-form-item>

            <el-form-item label="手机号" prop="phone">
              <el-input v-model="formData.phone" />
            </el-form-item>
            <el-form-item label="电子邮箱" prop="email">
              <el-input v-model="formData.email" />
            </el-form-item>
            <el-form-item label="密码" prop="password">
              <el-input v-model="formData.password" show-password autocomplete="off" />
            </el-form-item>
            <el-form-item label="是否启用" prop="enabled">
              <el-radio-group v-model="formData.enabled">
                <el-radio label="1">是</el-radio>
                <el-radio label="0">否</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="所属角色" prop="role">
              <el-select v-model="formData.role_ids" multiple placeholder="授予权限，可多选">
                <el-option
                  v-for="item in roleList"
                  :key="item.id"
                  :label="item.label"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
          </el-form>
        </el-tab-pane>

        <el-tab-pane name="tab_two" label="更多信息">
          <el-form ref="form_tab_two" :model="formData" :rules="rules_tab_two" size="mini" label-width="80px">
            <el-form-item label="身份证号" prop="identity_document_number">
              <el-input v-model="formData.identity_document_number" />
            </el-form-item>
            <el-form-item label="工号" prop="employee_number">
              <el-input v-model="formData.employee_number" />
            </el-form-item>
            <el-form-item label="部门" prop="dept_id">
              <treeSelect v-model="formData.dept_id" :options="deptList" placeholder="选择部门" />
            </el-form-item>
            <el-form-item label="岗位" prop="job_id">
              <el-select v-model="formData.job_id" placeholder="选择岗位">
                <el-option
                  v-for="item in jobList"
                  :key="item.id"
                  :label="item.label"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item
              v-for="(attribute, index) in extraAttributeList"
              :key="index"
              :label="attribute.label"
              :prop="attribute.label"
            >
              <el-select v-model="formData.extra_attributes[index]" placeholder="请选择">
                <el-option
                  v-for="item in attribute.values"
                  :key="item.id"
                  :label="item.label"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
          </el-form>
        </el-tab-pane>
      </el-tabs>

      <div slot="footer" class="dialog-footer">
        <el-button size="mini" @click="cancelDialog()">取消</el-button>
        <el-button v-show="tabIndex == 'tab_one'" type="primary" size="mini" @click="toNextTab()">下一页</el-button>
        <el-button v-show="tabIndex == 'tab_two'" type="primary" size="mini" @click="dialogAction==='create'?doCreate():doUpdate()">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import 第三方组件
import treeSelect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

// import 公共method
import { validQueryWords, validChineseChar, validPhone, validEmail } from '@/utils/app/validator/common'

// import api
import { apiGetUser, apiCreateUser, apiUpdateUser, apiDelUser } from '@/api/app/admin/user'

export default {
  name: 'AdminUser',
  components: { treeSelect },
  data() {
    return {
      query: {
        words: ''
      },

      tableLoading: false,
      tableData: [],

      pageTotalContent: 0,
      pageSize: 5,
      pageIdx: 1,

      dialogVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑',
        create: '新建'
      },
      tabIndex: 'tab_one',

      formData: {
        id: null,
        username: '',
        sex: '0',
        phone: '',
        email: '',
        password: '',
        enabled: '1',
        role_ids: [],
        identity_document_number: '',
        employee_number: '',
        dept_id: null,
        job_id: null,
        extra_attributes: []
      },

      // 关联其他table，获取data
      roleList: [],
      extraAttributeList: [],
      deptList: [],
      jobList: [],

      rules_tab_one: {
        username: [{ required: true, validator: validChineseChar, trigger: 'change' }],
        phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        email: [{ required: true, validator: validEmail, trigger: 'change' }]
      }
    }
  },
  computed: {
    // tableToPage计算属性通过slice方法计算表格当前应显示的数据
    tableToPage() {
      if (this.tableData.length !== 0) {
        return this.tableData.slice(
          (this.pageIdx - 1) * this.pageSize, this.pageIdx * this.pageSize)
      } else {
        return []
      }
    }
  },
  created() {
    this.updateTbl({ wanted: 'all' })
  },
  methods: {
    // CRUD core
    /**
     * @description: success response, tableData be updated; failed response, tableData be cleared
     * @param {type}
     * @return:
     */
    updateTbl(params) {
      this.tableLoading = true
      apiGetUser(params)
        .then(function(data) {
          this.pageTotalContent = data.users.slice(0).length
          this.tableData.splice(0)
          this.tableData = data.users.slice(0)
        }.bind(this))
        .catch(function(err) {
          this.tableData.splice(0)
          this.$message({
            message: err,
            type: 'warning'
          })
        }.bind(this))
        .finally(function() {
          this.tableLoading = false
        }.bind(this))
    },

    /**
     * @description: reset formData, request a blank user profile, show dialog
     * @param {type}
     * @return:
     */
    preCreate() {
      this.rstFormData()
      this.roleList.splice(0)
      this.deptList.splice(0)
      this.jobList.splice(0)
      this.extraAttributeList.splice(0)

      this.tabIndex = 'tab_one'
      apiGetUser({ wanted: 'new_form' })
        .then(function(data) {
          this.roleList = data.role.slice(0)
          this.deptList = data.dept.slice(0)
          this.jobList = data.job.slice(0)
          this.extraAttributeList = data.extra_attribute.slice(0)
          const tempLength = this.extraAttributeList.length
          this.formData.extra_attributes = new Array(tempLength).fill('')
          //
          this.dialogAction = 'create'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form_tab_one'].clearValidate()
            this.$refs['form_tab_two'].clearValidate()
          })
        }.bind(this))
        .catch(function(err) {
          this.$message({
            message: err,
            type: 'warning'
          })
        }.bind(this))
    },

    /**
     * @description: make sure form validation in tab_one passed, then turn to tab_two
     * @param {type}
     * @return:
     */
    toNextTab() {
      this.$refs['form_tab_one'].validate((valid) => {
        if (valid) {
          this.tabIndex = 'tab_two'
        }
      })
    },
    leaveTab(activeName, oldActiveName) {
      if (this.dialogVisible === true) {
        if (oldActiveName === 'tab_one') {
          return this.$refs['form_tab_one'].validate()
        }
        if (oldActiveName === 'tab_two') {
          return this.$refs['form_tab_two'].validate()
        }
      }
    },

    /**
     * @description: validate form in tab_two, post new form, update data display area
     * @param {type}
     * @return:
     */
    doCreate() {
      this.$refs['form_tab_two'].validate((valid) => {
        if (valid) {
          // API create
          apiCreateUser(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.updateTbl({ wanted: 'all' })
            }.bind(this))
            .catch(function(err) {
              this.$message({
                message: err,
                type: 'warning'
              })
            }.bind(this))
        }
      })
    },

    /**
     * @description: reset formData, select list, request a current user profile, show dialog
     * @param {type}
     * @return:
     */
    preUpdate(id) {
      this.rstFormData()
      this.roleList.splice(0)
      this.deptList.splice(0)
      this.jobList.splice(0)
      this.extraAttributeList.splice(0)

      this.tabIndex = 'tab_one'
      apiGetUser({ wanted: 'current_form', uid: id })
        .then(function(data) {
          this.roleList = data.lists.role.slice(0)
          this.deptList = data.lists.dept.slice(0)
          this.jobList = data.lists.job.slice(0)
          this.extraAttributeList = data.lists.extra_attribute.slice(0)
          //
          this.updateFormData(data.user)
          this.dialogAction = 'update'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form_tab_one'].clearValidate()
            this.$refs['form_tab_two'].clearValidate()
          })
        }.bind(this))
        .catch(function(err) {
          this.$message({
            message: err,
            type: 'warning'
          })
        }.bind(this))
    },

    /**
     * @description: validate form in tab_two, post update form, update data display area
     * @param {type}
     * @return:
     */
    doUpdate() {
      this.$refs['form_tab_two'].validate((valid) => {
        if (valid) {
          // API update
          apiUpdateUser(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.updateTbl({ wanted: 'all' })
            }.bind(this))
            .catch(function(err) {
              this.$message({
                message: err,
                type: 'warning'
              })
            }.bind(this))
        }
      })
    },

    /**
     * @description: delete by id, update data display area
     * @param {type}
     * @return:
     */
    doDelete(id) {
      this.$confirm('确定删除吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
        center: true
      })
        .then(() => {
          apiDelUser(id)
            .then(function() {
              this.updateTbl({ wanted: 'all' })
            }.bind(this))
            .catch(function(err) {
              this.$message({
                message: err,
                type: 'warning'
              })
            }.bind(this))
        })
        .catch(() => {
        })
    },

    // 其他
    rstFormData() {
      this.formData.id = null
      this.formData.employee_number = ''
      this.formData.username = ''
      this.formData.sex = '0'
      this.formData.identity_document_number = ''
      this.formData.phone = ''
      this.formData.email = ''
      this.formData.enabled = '1'
      this.formData.dept_id = null
      this.formData.job_id = null
      this.formData.password = ''
      this.formData.role_ids.splice(0)
      this.formData.extra_attributes.splice(0)
    },
    updateFormData(data) {
      // this.formData = JSON.parse(JSON.stringify(data))
      this.formData.id = data.id
      this.formData.employee_number = data.employee_number
      this.formData.username = data.username
      this.formData.sex = data.sex
      this.formData.identity_document_number = data.identity_document_number
      this.formData.phone = data.phone
      this.formData.email = data.email
      this.formData.enabled = data.enabled
      this.formData.dept_id = data.dept_id
      this.formData.job_id = data.job_id
      this.formData.password = ''
      this.formData.role_ids = data.role_ids.slice(0)
      this.formData.extra_attributes = data.extra_attributes.slice(0)
    },
    pageSizeChange(val) {
      this.pageSize = val
    },
    pageIdxChange(val) {
      this.pageIdx = val
    },
    cancelDialog() {
      this.dialogAction = ''
      this.dialogVisible = false
    },

    handleQuery() {
      // API param - this.word，输入检验
      if (this.query.words === '') {
        this.updateTbl({ wanted: 'all' })
      } else {
        const msg = validQueryWords(this.query.words)
        if (msg === true) {
          var params = {
            select_col: null,
            method: 'like',
            cond: { 'label': this.query.words },
            cond_col: null
          }
          this.updateTbl(params)
        } else {
          this.$message({
            message: msg,
            type: 'warning'
          })
        }
      }
    }
  }
}
</script>
