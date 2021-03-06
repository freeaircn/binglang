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
      size="small"
      :header-cell-style="{background:'#F2F6FC', color:'#606266'}"
    >
      <el-table-column prop="sort" label="序号" />
      <el-table-column :show-overflow-tooltip="true" prop="label" label="标签" />

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
        <el-form-item label="序号" prop="sort">
          <el-input-number v-model="formData.sort" />
        </el-form-item>
        <el-form-item label="标签" prop="label">
          <el-input v-model="formData.label" />
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
import searchOptionsConfig from '@/views/app/admin/job/job-search-mixin'

// import utils
import { validSort, validEnglishChineseLetter } from '@/utils/app/validator/common'

// import api
import { apiGet, apiCreate, apiUpdate, apiDelete } from '@/api/app/admin/job'

export default {
  name: 'AdminJob',
  components: { SearchOptions },
  mixins: [searchOptionsConfig()],
  data() {
    return {
      query: {},

      tableLoading: false,
      tableData: [],
      tableTotalRows: 0,

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
        enabled: '1'
      },
      rules: {
        sort: [{ required: true, validator: validSort, trigger: 'change' }],
        label: [{ required: true, validator: validEnglishChineseLetter, trigger: 'change' }]
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
          this.tableData = data.job.slice(0)
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
      this.dialogAction = 'create'
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
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
     * @description: reset formData, request current row, show dialog
     * @param {type}
     * @return:
     */
    preUpdate(id) {
      this.rstFormData()
      apiGet({ id: id })
        .then(function(data) {
          this.updateFormData(data.form)
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
            .then(function() {
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
      this.formData.sort = '1'
      this.formData.label = ''
      this.formData.enabled = '1'
    },
    updateFormData(form) {
      this.formData.id = form.id
      this.formData.sort = form.sort
      this.formData.label = form.label
      this.formData.enabled = form.enabled
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
