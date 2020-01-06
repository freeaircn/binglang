<template>
  <div class="app-container">
    <!--工具栏-->
    <div class="head-container">
      <div>
        <!-- 搜索 -->
        <el-input v-model="query.words" clearable size="small" placeholder="搜索菜单名" style="width: 200px;" class="filter-item" @keyup.enter.native="handleQuery" />
        <el-button class="filter-item" size="mini" type="success" icon="el-icon-search" @click="handleQuery">查询</el-button>
        <el-button class="filter-item" size="mini" type="primary" icon="el-icon-plus" @click="preCreateRow">新增</el-button>
        <el-button class="filter-item" size="mini" type="info" icon="el-icon-download" @click="handleExport">导出</el-button>
      </div>
    </div>
    <el-divider><i class="el-icon-download">API测试区</i></el-divider>
    <div>
      <el-button class="filter-item" size="mini" type="primary" @click="handleGet">Get</el-button>
      <el-button class="filter-item" size="mini" type="primary" @click="handlePost">Post</el-button>
      <el-button class="filter-item" size="mini" type="primary" @click="handleUpdate">Update</el-button>
      <el-button class="filter-item" size="mini" type="primary" @click="handleDel">Delete</el-button>

    </div>
    <!--表格渲染-->
    <el-table
      ref="table"
      v-loading="tableLoading"
      :data="tableData"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
      row-key="id"
      size="small"
    >
      <el-table-column :show-overflow-tooltip="true" prop="name" label="菜单名" width="125px" />
      <el-table-column prop="icon" label="图标" align="center" width="60px">
        <template slot-scope="scope">
          <svg-icon :icon-class="scope.row.icon" />
        </template>
      </el-table-column>
      <el-table-column prop="sort" label="排序" align="center">
        <template slot-scope="scope">
          {{ scope.row.sort }}
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="path" label="路由地址" />
      <el-table-column :show-overflow-tooltip="true" prop="permission" label="权限标识" />
      <el-table-column :show-overflow-tooltip="true" prop="component" label="组件路径" />
      <el-table-column prop="outlink" label="外部链接" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.outlink">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="cache" label="缓存" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.cache">是</span>
          <span v-else>否</span>
        </template>
      </el-table-column>
      <el-table-column prop="hidden" label="可见" width="75px">
        <template slot-scope="scope">
          <span v-if="scope.row.hidden">否</span>
          <span v-else>是</span>
        </template>
      </el-table-column>
      <el-table-column prop="create_time" label="创建日期" width="135px">
        <template slot-scope="scope">
          <span>{{ scope.row.create_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" width="130px" align="center" fixed="right">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="preUpdateRow(row)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="doDelRow(row)" />
        </template>
      </el-table-column>
    </el-table>

    <!--表单渲染-->
    <el-dialog append-to-body :close-on-click-modal="false" :visible.sync="dialogVisible" :title="dialogActionMap[dialogAction]" width="580px">
      <el-form ref="form" :inline="true" :model="formData" :rules="rules" size="small" label-width="80px">
        <el-form-item label="菜单类型" prop="type">
          <el-radio-group v-model="formData.type" size="mini" style="width: 178px">
            <el-radio-button label="0">目录</el-radio-button>
            <el-radio-button label="1">子菜单</el-radio-button>
            <el-radio-button label="2">按钮</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-show="formData.type.toString() !== '2'" label="菜单图标" prop="icon">
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
        <el-form-item v-show="formData.type.toString() !== '2'" label="外部链接" prop="outlink">
          <el-radio-group v-model="formData.outlink" size="mini">
            <el-radio-button label="true">是</el-radio-button>
            <el-radio-button label="false">否</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-show="formData.type.toString() === '1'" label="菜单缓存" prop="cache">
          <el-radio-group v-model="formData.cache" size="mini">
            <el-radio-button label="true">是</el-radio-button>
            <el-radio-button label="false">否</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-show="formData.type.toString() !== '2'" label="菜单可见" prop="hidden">
          <el-radio-group v-model="formData.hidden" size="mini">
            <el-radio-button label="false">是</el-radio-button>
            <el-radio-button label="true">否</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-show="formData.type.toString() !== '2'" label="菜单标题" prop="name">
          <el-input v-model="formData.name" :style=" formData.type.toString() === '0' ? 'width: 450px' : 'width: 178px'" placeholder="菜单标题" />
        </el-form-item>
        <el-form-item v-show="formData.type.toString() === '2'" label="按钮名称" prop="name">
          <el-input v-model="formData.name" placeholder="按钮名称" style="width: 178px;" />
        </el-form-item>
        <el-form-item v-show="formData.type.toString() !== '0'" label="权限标识" prop="permission">
          <el-input v-model="formData.permission" :disabled="formData.outlink" placeholder="权限标识" style="width: 178px;" />
        </el-form-item>
        <el-form-item v-if="formData.type.toString() !== '2'" label="路由地址" prop="path">
          <el-input v-model="formData.path" placeholder="路由地址" style="width: 178px;" />
        </el-form-item>
        <el-form-item label="菜单排序" prop="sort">
          <el-input-number v-model.number="formData.sort" :min="0" :max="999" controls-position="right" style="width: 178px;" />
        </el-form-item>
        <el-form-item v-show="!formData.outlink && formData.type.toString() === '1'" label="组件名称" prop="component_name">
          <el-input v-model="formData.component_name" style="width: 178px;" placeholder="匹配组件内Name字段" />
        </el-form-item>
        <el-form-item v-show="!formData.outlink && formData.type.toString() === '1'" label="组件路径" prop="component">
          <el-input v-model="formData.component" style="width: 178px;" placeholder="组件路径" />
        </el-form-item>
        <el-form-item label="上级类目" prop="pid">
          <treeSelect v-model="formData.pid" :options="treeData" style="width: 450px;" placeholder="选择上级类目" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="dialogAction==='create'?doCreateRow():doUpdateRow()">提交</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
// import 第三方组件
import IconSelect from '@/components/app/IconSelect'
import treeSelect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

// import 公共method
import { validQueryWords } from '@/utils/app/validator/common'

// import api
import { apiGetMenu, apiCreateMenu, apiUpdateMenu, apiDelMenu } from '@/api/app/admin/menu'

// Import validator
import { validName, validPath } from '@/utils/app/validator/menu_form'

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
        type: 0,
        icon: null,
        outlink: false,
        cache: false,
        hidden: false,
        name: null,
        permission: null,
        path: null,
        sort: 999,
        component_name: null,
        component: null,
        pid: 0,
        create_time: null
      },
      rules: {
        name: [{ required: true, validator: validName, trigger: 'change' }],
        path: [{ required: true, validator: validPath, trigger: 'change' }],
        permission: [{ validator: validName, trigger: 'change' }],
        component_name: [{ validator: validName, trigger: 'change' }],
        component: [{ validator: validName, trigger: 'change' }]
      }

      // 测试数据
      // tableData: [{
      //   id: 1,
      //   outlink: false,
      //   name: '系统管理',
      //   component: null,
      //   pid: 0,
      //   sort: 1,
      //   icon: 'system',
      //   path: 'system',
      //   cache: false,
      //   hidden: false,
      //   component_name: null,
      //   create_time: '',
      //   permission: null,
      //   type: 0,
      //   children: [{
      //     id: 5,
      //     outlink: false,
      //     name: '菜单管理',
      //     component: 'system/menu/index',
      //     pid: 1,
      //     sort: 5,
      //     icon: 'menu',
      //     path: 'menu',
      //     cache: false,
      //     hidden: false,
      //     component_name: 'Menu',
      //     create_time: '',
      //     permission: 'menu:list',
      //     type: 1
      //   }]
      // }],
      // treeData: [{
      //   id: 0,
      //   label: '顶级类目',
      //   children: [{
      //     id: 1,
      //     label: '系统管理',
      //     children: [{
      //       id: 5,
      //       label: '菜单管理'
      //     }]
      //   }]
      // }]
    }
  },
  created() {
    this.queryRow()
  },
  methods: {
    // CRUD core
    queryRow(words = null) {
      this.tableLoading = true

      apiGetMenu(words)
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
        this.queryRow()
      } else {
        const res = validQueryWords(this.query.words)
        if (res === true) {
          this.queryRow(this.query.words)
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
    preCreateRow() {
      this.rstFormData()
      apiGetMenu(null)
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
      // this.dialogAction = 'create'
      // this.dialogVisible = true
      // this.$nextTick(() => {
      //   this.$refs['form'].clearValidate()
      // })
    },
    // validate 表单输入，请求后台
    // 接收response
    // 200，获取整个数据表，更新显示
    doCreateRow() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          console.log(this.formData)
          // TODO: API create
          apiCreateMenu(this.formData)
            .then(function(data) {
              this.dialogAction = ''
              this.dialogVisible = false
              this.queryRow()
            }.bind(this))
            .catch(function(err) {
              console.log(err)
            })
        }
      })
    },
    // 请求后台menu tree，组装tree数据结构
    // 取row 填写表单formData
    // 显示dialog
    preUpdateRow(row) {
      this.dialogVisible = true

      this.formData = Object.assign({}, row) // copy obj
      console.log(this.formData)
      this.dialogAction = 'update'
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
    },
    // validate 表单输入，请求后台
    // 接收response，更新显示
    doUpdateRow() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.formData)
          console.log(tempData)

          // TODO: API update
          const id = 301
          apiUpdateMenu(id)
            .then(function(data) {
              console.log(data)
            }).catch(function(err) {
              console.log(err)
            })

          this.dialogAction = ''
          this.dialogFormVisible = false
          this.$notify({
            title: 'Success',
            message: '更新成功！',
            type: 'success',
            duration: 2000
          })
        }
      })
    },
    doDelRow() {
      const id = 401
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
      this.formData = {
        id: null,
        type: 0,
        icon: null,
        outlink: false,
        cache: false,
        hidden: false,
        name: null,
        permission: null,
        path: null,
        sort: 999,
        component_name: null,
        component: null,
        pid: 0,
        create_time: null
      }
    },

    // 测试API
    handleGet() {
      const msg = 'Method: Get'
      apiGetMenu(msg)
        .then(function(data) {
          console.log('Get response received')
        }).catch(function(err) {
          console.log(err)
        })
    },
    handlePost() {
      const msg = 'Method: Post'
      apiCreateMenu(msg)
        .then(function(data) {
          console.log('Post response received')
        }).catch(function(err) {
          console.log(err)
        })
    },
    handleUpdate() {
      const msg = 'Method: Update'
      apiUpdateMenu(msg)
        .then(function(data) {
          console.log('Update response received')
        }).catch(function(err) {
          console.log(err)
        })
    },
    handleDel() {
      const msg = 'Method: Delete'
      apiDelMenu(msg)
        .then(function(data) {
          console.log('Delete response received')
        }).catch(function(err) {
          console.log(err)
        })
    }
  }
}
</script>
