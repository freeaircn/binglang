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
      :data="tableData"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
      :default-expand-all="true"
      row-key="id"
      size="small"
      :header-cell-style="{background:'#F2F6FC', color:'#606266'}"
    >
      <el-table-column :show-overflow-tooltip="true" prop="label" label="名称" />

      <el-table-column prop="enabled" label="是否启用" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.enabled == '1'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="update_time" label="更新日期" />

      <el-table-column label="操作" width="130px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="preUpdate(row.id)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="doDelete(row.id)" />
        </template>
      </el-table-column>
    </el-table>

    <!--表单渲染-->
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="500px">
      <el-form ref="form" :model="formData" :rules="rules" size="small" label-width="80px">
        <el-form-item label="名称" prop="label">
          <el-input v-model="formData.label" />
        </el-form-item>

        <el-form-item label="是否启用" prop="enabled">
          <el-radio-group v-model="formData.enabled" size="mini">
            <el-radio-button label="1">是</el-radio-button>
            <el-radio-button label="0">否</el-radio-button>
          </el-radio-group>
        </el-form-item>

        <el-form-item label="上级部门" prop="pid">
          <treeSelect v-model="formData.pid" :options="treeData" placeholder="选择上级部门" />
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
import treeSelect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

// import 公共method
import { validQueryWords, validLabel } from '@/utils/app/validator/common'

// import api
import { apiGetDept, apiCreateDept, apiUpdateDept, apiDelDept } from '@/api/app/admin/dept'

export default {
  name: 'AdminDept',
  components: { treeSelect },
  data() {
    return {
      query: {
        words: ''
      },

      tableLoading: false,
      tableData: [],
      treeData: [],

      dialogVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑',
        create: '新建'
      },
      formData: {
        id: null,
        label: null,
        enabled: '1',
        pid: 1,
        update_time: null
      },
      rules: {
        label: [{ required: true, validator: validLabel, trigger: 'change' }]
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
      apiGetDept(params)
        .then(function(data) {
          this.tableData.splice(0)
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

    // 请求后台menu tree，组装tree数据结构
    // 表单formData清空
    // 显示dialog
    preCreate() {
      this.rstFormData()
      var params = {
        select_col: 'id, label, pid',
        method: null,
        cond: null,
        cond_col: null
      }
      apiGetDept(params)
        .then(function(data) {
          this.treeData.splice(0)
          this.treeData = data.slice(0)
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
          apiCreateDept(this.formData)
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

    // 请求后台 tree，组装tree数据结构
    // 取row.id，请求后台，填写表单formData
    // 显示dialog
    preUpdate(rowID) {
      var params1 = {
        select_col: 'id, label, pid',
        method: null,
        cond: null,
        cond_col: null
      }
      var params2 = {
        select_col: null,
        method: 'where',
        cond: { 'id': rowID },
        cond_col: null
      }
      Promise.all([apiGetDept(params1), apiGetDept(params2)])
        .then(function(res) {
          this.treeData.splice(0)
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
          apiUpdateDept(tempData)
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
      this.$confirm('确定删除吗？子节点会同时删除，此操作不能撤销！', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
        center: true
      })
        .then(() => {
          apiDelDept(id)
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
      this.formData.label = null
      this.formData.enabled = '1'
      this.formData.pid = 1
      this.formData.update_time = null
    },
    copyFormData(data) {
      this.formData.id = data.id
      this.formData.label = data.label
      this.formData.enabled = data.enabled
      this.formData.pid = data.pid
      this.formData.update_time = data.update_time
    }
  }
}
</script>
