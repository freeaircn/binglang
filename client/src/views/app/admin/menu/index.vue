<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <div>
        <!-- 搜索 -->
        <el-input v-model="query.words" clearable size="small" placeholder="搜索菜单名" style="width: 200px;" class="filter-item" @keyup.enter.native="handleQuery" />
        <el-button class="filter-item" size="mini" type="success" icon="el-icon-search" @click="handleQuery">查询</el-button>
        <el-button class="filter-item" size="mini" type="primary" icon="el-icon-plus" @click="preCreate">新增</el-button>
        <el-button class="filter-item" size="mini" type="info" icon="el-icon-download" @click="handleExport">导出</el-button>
      </div>
    </div>
    <el-divider><i class="el-icon-arrow-down" /></el-divider>
    <!--表格渲染-->
    <el-table
      ref="table"
      v-loading="tableLoading"
      :data="tableData"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
      row-key="id"
      size="small"
    >
      <el-table-column :show-overflow-tooltip="true" prop="title" label="标题" width="125px" />
      <el-table-column prop="sort" label="排序" align="center">
        <template slot-scope="scope">
          {{ scope.row.sort }}
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="path" label="url地址" />
      <el-table-column :show-overflow-tooltip="true" prop="name" label="组件名" />
      <el-table-column :show-overflow-tooltip="true" prop="component" label="组件路径" />
      <el-table-column :show-overflow-tooltip="true" prop="redirect" label="重定向" />

      <el-table-column prop="hidden" label="侧边可见" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.hidden">否</span>
          <span v-else>是</span>
        </template>
      </el-table-column>
      <el-table-column prop="alwaysShow" label="顶级可见" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.alwaysShow">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="noCache" label="页面缓存" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.noCache">否</span>
          <span v-else>是</span>
        </template>
      </el-table-column>
      <el-table-column prop="breadcrumb" label="面包屑" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.breadcrumb">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>

      <el-table-column :show-overflow-tooltip="true" prop="roles" label="权限标识" />
      <el-table-column prop="create_time" label="创建日期" width="135px" />

      <el-table-column label="操作" width="130px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="preUpdate(row)" />
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
              <el-radio-group v-model="formData.type" size="mini" style="">
                <el-radio-button label="1">菜单</el-radio-button>
                <el-radio-button label="2">按钮</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item label="标题" prop="title">
              <el-input v-model="formData.title" style="" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-if="formData.type.toString() === '1'" label="url地址" prop="path">
              <el-input v-model="formData.path" placeholder="路由地址" style="" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="重定向" prop="redirect">
              <el-input v-model="formData.redirect" style="" placeholder="重定向地址" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="组件名称" prop="name">
              <el-input v-model="formData.name" style="" placeholder="路由/组件Name" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="组件路径" prop="component">
              <el-input v-model="formData.component" style="" placeholder="组件路径" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="侧边可见" prop="hidden">
              <el-radio-group v-model="formData.hidden" size="mini">
                <el-radio-button label="false">是</el-radio-button>
                <el-radio-button label="true">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="顶级可见" prop="alwaysShow">
              <el-radio-group v-model="formData.alwaysShow" size="mini">
                <el-radio-button label="true">是</el-radio-button>
                <el-radio-button label="false">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="页面缓存" prop="noCache">
              <el-radio-group v-model="formData.noCache" size="mini">
                <el-radio-button label="false">是</el-radio-button>
                <el-radio-button label="true">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item v-show="formData.type.toString() === '1'" label="面包屑" prop="breadcrumb">
              <el-radio-group v-model="formData.breadcrumb" size="mini">
                <el-radio-button label="true">是</el-radio-button>
                <el-radio-button label="false">否</el-radio-button>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>

        <el-row>
          <el-col :span="12">
            <el-form-item label="权限标识" prop="roles">
              <el-input v-model="formData.roles" placeholder="xx:list" style="" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="排序" prop="sort">
              <el-input-number v-model.number="formData.sort" :min="0" :max="999" controls-position="right" style="" />
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item v-show="formData.type.toString() === '1'" label="图标" prop="icon">
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

        <el-form-item label="上级类目" prop="pid">
          <treeSelect v-model="formData.pid" :options="treeData" style="width: 450px;" placeholder="选择上级类目" />
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
import axios from 'axios'
import IconSelect from '@/components/app/IconSelect'
import treeSelect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

// import 公共method
import { validQueryWords } from '@/utils/app/validator/common'

// import api
import { apiGetMenu, apiCreateMenu, apiUpdateMenu, apiDelMenu } from '@/api/app/admin/menu'

// Import validator
// import { validName, validPath } from '@/utils/app/validator/menu-form'

export default {
  name: 'AdminMenu',
  components: { IconSelect, treeSelect },
  data() {
    return {
      query: {
        words: ''
      },

      tableLoading: false,
      tableData: [],
      treeData: [{
        id: 0,
        label: '顶级类目',
        children: []
      }],

      dialogVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑',
        create: '新建'
      },
      formData: {
        id: null,
        type: 1,
        name: null,
        path: null,
        component: null,
        redirect: null,
        hidden: false,
        alwaysShow: false,
        title: null,
        icon: null,
        noCache: true,
        breadcrumb: true,
        roles: null,
        sort: 999,
        pid: 0,
        create_time: null
      }
      // rules: {
      //   name: [{ required: true, validator: validName, trigger: 'change' }],
      //   path: [{ required: true, validator: validPath, trigger: 'change' }],
      //   permission: [{ validator: validName, trigger: 'change' }],
      //   component_name: [{ validator: validName, trigger: 'change' }],
      //   component: [{ validator: validName, trigger: 'change' }]
      // }
    }
  },
  created() {
    this.doRead()
  },
  methods: {
    // CRUD core
    doRead(col = null, words = null) {
      this.tableLoading = true

      apiGetMenu(col, words)
        .then(function(data) {
          this.tableData.splice(0, this.tableData.length)
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
        this.doRead()
      } else {
        const res = validQueryWords(this.query.words)
        if (res === true) {
          this.doRead('title', this.query.words)
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
      apiGetMenu(null, null)
        .then(function(data) {
          this.treeData[0].children.splice(0, this.treeData[0].children.length)
          this.treeData[0].children = data.slice(0)
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
          console.log(this.formData)
          // API create
          apiCreateMenu(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.doRead()
            }.bind(this))
            .catch(function(err) {
              console.log(err)
            })
        }
      })
    },

    // 请求后台menu tree，组装tree数据结构
    // 取row.id，请求后台，填写表单formData
    // 显示dialog
    preUpdate(row) {
      axios.all([apiGetMenu(null, null), apiGetMenu('id', row.id)])
        .then(axios.spread(function(treeData, tableRow) {
          this.treeData[0].children.splice(0, this.treeData[0].children.length)
          this.treeData[0].children = treeData.slice(0)
          //
          this.copyFormData(tableRow[0])
          this.dialogAction = 'update'
          this.dialogVisible = true
          this.$nextTick(() => {
            this.$refs['form'].clearValidate()
          })
        }.bind(this)))
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
          apiUpdateMenu(tempData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.doRead()
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
      apiDelMenu(id)
        .then(function(data) {
          console.log(data)
        }).catch(function(err) {
          console.log(err)
        })
    },

    // 其他
    selected(name) {
      this.formData.icon = name
    },
    handleExport() {

    },
    rstFormData() {
      this.formData.id = null
      this.formData.type = 1
      this.formData.name = null
      this.formData.path = null
      this.formData.component = null
      this.formData.redirect = null
      this.formData.hidden = false
      this.formData.alwaysShow = false
      this.formData.title = null
      this.formData.icon = null
      this.formData.noCache = true
      this.formData.breadcrumb = true
      this.formData.roles = null
      this.formData.sort = 999
      this.formData.pid = 0
      this.formData.create_time = null
    },
    copyFormData(data) {
      this.formData.id = data.id
      this.formData.type = data.type
      this.formData.name = data.name
      this.formData.path = data.path
      this.formData.component = data.component
      this.formData.redirect = data.redirect
      this.formData.hidden = data.hidden
      this.formData.alwaysShow = data.alwaysShow
      this.formData.title = data.title
      this.formData.icon = data.icon
      this.formData.noCache = data.noCache
      this.formData.breadcrumb = data.breadcrumb
      this.formData.roles = data.roles
      this.formData.sort = data.sort
      this.formData.pid = data.pid
      this.formData.create_time = data.create_time
    }
  }
}
</script>
