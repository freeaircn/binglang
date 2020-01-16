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
      <el-table-column :show-overflow-tooltip="true" prop="sex" label="性别" />
      <el-table-column :show-overflow-tooltip="true" prop="phone" label="手机号" />
      <el-table-column :show-overflow-tooltip="true" prop="email" label="电子邮箱" />
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
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="400px">
      <el-form ref="form" :model="formData" :rules="rules" size="mini" label-width="80px">
        <el-form-item label="工号" prop="employee_number">
          <el-input v-model="formData.employee_number" />
        </el-form-item>
        <el-form-item label="中文名" prop="username">
          <el-input v-model="formData.username" />
        </el-form-item>
        <el-form-item label="性别" prop="sex">
          <el-input v-model="formData.sex" />
        </el-form-item>
        <el-form-item label="身份证号" prop="identity_document_number">
          <el-input v-model="formData.identity_document_number" />
        </el-form-item>
        <el-form-item label="手机号" prop="phone">
          <el-input v-model="formData.phone" />
        </el-form-item>
        <el-form-item label="电子邮箱" prop="email">
          <el-input v-model="formData.email" />
        </el-form-item>
        <el-form-item label="是否启用" prop="enabled">
          <el-radio-group v-model="formData.enabled" size="mini">
            <el-radio-button label="1">是</el-radio-button>
            <el-radio-button label="0">否</el-radio-button>
          </el-radio-group>
        </el-form-item>

        <el-form-item label="党派" prop="dept_id">
          <el-select v-model="formData.dept_id" placeholder="选择部门">
            <el-option
              v-for="item in treeDept"
              :key="item.id"
              :label="item.label"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item label="部门" prop="dept_id">
          <el-select v-model="formData.dept_id" placeholder="选择部门">
            <el-option
              v-for="item in treeDept"
              :key="item.id"
              :label="item.label"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item label="岗位" prop="job_id">
          <el-select v-model="formData.job_id" placeholder="选择岗位">
            <el-option
              v-for="item in treeJob"
              :key="item.id"
              :label="item.label"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item label="密码" prop="password">
          <el-input v-model="formData.password" show-password autocomplete="off" />
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button size="mini" @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" size="mini" @click="dialogAction==='create'?doCreate():doUpdate()">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import 第三方组件

// import 公共method
import { validQueryWords, validLabel } from '@/utils/app/validator/common'

// import api
import { apiGetUser, apiCreateUser, apiUpdateUser, apiDelUser } from '@/api/app/admin/user'

export default {
  name: 'AdminUser',
  data() {
    return {
      query: {
        words: ''
      },

      tableLoading: false,
      tableData: [],
      treeData: [],

      pageTotalContent: 0,
      pageSize: 5,
      pageIdx: 1,

      dialogVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑',
        create: '新建'
      },
      formData: {
        id: null,
        employee_number: '',
        username: '',
        sex: '',
        identity_document_number: '',
        phone: '',
        email: '',
        enabled: '1',
        dept_id: null,
        job_id: null,
        password: ''
      },
      rules: {
        // label: [{ required: true, validator: validLabel, trigger: 'change' }]
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
    this.updateTbl(null)
  },
  methods: {
    // CRUD core
    updateTbl(params) {
      this.tableLoading = true

      if (params === null) {
        params = {
          select_col: null,
          method: null,
          cond: null,
          cond_col: null
        }
      }
      apiGetUser(params)
        .then(function(data) {
          this.tableData.splice(0, this.tableData.length)
          this.pageTotalContent = data.slice(0).length
          this.tableData = data.slice(0)
        }.bind(this))
        .catch(function(err) {
          console.log(err)
        })
        .finally(function() {
          this.tableLoading = false
        }.bind(this))
    },
    handleQuery() {
      // API param - this.word，输入检验
      if (this.query.words === '') {
        this.updateTbl(null)
      } else {
        const res = validQueryWords(this.query.words)
        if (res === true) {
          var params = {
            select_col: null,
            method: 'like',
            cond: { 'label': this.query.words },
            cond_col: null
          }
          this.updateTbl(params)
        } else {
          this.$notify.error({
            title: '错误',
            message: res,
            duration: 2000
          })
        }
      }
    },

    // 表单formData清空
    // 获取字典列表
    // 显示dialog
    preCreate() {
      this.rstFormData()
      const params = {
        wanted: 'new_form'
      }
      apiGetUser(params)
        .then(function(data) {
          // this.treeData.splice(0, this.treeData.length)
          // this.treeData = data.slice(0)
          //
          this.dialogAction = 'create'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form'].clearValidate()
          })
        }.bind(this))
        .catch(function(err) {
          console.log(err)
        })
    },
    // validate 表单输入，请求后台
    // 接收response
    // 200，获取整个数据表，更新显示
    doCreate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          // API create
          apiCreateUser(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.updateTbl(null)
            }.bind(this))
            .catch(function(err) {
              console.log(err)
            })
        }
      })
    },

    // 取row.id，请求后台，填写表单formData
    // 获取字典列表
    // 显示dialog
    preUpdate(rowID) {
      var params1 = {
        select_col: null,
        method: null,
        cond: null,
        cond_col: null
      }
      apiGetUser(params1)
        .then(function(res) {
          this.treeData.splice(0, this.treeData.length)
          this.treeData = res[0].slice(0)
          //
          this.copyFormData(res[1][0])
          this.dialogAction = 'update'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form'].clearValidate()
          })
        }.bind(this))
        .catch(function(err) {
          console.log(err)
        })
    },
    // validate 表单输入，请求后台
    // 接收response，更新显示
    doUpdate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.formData)
          // API update
          apiUpdateUser(tempData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.updateTbl(null)
            }.bind(this))
            .catch(function(err) {
              console.log(err)
            })
        }
      })
    },

    // 子节点删除处理
    // 接收response，更新显示
    doDelete(id) {
      this.$confirm('确定删除吗？此操作不能撤销！', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
        center: true
      })
        .then(() => {
          apiDelUser(id)
            .then(function(data) {
              this.$message({
                type: 'success',
                message: '删除成功!'
              })
              this.updateTbl(null)
            }.bind(this))
            .catch(function(err) {
              console.log(err)
            })
        })
        .catch(() => {
        })
    },

    // 其他
    rstFormData() {
      this.formData.id = null
      this.formData.employee_number = ''
      this.formData.username = ''
      this.formData.sex = ''
      this.formData.phone = ''
      this.formData.email = ''
      this.formData.enabled = '1'
      this.formData.dept_id = null
      this.formData.job_id = null
      this.formData.password = ''
    },
    copyFormData(data) {
      this.formData.id = data.id
      this.formData.label = data.label
      this.formData.name = data.name
      this.formData.code = data.code
      this.formData.enabled = data.enabled
      this.formData.sort = data.sort
      this.formData.dict_id = data.dict_id

      this.formData.id = data.id
      this.formData.employee_number = data.employee_number
      this.formData.username = data.username
      this.formData.sex = data.sex
      this.formData.phone = data.phone
      this.formData.email = data.email
      this.formData.enabled = data.enabled
      this.formData.dept_id = data.dept_id
      this.formData.job_id = data.job_id
      this.formData.password = data.password
    },
    pageSizeChange(val) {
      this.pageSize = val
    },
    pageIdxChange(val) {
      this.pageIdx = val
    }
  }
}
</script>
