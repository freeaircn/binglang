<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <SearchOptions :inputs="searchOptionsInputs" :rules="searchOptionsRules" @click-search="handleSearch" @change="searchChange" />
      <el-button type="success" size="mini" icon="el-icon-plus" @click="preCreate">新增</el-button>
      <el-button type="success" size="mini" icon="el-icon-plus" @click="buildMenu">Build Menu</el-button>
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
      <el-table-column :show-overflow-tooltip="true" prop="title" label="标题" width="125px" />
      <el-table-column prop="sort" label="序号" />
      <el-table-column :show-overflow-tooltip="true" prop="path" label="url路径" />
      <el-table-column :show-overflow-tooltip="true" prop="name" label="组件名" />
      <el-table-column :show-overflow-tooltip="true" prop="component" label="组件路径" />
      <el-table-column :show-overflow-tooltip="true" prop="redirect" label="重定向" />

      <el-table-column prop="hidden" label="侧边可见" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.hidden == '0'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="alwaysShow" label="顶级可见" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.alwaysShow == '1'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="noCache" label="页面缓存" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.noCache == '0'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="breadcrumb" label="面包屑" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.breadcrumb == '1'">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>

      <el-table-column :show-overflow-tooltip="true" prop="roles" label="权限标识" />
      <el-table-column prop="update_time" label="更新日期" width="135px" />

      <el-table-column label="操作" width="130px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="preUpdate(row.id)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="doDelete(row.id)" />
        </template>
      </el-table-column>
    </el-table>

    <!--表单渲染-->
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="600px">
      <el-form ref="form" :model="formData" :rules="rules" size="small" label-width="80px">
        <el-row>
          <el-col :span="24">
            <el-form-item label="类型" prop="type">
              <el-radio-group v-model="formData.type" size="mini">
                <el-radio-button label="1">菜单</el-radio-button>
                <el-radio-button label="2">按钮</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>

        <!-- <el-form-item label="所属" prop="pid">
          <treeSelect v-model="formData.pid" :options="treeData" style="width: 450px;" placeholder="选择所属上一级" />
        </el-form-item> -->

        <el-form-item label="所属" prop="pid">
          <TreeSelectE
            :selected.sync="formData.pid"
            :data="treeData"
            :default-props="{ children: 'children', label: 'title' }"
            :placeholder="'选择所属上一级'"
            clearable
          />
        </el-form-item>

        <el-row>
          <el-col :span="12">
            <el-form-item m label="标题" prop="title">
              <el-input v-model="formData.title" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="序号" prop="sort">
              <el-input-number v-model="formData.sort" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-if="formData.type === '1'" label="url路径" prop="path">
              <el-input v-model="formData.path" placeholder="路由地址" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="重定向" prop="redirect">
              <el-input v-model="formData.redirect" placeholder="重定向地址" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="组件名称" prop="name">
              <el-input v-model="formData.name" placeholder="组件Name" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="组件路径" prop="component">
              <el-input v-model="formData.component" placeholder="组件路径" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="侧边可见" prop="hidden">
              <el-radio-group v-model="formData.hidden" size="mini">
                <el-radio-button label="0">是</el-radio-button>
                <el-radio-button label="1">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="顶级可见" prop="alwaysShow">
              <el-radio-group v-model="formData.alwaysShow" size="mini">
                <el-radio-button label="1">是</el-radio-button>
                <el-radio-button label="0">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="缓存页面" prop="noCache">
              <el-radio-group v-model="formData.noCache" size="mini">
                <el-radio-button label="0">是</el-radio-button>
                <el-radio-button label="1">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type === '1'" label="面包屑" prop="breadcrumb">
              <el-radio-group v-model="formData.breadcrumb" size="mini">
                <el-radio-button label="1">是</el-radio-button>
                <el-radio-button label="0">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item label="权限标识" prop="roles">
          <el-input v-model="formData.roles" placeholder="xx:list" style="width: 450px;" />
        </el-form-item>

        <el-form-item v-show="formData.type === '1'" label="图标" prop="icon">
          <el-popover
            placement="bottom-start"
            width="450"
            trigger="click"
            @show="$refs['iconSelect'].reset()"
          >
            <IconSelect ref="iconSelect" @selected="selected" />
            <el-input slot="reference" v-model="formData.icon" style="width: 450px;" placeholder="点击选择图标" readonly>
              <svg-icon v-if="formData.icon" slot="prefix" :icon-class="formData.icon" class="el-input__icon" style="height: 32px;width: 16px;" />
              <i v-else slot="prefix" class="el-icon-search el-input__icon" />
            </el-input>
          </el-popover>
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
import TreeSelectE from '@/components/app/TreeSelect/index'
import SearchOptions from '@/components/app/SearchOptions/index'
import searchOptionsConfig from '@/views/app/admin/menu/menu-search-mixin'
import IconSelect from '@/components/app/IconSelect'
// import treeSelect from '@riophae/vue-treeselect'
// import '@riophae/vue-treeselect/dist/vue-treeselect.css'

// import utils
import { validSort, validChineseLetter } from '@/utils/app/validator/common'
// import { filterAsyncRouter } from '@/utils/app/common'

// import api
import { apiGet, apiCreate, apiUpdate, apiDelete } from '@/api/app/admin/menu'

export default {
  name: 'AdminMenu',
  components: { TreeSelectE, SearchOptions, IconSelect },
  mixins: [searchOptionsConfig()],
  data() {
    return {
      query: {},

      tableLoading: false,
      tableData: [],

      treeData: [{
        id: '0',
        title: '一级标题',
        children: []
      }],

      dialogVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑',
        create: '新建'
      },
      formData: {
        id: '',
        type: '1',
        name: '',
        path: '',
        component: '',
        redirect: 'noRedirect',
        hidden: '0',
        alwaysShow: '0',
        title: '',
        icon: '',
        noCache: '1',
        breadcrumb: '1',
        roles: '',
        sort: '1',
        pid: '0'
      },
      rules: {
        sort: [{ required: true, validator: validSort, trigger: 'change' }],
        title: [{ required: true, validator: validChineseLetter, trigger: 'change' }]
        // name: [{ required: true, validator: validName, trigger: 'change' }],
        // path: [{ required: true, validator: validPath, trigger: 'change' }],
        // permission: [{ validator: validName, trigger: 'change' }],
        // component_name: [{ validator: validName, trigger: 'change' }],
        // component: [{ validator: validName, trigger: 'change' }]
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
          this.tableData = data.menu.slice(0)
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
      apiGet({ req: 'id_title_pid' })
        .then(function(data) {
          this.treeData[0].children.splice(0)
          this.treeData[0].children = data.menu_list.slice(0)
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
      Promise.all([apiGet({ req: 'id_title_pid' }), apiGet({ id: id })])
        .then(function(res) {
          this.treeData[0].children.splice(0)
          this.treeData[0].children = res[0].menu_list.slice(0)
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
    selected(name) {
      this.formData.icon = name
    },
    rstFormData() {
      this.formData.id = ''
      this.formData.type = '1'
      this.formData.name = ''
      this.formData.path = ''
      this.formData.component = ''
      this.formData.redirect = 'noRedirect'
      this.formData.hidden = '0'
      this.formData.alwaysShow = '0'
      this.formData.title = ''
      this.formData.icon = ''
      this.formData.noCache = '1'
      this.formData.breadcrumb = '1'
      this.formData.roles = ''
      this.formData.sort = '1'
      this.formData.pid = '0'
    },
    updateFormData(form) {
      this.formData.id = form.id
      this.formData.type = form.type
      this.formData.name = form.name
      this.formData.path = form.path
      this.formData.component = form.component
      this.formData.redirect = form.redirect
      this.formData.hidden = form.hidden
      this.formData.alwaysShow = form.alwaysShow
      this.formData.title = form.title
      this.formData.icon = form.icon
      this.formData.noCache = form.noCache
      this.formData.breadcrumb = form.breadcrumb
      this.formData.roles = form.roles
      this.formData.sort = form.sort
      this.formData.pid = form.pid
    },

    cancelDialog() {
      this.dialogAction = ''
      this.dialogVisible = false
      // this.rstFormData()
    },

    handleSearch(search) {
      this.query = JSON.parse(JSON.stringify(search))
      this.refreshTblDisplay(this.query)
    },
    searchChange(search) {
      this.query = JSON.parse(JSON.stringify(search))
      this.refreshTblDisplay(this.query)
    },

    buildMenu() {
      console.log('Debug formData.pid: ')
      console.log(this.formData.pid)
      // apiGet({ req: 'build_menu' })
      //   .then(function(data) {
      //     var menu = data.menu.slice(0)
      //     const asyncRouter = filterAsyncRouter(menu)
      //     console.log('# build menus: ')
      //     console.log(asyncRouter)
      //     //
      //   })
      //   .catch(function(err) {
      //     this.tableData.splice(0)
      //     this.$message({
      //       message: err,
      //       type: 'warning'
      //     })
      //   }.bind(this))
    }
  }
}
</script>
