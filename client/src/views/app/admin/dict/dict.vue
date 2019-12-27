<template>
  <div>
    <div class="filter-container">
      <el-input v-model="queryDictCond.keyWord" placeholder="搜索字典" clearable size="small" style="width: 200px;" class="filter-item" @keyup.enter.native="eventQueryDict" />
      <el-button class="filter-item" type="success" size="mini" icon="el-icon-search" @click="eventQueryDict">
        搜索
      </el-button>
      <el-button class="filter-item" style="margin-left: 5px;" type="primary" size="mini" icon="el-icon-plus" @click="eventCreateDict">
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
      <el-table-column v-if="showInnerId" label="ID" prop="id" sortable="custom" width="80px" align="center">
        <template slot-scope="scope">
          <span style="color:red;">{{ scope.row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="字典名" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.label }}</span>
        </template>
      </el-table-column>
      <el-table-column label="启用状态" class-name="status-col">
        <template slot-scope="{row}">
          <span :style="row.status | statusColorFilter">{{ row.status | statusFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="操作" align="center" class-name="small-padding fixed-width">
        <template slot-scope="{row}">
          <el-button size="mini" type="primary" icon="el-icon-edit" @click="eventUpdateDict(row)" />
          <el-button size="mini" type="danger" icon="el-icon-delete" @click="eventDelDict(row,'deleted')" />
        </template>
      </el-table-column>
    </el-table>

    <el-pagination
      :page-sizes="[5, 10, 30, 50, 100]"
      :page-size="pageSize"
      :current-page="queryDictCond.pageId"
      layout="total, prev, pager, next, sizes"
      :total="total"
      @size-change="pageSizeChange"
      @current-change="pageIdChange"
    />

    <el-dialog :title="dialogActionMap[dialogAction]" :visible.sync="dialogFormVisible">
      <el-form ref="form" :rules="rules" :model="tempDict" label-position="right" label-width="100px">
        <el-form-item label="字典名" prop="label">
          <el-input v-model="tempDict.label" />
        </el-form-item>
        <el-form-item label="是否启用" prop="status">
          <el-radio-group v-model="tempDict.status">
            <el-radio label="1">启用</el-radio>
            <el-radio label="0">禁用</el-radio>
          </el-radio-group>
        </el-form-item>

        <el-form-item>
          <el-button @click="dialogFormVisible = false">取消</el-button>
          <el-button type="primary" @click="dialogAction==='create'?createDict():updateDict()">提交</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
// Import API

// Import validator
import { validQueryKey, validLabel } from '@/utils/app/validator/dict_form'

export default {
  name: 'Dict',
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
      dict_list: [
        { id: 1, label: '用户状态', status: '1' },
        { id: 2, label: '部门级别', status: '1' },
        { id: 3, label: 'c', status: '1' },
        { id: 4, label: 'd', status: '0' },
        { id: 5, label: 'e', status: '1' },
        { id: 6, label: 'f', status: '1' },
        { id: 7, label: 'g', status: '0' }
      ],
      tempDict: {
        id: 0,
        label: '',
        status: ''
      },

      total: 0,
      pageSize: 5,
      dictPageId: 1,

      queryDictCond: {
        pageId: 1,
        keyWord: ''
      },

      dialogFormVisible: false,
      dialogAction: '',
      dialogActionMap: {
        update: '编辑',
        create: '新建'
      },

      rules: {
        label: [{ required: true, validator: validLabel, trigger: 'change' }],
        status: [{ required: true, message: '请选择一项', trigger: 'change' }]
      }
    }
  },
  computed: {
    // showList计算属性通过slice方法计算表格当前应显示的数据
    showList() {
      if (this.dict_list !== null) {
        return this.dict_list.slice(
          (this.dictPageId - 1) * this.pageSize, this.dictPageId * this.pageSize)
      } else {
        return null
      }
    }
  },
  created() {
    this.getDictList()
  },
  methods: {
    getDictList() {
      this.listLoading = true
      // TODO: API read param - this.queryDictCond.keyWord，输入检验
      this.total = 7

      // Just to simulate the time of the request
      setTimeout(() => {
        this.listLoading = false
      }, 1.5 * 1000)
    },
    eventQueryDict() {
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
      // this.getDictList()
    },

    //
    pageSizeChange(val) {
      this.pageSize = val
    },
    pageIdChange(val) {
      this.dictPageId = val
    },
    // 新增Dict Type按钮事件
    eventCreateDict() {
      this.tempDict = {
        id: 0,
        label: '',
        status: ''
      }
      this.dialogAction = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
    },
    // 提交新增Dict Type
    createDict() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          console.log(this.tempDict)

          // TODO: API create
          this.dict_list.unshift(this.tempDict)
          this.dialogFormVisible = false
          this.dialogAction = ''
        }
      })
    },
    // 编辑Dict Type按钮事件
    eventUpdateDict(row) {
      this.tempDict = Object.assign({}, row) // copy obj
      this.dialogAction = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['form'].clearValidate()
      })
    },
    // 提交编辑Dict Type
    updateDict() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.tempDict)
          console.log(tempData)

          // TODO: API update
          for (const v of this.dict_list) {
            if (v.id === this.tempDict.id) {
              const index = this.dict_list.indexOf(v)
              this.dict_list.splice(index, 1, this.tempDict)
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
    // 删除Dict type按钮事件
    eventDelDict(row) {
      // TODO: API delete
    }
  }
}
</script>
