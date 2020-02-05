<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <SearchOptions :inputs="searchOptionsInputs" :rules="searchOptionsRules" @click-search="handleSearch" @change="searchChange" />
      <el-button type="success" size="mini" icon="el-icon-plus" @click="preCreate">新增</el-button>
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
      <el-table-column :show-overflow-tooltip="true" prop="label" label="标签" />
      <el-table-column prop="sort" label="序号" />

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
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="400px">
      <el-form ref="form" :model="formData" :rules="rules" size="small" label-width="80px">
        <el-form-item label="标签" prop="label">
          <el-input v-model="formData.label" />
        </el-form-item>
        <el-form-item label="序号" prop="sort">
          <el-input-number v-model="formData.sort" />
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
// import components
import SearchOptions from '@/components/app/SearchOptions/index'
import searchOptionsConfig from '@/views/app/admin/dept/dept-search-mixin'
import treeSelect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

// import utils
// import { validSort, validChineseLetter, validLowerLetterUnderline } from '@/utils/app/validator/common'

// import api
import { apiGet, apiCreate, apiUpdate, apiDelete } from '@/api/app/admin/dept'

export default {
  name: 'AdminDept',
  components: { SearchOptions, treeSelect },
  mixins: [searchOptionsConfig()],
  data() {
    return {
      query: {},

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
        id: '',
        sort: '1',
        label: '',
        enabled: '1',
        pid: '1'
      },
      rules: {
        // sort: [{ required: true, validator: validSort, trigger: 'change' }],
        // label: [{ required: true, validator: validChineseLetter, trigger: 'change' }]
      }
    }
  },
  // mounted: function() {
  //   this.refreshTblDisplay()
  // },
  methods: {
    /**
     * @description: success response, tableData be updated; failed response, tableData be cleared
     * @param array
     * @return:
     */
    refreshTblDisplay(params = {}) {
      this.tableLoading = true

      var temp = JSON.parse(JSON.stringify(params))
      apiGet(temp)
        .then(function(data) {
          this.tableData.splice(0)
          this.tableData = data.dept.slice(0)
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
     * @description: reset formData, a blank form, show dialog
     * @param {type}
     * @return:
     */
    preCreate() {
      this.rstFormData()
      apiGet({ req: 'id_label_pid' })
        .then(function(data) {
          this.treeData.splice(0)
          this.treeData = data.dept_list.slice(0)
          //
          this.dialogAction = 'create'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form'].clearValidate()
          })
        }.bind(this))
        .catch(function(err) {
          this.tableData.splice(0)
          this.$message({
            message: err,
            type: 'warning'
          })
        }.bind(this))
    },
    /**
     * @description: validate form, post, update data display area
     * @param {type}
     * @return:
     */
    doCreate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          // API create
          apiCreate(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false

              this.$nextTick(() => {
                this.rstFormData()
                this.refreshTblDisplay(this.query)
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
     * @description: reset formData, request current row and dict list, show dialog
     * @param {type}
     * @return:
     */
    preUpdate(id) {
      this.rstFormData()
      Promise.all([apiGet({ req: 'id_label_pid' }), apiGet({ id: id })])
        .then(function(res) {
          this.treeData.splice(0)
          this.treeData = res[0].dept_list.slice(0)
          // 更新搜索区域
          // this.updateDictList(this.treeData)
          //
          this.updateFormData(res[1]['form'])
          this.dialogAction = 'update'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form'].clearValidate()
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
     * @description: validate form, post, update data display area
     * @param {type}
     * @return:
     */
    doUpdate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          // API update
          apiUpdate(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false

              this.$nextTick(() => {
                this.rstFormData()
                this.refreshTblDisplay(this.query)
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
      this.$confirm('确定删除吗？将同时删除子节点', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
        center: true
      })
        .then(() => {
          apiDelete(id)
            .then(function(data) {
              this.$nextTick(() => {
                this.refreshTblDisplay(this.query)
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
      this.formData.sort = '1'
      this.formData.label = ''
      this.formData.enabled = '1'
      this.formData.pid = '1'
    },
    updateFormData(form) {
      this.formData.id = form.id
      this.formData.sort = form.sort
      this.formData.label = form.label
      this.formData.enabled = form.enabled
      this.formData.pid = form.pid
    },

    cancelDialog() {
      this.dialogAction = ''
      this.dialogVisible = false
      this.rstFormData()
    },

    handleSearch(search) {
      this.query = JSON.parse(JSON.stringify(search))
      this.refreshTblDisplay(this.query)
    },
    searchChange(search) {
      this.query = JSON.parse(JSON.stringify(search))
      this.refreshTblDisplay(this.query)
    }
  }
}
</script>
