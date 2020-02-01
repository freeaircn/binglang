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
      row-key="id"
      highlight-current-row
      size="small"
      :header-cell-style="{background:'#F2F6FC', color:'#606266'}"
    >
      <el-table-column prop="sort" label="序号" />
      <el-table-column :show-overflow-tooltip="true" prop="dict_label" label="所属词典" />
      <el-table-column :show-overflow-tooltip="true" prop="label" label="词条名" />
      <el-table-column :show-overflow-tooltip="true" prop="name" label="注释" />
      <el-table-column :show-overflow-tooltip="true" prop="code" label="Code值" />

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
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="400px">
      <el-form ref="form" :model="formData" :rules="rules" size="small" label-width="80px">
        <el-form-item label="所属词典" prop="dict_id">
          <el-select v-model="formData.dict_id" :disabled="dialogAction==='update'" placeholder="选择所属字典">
            <el-option
              v-for="item in treeData"
              :key="item.id"
              :label="item.label"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item label="序号" prop="sort">
          <el-input-number v-model="formData.sort" />
        </el-form-item>

        <el-form-item label="词条名" prop="label">
          <el-input v-model="formData.label" clearable />
        </el-form-item>
        <el-form-item label="注释" prop="name">
          <el-input v-model="formData.name" clearable />
        </el-form-item>
        <el-form-item label="Code值" prop="code">
          <el-input v-model="formData.code" clearable />
        </el-form-item>

        <el-form-item label="是否启用" prop="enabled">
          <el-radio-group v-model="formData.enabled" size="mini">
            <el-radio-button label="1">是</el-radio-button>
            <el-radio-button label="0">否</el-radio-button>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button size="mini" @click="cancelDialog()">取消</el-button>
        <el-button type="primary" size="mini" @click="dialogAction==='create'?doCreate():doUpdate()">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import components
import SearchOptions from '@/components/app/SearchOptions/index'
import searchOptionsConfig from '@/views/app/admin/dict/dict-data-search-mixin'

// import utils
// import { validSort, validChineseLetter, validLowerLetterUnderline } from '@/utils/app/validator/common'

// import api
import { apiGet, apiCreate, apiUpdate, apiDelete } from '@/api/app/admin/dict-data'
import { apiGet as apiGetDict } from '@/api/app/admin/dict'

export default {
  name: 'AdminDictData',
  components: { SearchOptions },
  mixins: [searchOptionsConfig],
  data() {
    return {
      query: {},

      tableLoading: false,
      tableData: [],
      tableTotalRows: 0,

      treeData: [],

      pageSize: 10,
      pageIdx: 1,

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
        name: '',
        code: '',
        enabled: '1',
        dict_id: ''
      },
      rules: {
        // label: [{ required: true, validator: validLabel, trigger: 'change' }]
      }
    }
  },
  computed: {
    limit: function() {
      return this.pageSize.toString() + '_' + ((this.pageIdx - 1) * this.pageSize).toString()
    }
  },
  // mounted: function() {
  //   this.refreshTblDisplay()
  // },
  methods: {
    // CRUD core

    /**
     * @description: success response, tableData be updated; failed response, tableData be cleared
     * @param array
     * @return:
     */
    refreshTblDisplay(params = {}) {
      this.tableLoading = true

      var temp = JSON.parse(JSON.stringify(params))
      temp['limit'] = this.limit
      apiGet(temp)
        .then(function(data) {
          this.tableTotalRows = data.total_rows
          this.tableData.splice(0)
          this.tableData = data.dict_data.slice(0)
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
     * @description: reset formData, dict list, show dialog
     * @param {type}
     * @return:
     */
    preCreate() {
      this.rstFormData()
      apiGetDict({ sender: 'dict_data' })
        .then(function(data) {
          this.treeData.splice(0)
          this.treeData = data.dict.slice(0)
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
              this.tableTotalRows = this.tableTotalRows + 1

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
      Promise.all([apiGetDict({ sender: 'dict_data' }), apiGet({ id: id })])
        .then(function(res) {
          this.treeData.splice(0)
          this.treeData = res[0].dict.slice(0)
          //
          this.updateFormData(res[1][0])
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
      this.$confirm('确定删除吗？', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning',
        center: true
      })
        .then(() => {
          apiDelete(id)
            .then(function(data) {
              if (this.tableTotalRows > 0) {
                this.tableTotalRows = this.tableTotalRows - 1
              }
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
      this.formData.label = ''
      this.formData.name = ''
      this.formData.code = ''
      this.formData.enabled = '1'
      this.formData.sort = '1'
      this.formData.dict_id = ''
    },
    updateFormData(form) {
      this.formData.id = form.id
      this.formData.label = form.label
      this.formData.name = form.name
      this.formData.code = form.code
      this.formData.enabled = form.enabled
      this.formData.sort = form.sort
      this.formData.dict_id = form.dict_id
    },

    cancelDialog() {
      this.dialogAction = ''
      this.dialogVisible = false
      this.rstFormData()
    },

    pageSizeChange(val) {
      this.pageSize = val
      this.refreshTblDisplay(this.query)
    },
    pageIdxChange(val) {
      this.pageIdx = val
      this.refreshTblDisplay(this.query)
    },

    handleSearch(search) {
      this.query = JSON.parse(JSON.stringify(search))

      this.pageIdx = 1
      this.refreshTblDisplay(this.query)
    },
    searchChange(search) {
      this.query = JSON.parse(JSON.stringify(search))

      this.pageIdx = 1
      this.refreshTblDisplay(this.query)
    }
  }
}
</script>
