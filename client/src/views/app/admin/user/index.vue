<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <el-row>
        <!-- 搜索 -->
        <el-col :span="20">
          <el-form ref="form_query" :model="query" :rules="rules_form_query" :inline="true" size="mini" label-width="80px">
            <el-form-item prop="individual">
              <el-tooltip effect="dark" content="查询字段:工号，姓名，手机号，邮箱，身份证号" placement="top">
                <el-input v-model="query.individual" clearable placeholder="工号，姓名，手机号..." />
              </el-tooltip>
            </el-form-item>

            <el-form-item prop="sex">
              <el-tooltip effect="dark" content="查询字段:性别" placement="top">
                <el-select v-model="query.sex" clearable placeholder="性别" style="width:80px;">
                  <el-option label="男" value="0" />
                  <el-option label="女" value="1" />
                </el-select>
              </el-tooltip>
            </el-form-item>
            <el-form-item prop="dept">
              <el-tooltip effect="dark" content="查询字段:部门" placement="top">
                <el-input v-model="query.dept" clearable placeholder="字段：部门" style="width:150px;" />
              </el-tooltip>
            </el-form-item>
            <el-form-item prop="job">
              <el-tooltip effect="dark" content="查询字段:岗位" placement="top">
                <el-input v-model="query.job" clearable placeholder="字段：岗位" style="width:150px;" />
              </el-tooltip>
            </el-form-item>
            <el-form-item prop="politic">
              <el-tooltip effect="dark" content="查询字段:党派" placement="top">
                <el-input v-model="query.politic" clearable placeholder="字段：党派" style="width:150px;" />
              </el-tooltip>
            </el-form-item>
            <el-form-item prop="professional_title">
              <el-tooltip effect="dark" content="查询字段:职称" placement="top">
                <el-input v-model="query.professional_title" clearable placeholder="字段：职称" style="width:150px;" />
              </el-tooltip>
            </el-form-item>

            <el-form-item>
              <el-tooltip effect="dark" content="各字段按“与”组合查询" placement="top">
                <el-button type="success" icon="el-icon-search" @click="handleQuery">查询</el-button>
              </el-tooltip>
              <el-button type="primary" icon="el-icon-plus" @click="preCreate">新增</el-button>
              <el-button type="primary" icon="el-icon-plus" @click="x1">x1</el-button>
              <el-button type="primary" icon="el-icon-plus" @click="x2">x2</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :span="4">
          <TableOptions :table-columns="columns" />
        </el-col>
      </el-row>
    </div>
    <el-divider><i class="el-icon-arrow-down" /></el-divider>
    <!--表格渲染-->
    <el-table
      ref="table"
      v-loading="tableLoading"
      :data="tableData"
      row-key="id"
      highlight-current-row
      size="small"
      :header-cell-style="{background:'#F2F6FC', color:'#606266'}"
    >
      <el-table-column v-if="columnOpt.visible('sort')" prop="sort" label="工号" />
      <el-table-column v-if="columnOpt.visible('username')" :show-overflow-tooltip="true" prop="username" label="中文名" />
      <el-table-column v-if="columnOpt.visible('sex')" :show-overflow-tooltip="true" prop="sex" label="性别" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.sex == '1'">女</span>
          <span v-else>男</span>
        </template>
      </el-table-column>
      <el-table-column v-if="columnOpt.visible('phone')" :show-overflow-tooltip="true" prop="phone" label="手机号" />
      <el-table-column v-if="columnOpt.visible('email')" :show-overflow-tooltip="true" prop="email" label="电子邮箱" />
      <el-table-column v-if="columnOpt.visible('identity_document_number')" column-key="pre-hide" :show-overflow-tooltip="true" prop="identity_document_number" label="身份证号" />
      <el-table-column v-if="columnOpt.visible('dept_label')" :show-overflow-tooltip="true" prop="dept_label" label="部门" />
      <el-table-column v-if="columnOpt.visible('job_label')" :show-overflow-tooltip="true" prop="job_label" label="岗位" />

      <el-table-column
        v-for="item in dynamic_columns"
        v-if="columnOpt.visible(item.name)"
        :key="item.name"
        :show-overflow-tooltip="true"
        :prop="item.name"
        :label="item.label"
      />

      <el-table-column v-if="columnOpt.visible('enabled')" column-key="pre-hide" prop="enabled" label="是否启用" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.enabled == '1'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>

      <el-table-column v-if="columnOpt.visible('last_login')" column-key="pre-hide" :show-overflow-tooltip="true" prop="last_login" label="登录日期" />
      <el-table-column v-if="columnOpt.visible('ip_address')" column-key="pre-hide" :show-overflow-tooltip="true" prop="ip_address" label="登录IP" />

      <el-table-column v-if="columnOpt.visible('update_time')" prop="update_time" label="更新日期" />

      <el-table-column label="操作" width="130px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="preUpdate(row.id)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="doDelete(row.id)" />
        </template>
      </el-table-column>
    </el-table>

    <!--分页-->
    <el-pagination
      :page-sizes="[5, 10, 30]"
      :page-size="pageSize"
      :current-page.sync="pageIdx"
      layout="total, prev, pager, next, sizes"
      :total="tableTotalRows"
      @size-change="pageSizeChange"
      @current-change="pageIdxChange"
    />

    <!--表单渲染-->
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="480px">
      <el-tabs v-model="tabIndex" tab-position="left" :before-leave="leaveTab">
        <el-tab-pane name="tab_one" label="基本信息">
          <el-form ref="form_tab_one" :model="formData" :rules="rules_tab_one" size="mini" label-width="80px">
            <el-form-item label="工号" prop="sort">
              <el-input v-model="formData.sort" clearable />
            </el-form-item>
            <el-form-item label="中文名" prop="username">
              <el-input v-model="formData.username" clearable />
            </el-form-item>
            <el-form-item label="性别" prop="sex">
              <el-radio-group v-model="formData.sex">
                <el-radio label="0">男</el-radio>
                <el-radio label="1">女</el-radio>
              </el-radio-group>
            </el-form-item>

            <el-form-item label="手机号" prop="phone">
              <el-input v-model="formData.phone" clearable />
            </el-form-item>
            <el-form-item label="电子邮箱" prop="email">
              <el-input v-model="formData.email" clearable />
            </el-form-item>
            <el-form-item label="密码" prop="password">
              <el-input v-model="formData.password" show-password autocomplete="off" clearable />
            </el-form-item>
            <el-form-item label="是否启用" prop="enabled">
              <el-radio-group v-model="formData.enabled">
                <el-radio label="1">是</el-radio>
                <el-radio label="0">否</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item label="所属角色" prop="role">
              <el-select v-model="formData.roles" multiple placeholder="授予权限，可多选" clearable>
                <el-option
                  v-for="item in role_list"
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
              <el-input v-model="formData.identity_document_number" clearable />
            </el-form-item>
            <el-form-item label="部门" prop="dept_id">
              <treeSelect v-model="formData.dept_id" :options="dept_list" placeholder="选择部门" />
            </el-form-item>
            <el-form-item label="岗位" prop="job_id">
              <el-select v-model="formData.job_id" placeholder="选择岗位" clearable>
                <el-option
                  v-for="item in job_list"
                  :key="item.id"
                  :label="item.label"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item
              v-for="(category, index) in user_attribute_dynamic_list"
              :key="index"
              :label="category.label"
              :prop="category.label"
            >
              <el-select v-model="formData.user_attribute[index]" placeholder="请选择" clearable>
                <el-option
                  v-for="item in category.sub_list"
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
        <el-button size="mini" @click="cancelDialog()">取 消</el-button>
        <el-button v-show="tabIndex == 'tab_one'" type="primary" size="mini" @click="toNextTab()">下一页</el-button>
        <el-button v-show="tabIndex == 'tab_two'" type="primary" size="mini" @click="dialogAction==='create'?doCreate():doUpdate()">提 交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import 第三方组件
import treeSelect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

import TableOptions from '@/components/app/TableOptions/index'
import hideColumns from '@/components/app/TableOptions/hide-columns'

// import 公共method
import { validChineseChar, validPhone, validEmail, validSort } from '@/utils/app/validator/common'

// import api
import { apiGetUser, apiCreateUser, apiUpdateUser, apiDelUser } from '@/api/app/admin/user'

export default {
  name: 'AdminUser',
  components: { treeSelect, TableOptions },
  mixins: [hideColumns()],
  data() {
    return {
      query: {
        individual: '',
        sex: '',
        dept: '',
        job: '',
        politic: '',
        professional_title: ''
      },

      tableLoading: false,
      tableData: [],
      dynamic_columns: [],
      initTableDone: false,

      tableTotalRows: 0,
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
        dept_id: '1',
        job_id: '',
        user_attribute: []
      },

      // 关联其他table，获取data
      role_list: [],
      user_attribute_dynamic_list: [],
      dept_list: [],
      job_list: [],

      rules_tab_one: {
        sort: [{ required: true, validator: validSort, trigger: 'change' }],
        username: [{ required: true, validator: validChineseChar, trigger: 'change' }],
        phone: [{ required: true, validator: validPhone, trigger: 'change' }],
        email: [{ required: true, validator: validEmail, trigger: 'change' }]
      }
    }
  },
  computed: {
    limit: function() {
      return this.pageSize.toString() + '_' + ((this.pageIdx - 1) * this.pageSize).toString()
    }
  },
  created() {
    this.refreshTblDisplay()
  },
  methods: {
    // CRUD core
    /**
     * @description: success response, tableData be updated; failed response, tableData be cleared
     * @param {type}
     * @return:
     */
    refreshTblDisplay() {
      this.tableLoading = true

      var params = []
      if (this.isQueryEmpty()) {
        params['limit'] = this.limit
      } else {
        params['limit'] = this.limit
        params['individual'] = this.query.individual
        params['sex'] = this.query.sex
        params['dept'] = this.query.dept
        params['job'] = this.query.job
        params['politic'] = this.query.politic
        params['professional_title'] = this.query.professional_title
      }
      apiGetUser(params)
        .then(function(data) {
          this.tableTotalRows = data.total_rows
          this.tableData.splice(0)
          this.tableData = data.users.slice(0)
          this.dynamic_columns = data.dynamic_columns.slice(0)

          this.$nextTick(() => {
            if (!this.initTableDone) {
              this.updateColumns()
              this.initTableDone = true
            }
          })
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
      this.role_list.splice(0)
      this.dept_list.splice(0)
      this.job_list.splice(0)
      this.user_attribute_dynamic_list.splice(0)

      this.tabIndex = 'tab_one'
      apiGetUser({ form: 'user_create' })
        .then(function(data) {
          this.role_list = data.role_list.slice(0)
          this.dept_list = data.dept_list.slice(0)
          this.job_list = data.job_list.slice(0)
          this.user_attribute_dynamic_list = data.user_attribute_dynamic_list.slice(0)
          const tempLength = this.user_attribute_dynamic_list.length
          this.formData.user_attribute = new Array(tempLength).fill('')
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
              this.tableTotalRows = this.tableTotalRows + 1

              this.$nextTick(() => {
                this.rstFormData()
                this.refreshTblDisplay()
              })
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
      this.role_list.splice(0)
      this.dept_list.splice(0)
      this.job_list.splice(0)
      this.user_attribute_dynamic_list.splice(0)

      this.tabIndex = 'tab_one'
      apiGetUser({ form: 'user_edit', uid: id })
        .then(function(data) {
          this.role_list = data.role_list.slice(0)
          this.dept_list = data.dept_list.slice(0)
          this.job_list = data.job_list.slice(0)
          this.user_attribute_dynamic_list = data.user_attribute_dynamic_list.slice(0)
          //
          this.updateFormData(data.form)
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

              this.$nextTick(() => {
                this.rstFormData()
                this.refreshTblDisplay()
              })
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
              if (this.tableTotalRows > 0) {
                this.tableTotalRows = this.tableTotalRows - 1
              }
              this.$nextTick(() => {
                this.refreshTblDisplay()
              })
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
      this.formData.id = ''
      this.formData.sort = ''
      this.formData.username = ''
      this.formData.sex = '0'
      this.formData.identity_document_number = ''
      this.formData.phone = ''
      this.formData.email = ''
      this.formData.enabled = '1'
      this.formData.dept_id = '1'
      this.formData.job_id = ''
      this.formData.password = ''
      this.formData.roles.splice(0)
      this.formData.user_attribute.splice(0)
    },
    updateFormData(form) {
      // this.formData = JSON.parse(JSON.stringify(data))
      this.formData.id = form.id
      this.formData.sort = form.sort
      this.formData.username = form.username
      this.formData.sex = form.sex
      this.formData.identity_document_number = form.identity_document_number
      this.formData.phone = form.phone
      this.formData.email = form.email
      this.formData.enabled = form.enabled
      this.formData.dept_id = form.dept_id
      this.formData.job_id = form.job_id
      this.formData.password = ''
      this.formData.roles = form.roles.slice(0)
      this.formData.user_attribute = form.user_attribute.slice(0)
    },

    cancelDialog() {
      this.dialogAction = ''
      this.dialogVisible = false
      this.rstFormData()
    },

    pageSizeChange(val) {
      this.pageSize = val
      this.refreshTblDisplay()
    },
    pageIdxChange(val) {
      this.pageIdx = val
      this.refreshTblDisplay()
    },

    handleQuery() {
      // API param - this.word，输入检验
      this.pageIdx = 1
      this.refreshTblDisplay()
    },

    isQueryEmpty() {
      var empty = []
      if (this.query.individual !== '') {
        empty.push(1)
      }
      if (this.query.sex !== '') {
        empty.push(1)
      }
      if (this.query.dept !== '') {
        empty.push(1)
      }
      if (this.query.job !== '') {
        empty.push(1)
      }
      if (this.query.politic !== '') {
        empty.push(1)
      }
      if (this.query.professional_title !== '') {
        empty.push(1)
      }

      if (empty.length === 0) {
        return true
      } else {
        return false
      }
    },
    x1() {
      console.log('# apiCreateUser ')
      apiCreateUser({ 'roles': [] })
    },
    x2() {
      console.log('# apiUpdateUser ')
      apiUpdateUser({ 'roles': [] })
    }
  }
}
</script>
