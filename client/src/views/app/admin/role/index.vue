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
      <el-table-column prop="sort" label="排序" />
      <el-table-column :show-overflow-tooltip="true" prop="label" label="标签" />
      <el-table-column :show-overflow-tooltip="true" prop="name" label="键名" />

      <el-table-column prop="enabled" label="是否启用" align="center">
        <template slot-scope="scope">
          <span v-if="scope.row.enabled == '1'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="remark" label="备注" />
      <el-table-column prop="update_time" label="更新日期" />

      <el-table-column label="操作" width="200px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="success" icon="el-icon-document-checked" @click="preAssignPermission(row)" />
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
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="500px">
      <el-form ref="form" :model="formData" :rules="rules" size="small" label-width="80px">
        <el-form-item label="标签" prop="label">
          <el-input v-model="formData.label" />
        </el-form-item>
        <el-form-item label="键名" prop="name">
          <el-input v-model="formData.name" />
        </el-form-item>

        <el-form-item label="是否启用" prop="enabled">
          <el-radio-group v-model="formData.enabled" size="mini">
            <el-radio-button label="1">是</el-radio-button>
            <el-radio-button label="0">否</el-radio-button>
          </el-radio-group>
        </el-form-item>

        <el-form-item label="排序" prop="sort">
          <el-input-number v-model.number="formData.sort" :min="0" :max="999" controls-position="right" style="" />
        </el-form-item>
        <el-form-item label="备注" prop="remark">
          <el-input v-model="formData.remark" />
        </el-form-item>

      </el-form>

      <div slot="footer" class="dialog-footer">
        <el-button size="mini" @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" size="mini" @click="dialogAction==='create'?doCreate():doUpdate()">提交</el-button>
      </div>
    </el-dialog>

    <!--指派权限-->
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisiblePermission" :title="titlePermissionTo" width="400px">
      <el-tree
        ref="treeSelect"
        :data="treeData"
        :default-checked-keys="checkedIds"
        :props="{ children: 'children', label: 'label' }"
        check-strictly
        accordion
        show-checkbox
        node-key="id"
      />

      <div slot="footer" class="dialog-footer">
        <el-button size="mini" @click="dialogVisiblePermission = false">取消</el-button>
        <el-button type="primary" size="mini" @click="doAssignPermission()">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import 第三方组件

// import 公共method
import { validQueryWords } from '@/utils/app/validator/common'

// import api
import { apiGetRole, apiCreateRole, apiUpdateRole, apiDelRole } from '@/api/app/admin/role'
import { apiGetRoleMenu, apiCreateRoleMenu } from '@/api/app/admin/role-menu'
import { apiGetMenu } from '@/api/app/admin/menu'

export default {
  name: 'AdminRole',
  data() {
    return {
      query: {
        words: ''
      },

      tableLoading: false,
      tableData: [],
      treeData: [],
      checkedIds: [],
      dialogVisiblePermission: false,
      titlePermissionTo: '',
      tempRoleId: null,

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
        sort: 999,
        label: '',
        name: '',
        enabled: '1',
        remark: ''
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
      apiGetRole(params)
        .then(function(data) {
          this.tableData.splice(0)
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
    // 显示dialog
    preCreate() {
      this.rstFormData()
      this.dialogAction = 'create'
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
    },
    // validate 表单输入，请求后台
    // 接收response
    // 200，获取整个数据表，更新显示
    doCreate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          // API create
          apiCreateRole(this.formData)
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
    // 显示dialog
    preUpdate(rowID) {
      var params = {
        select_col: null,
        method: 'where',
        cond: { 'id': rowID },
        cond_col: null
      }
      apiGetRole(params)
        .then(function(res) {
          this.copyFormData(res[0])
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
          apiUpdateRole(tempData)
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
          apiDelRole(id)
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
      this.formData.label = ''
      this.formData.name = ''
      this.formData.enabled = '1'
      this.formData.sort = 999
      this.formData.remark = ''
    },
    copyFormData(data) {
      this.formData.id = data.id
      this.formData.label = data.label
      this.formData.name = data.name
      this.formData.enabled = data.enabled
      this.formData.sort = data.sort
      this.formData.remark = data.remark
    },
    pageSizeChange(val) {
      this.pageSize = val
    },
    pageIdxChange(val) {
      this.pageIdx = val
    },

    // 获取menu tree
    // 获取roles_menus
    // 显示dialog
    preAssignPermission(role) {
      var params1 = {
        select_col: 'id, title, pid',
        method: null,
        cond: null,
        cond_col: null
      }
      var params2 = {
        select_col: 'menu_id',
        method: 'where',
        cond: { 'role_id': role.id },
        cond_col: null
      }
      this.tempRoleId = role.id
      Promise.all([apiGetMenu(params1), apiGetRoleMenu(params2)])
        .then(function(res) {
          this.treeData.splice(0)
          this.treeData = res[0].slice(0)
          //
          this.checkedIds = []
          res[1].forEach(element => {
            this.checkedIds.push(element.menu_id)
          })
          // this.checkedIds = res[1].slice(0)
          //
          this.titlePermissionTo = '授权 - ' + role.label
          this.dialogVisiblePermission = true
        }.bind(this))
        .catch(function(err) {
          console.log(err)
        })
    },

    doAssignPermission() {
      if (this.tempRoleId !== null) {
        const roleMenus = { role_id: this.tempRoleId, menus: [] }
        // 得到半选的父节点数据
        // this.$refs.treeSelect.getHalfCheckedKeys().forEach(function(data, index) {
        //   const menu = { id: data }
        //   role.menus.push(menu)
        // })
        // 得到已选中的 key 值
        this.$refs.treeSelect.getCheckedKeys().forEach(function(data, index) {
          const menu = data
          roleMenus.menus.push(menu)
        })
        apiCreateRoleMenu(roleMenus)
          .then(function(data) {
            this.titlePermissionTo = ''
            this.tempRoleId = null
            this.dialogVisiblePermission = false
          }.bind(this))
          .catch(function(err) {
            console.log(err)
          })
      }
    }
  }
}
</script>
