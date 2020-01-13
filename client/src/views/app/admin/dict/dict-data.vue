<template>
  <div>
    <div class="filter-container">
      <el-input v-model="queryDictItemCond.keyWord" placeholder="搜索词条" clearable size="small" style="width: 200px;" class="filter-item" @keyup.enter.native="eventQueryDictItem" />
      <el-button class="filter-item" type="success" size="mini" icon="el-icon-search" @click="eventQueryDictItem">
        搜索
      </el-button>
      <el-button class="filter-item" style="margin-left: 5px;" type="primary" size="mini" icon="el-icon-plus" @click="eventCreateDictItem">
        新增
      </el-button>
      <el-checkbox v-model="showInnerId" class="filter-item" style="margin-left:15px;" @change="tableKey=tableKey+1">内部ID</el-checkbox>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="showList"
      border
      size="small"
      style="width: 100%;"
    >
      <el-table-column label="序号" type="index" :index="1" width="80px" align="center" />
      <el-table-column v-if="showInnerId" label="ID" prop="innerId" sortable="custom" width="80px" align="center">
        <template slot-scope="scope">
          <span style="color:red;">{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="所属字典" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.dict }}</span>
        </template>
      </el-table-column>
      <el-table-column label="词条名" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.label }}</span>
        </template>
      </el-table-column>
      <el-table-column label="词条编码" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.code }}</span>
        </template>
      </el-table-column>
      <el-table-column label="父节点ID" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.pid }}</span>
        </template>
      </el-table-column>
      <el-table-column label="启用状态" class-name="status-col">
        <template slot-scope="{row}">
          <span :style="row.status | statusColorFilter">{{ row.status | statusFilter }}</span>
        </template>
      </el-table-column>

      <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="eventUpdateDictItem(row)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="eventDelDictItem(row,'deleted')" />
        </template>
      </el-table-column>
    </el-table>

    <el-pagination
      :page-sizes="[5, 10, 30, 50, 100]"
      :page-size="pageSize"
      :current-page="queryDictItemCond.pageId"
      layout="total, prev, pager, next, sizes"
      :total="total"
      @size-change="pageSizeChange"
      @current-change="pageIdChange"
    />

    <el-dialog :title="dialogActionMap[dialogAction]" :visible.sync="dialogFormVisible">
      <el-form ref="form" :rules="rules" :model="tempItem" label-position="right" label-width="100px">
        <el-form-item v-if="dialogAction === 'create'" label="所属字典" prop="dict">
          <el-select v-model="tempItem.dict" class="filter-item" placeholder="请选择">
            <el-option v-for="item in dictTypes" :key="item.key" :label="item.display_name" :value="item.key" />
          </el-select>
        </el-form-item>
        <el-form-item label="词条名" prop="label">
          <el-input v-model="tempItem.label" />
        </el-form-item>
        <el-form-item label="词条编码" prop="code">
          <el-input v-model="tempItem.code" />
        </el-form-item>
        <el-form-item label="父节点" prop="pid">
          <el-input v-model="tempItem.pid" />
        </el-form-item>
        <el-form-item label="是否启用" prop="status">
          <el-radio-group v-model="tempItem.status">
            <el-radio label="1">启用</el-radio>
            <el-radio label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>

        <el-form-item>
          <el-button @click="dialogFormVisible = false">取消</el-button>
          <el-button type="primary" @click="dialogAction==='create'?createDictItem():updateDictItem()">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>

  </div>
</template>

<script>
// Import API

// Import validator
import { validQueryKey, validLabel, validCode } from '@/utils/app/validator/dict-form'

export default {
  name: 'DictItem',
  filters: {
    statusFilter(status) {
      const statusMap = {
        '0': '禁用',
        '1': '启用'
      }
      return statusMap[status]
    },
    statusColorFilter(status) {
      const colorMap = {
        '0': 'color:#E6A23C',
        '1': 'color:#67C23A'
      }
      return colorMap[status]
    }
  },
  data() {
    return {
      showInnerId: false,
      tableKey: 0,
      listLoading: false,
      item_list: [
        { id: 1, dictId: 1, dict: '用户状态', label: '已激活', code: '1', pid: '', status: '1' },
        { id: 2, dictId: 1, dict: '用户状态', label: '未激活', code: '0', pid: '', status: '1' },
        { id: 3, dictId: 2, dict: '部门级别', label: '一级部门', code: '', pid: '', status: '1' }
      ],
      dictTypes: [
        { key: '1', display_name: '用户状态' },
        { key: '2', display_name: '部门级别' },
        { key: '3', display_name: 'Japan' },
        { key: '4', display_name: 'Eurozone' }
      ],
      tempItem: {
        id: 0,
        dictId: 0,
        dict: '',
        label: '',
        code: '',
        pid: '',
        status: ''
      },

      total: 0,
      pageSize: 5,
      pageId: 1,

      queryDictItemCond: {
        pageId: 1,
        keyWord: ''
      },

      dialogFormVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑词条',
        create: '新增词条'
      },

      rules: {
        label: [{ required: true, validator: validLabel, trigger: 'change' }],
        code: [{ required: false, validator: validCode, trigger: 'change' }],
        pid: [{ required: false, validator: validCode, trigger: 'change' }],
        status: [{ required: true, message: '请选择一项', trigger: 'change' }]
      }
    }
  },
  computed: {
    // showList计算属性通过slice方法计算表格当前应显示的数据
    showList() {
      if (this.item_list !== null) {
        return this.item_list.slice(
          (this.pageId - 1) * this.pageSize, this.pageId * this.pageSize)
      } else {
        return null
      }
    }
  },
  created() {
    this.getDictItemList()
  },
  methods: {
    getDictItemList() {
      this.listLoading = true
      // TODO: API read param - this.queryDictItemCond.keyWord，输入检验
      this.total = 3

      // Just to simulate the time of the request
      setTimeout(() => {
        this.listLoading = false
      }, 1.5 * 1000)
    },
    eventQueryDictItem() {
      if (validQueryKey(this.queryDictItemCond.keyWord)) {
        this.queryDictItemCond.pageId = 1
      } else {
        this.$notify.error({
          title: '错误',
          message: '只允许输入中文，数字，字母，长度2~20！',
          duration: 2000
        })
      }

      // TODO:
      // this.getDictItemList()
    },

    pageSizeChange(val) {
      this.pageSize = val
    },
    pageIdChange(val) {
      this.pageId = val
    },
    // 新增Dict Item按钮事件
    eventCreateDictItem() {
      this.tempItem = {
        id: 0,
        dictId: 0,
        dict: '',
        label: '',
        code: '',
        pid: '',
        status: ''
      }
      // TODO: 获取字典集合

      this.dialogAction = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
    },
    // 提交新增Dict Item
    createDictItem() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          console.log(this.tempItem)

          // TODO: API create
          this.item_list.unshift(this.tempItem)
          this.dialogFormVisible = false
          this.dialogAction = ''
        }
      })
    },
    // 编辑Dict Item按钮事件
    eventUpdateDictItem(row) {
      this.tempItem = Object.assign({}, row) // copy obj
      this.dialogAction = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
    },
    // 提交编辑Dict Item
    updateDictItem() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.tempItem)
          console.log(tempData)

          // TODO: API update
          for (const v of this.item_list) {
            if (v.id === this.tempItem.id) {
              const index = this.item_list.indexOf(v)
              this.item_list.splice(index, 1, this.tempItem)
              break
            }
          }
          this.dialogFormVisible = false
          this.dialogAction = ''
          this.$notify({
            title: 'Success',
            message: '更新成功！',
            type: 'success',
            duration: 2000
          })
        }
      })
    },
    // 删除Dict Item按钮事件
    eventDelDictItem(row) {
      // TODO: API delete
    }
  }
}
</script>
