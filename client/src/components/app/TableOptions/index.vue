<!--
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-23 19:13:13
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-23 23:16:34
 -->
<template>
  <div class="table-opts">
    <el-button-group class="table-opts-right">
      <!-- <el-button
        size="mini"
        icon="el-icon-refresh"
        @click="crud.refresh()"
      /> -->
      <el-popover placement="bottom-end" width="150" trigger="click">
        <el-button slot="reference" size="mini" icon="el-icon-s-grid">
          <i class="fa fa-caret-down" aria-hidden="true" />
        </el-button>
        <el-checkbox v-model="allColumnsSelected" :indeterminate="allColumnsSelectedIndeterminate" @change="handleCheckAllChange">
          全选
        </el-checkbox>
        <el-checkbox
          v-for="item in tableColumns"
          :key="item.label"
          v-model="item.visible"
          @change="handleCheckedTableColumnsChange(item)"
        >
          {{ item.label }}
        </el-checkbox>
      </el-popover>
    </el-button-group>
  </div>
</template>

<script>

export default {
  props: { tableColumns: {
    type: Object,
    default: function() {
      return {}
    }
  }},
  data() {
    return {
      allColumnsSelected: true,
      allColumnsSelectedIndeterminate: false
    }
  },
  mounted() {
    console.log('#2')
    console.log(this.tableColumns)
  },
  methods: {
    handleCheckAllChange(val) {
      if (val === false) {
        this.allColumnsSelected = true
        return
      }
      for (const key in this.tableColumns) {
        this.tableColumns[key].visible = val
      }
      this.allColumnsSelected = val
      this.allColumnsSelectedIndeterminate = false
    },
    handleCheckedTableColumnsChange(item) {
      let totalCount = 0
      let selectedCount = 0
      for (const key in this.tableColumns) {
        ++totalCount
        selectedCount += this.tableColumns[key].visible ? 1 : 0
      }
      if (selectedCount === 0) {
        // this.crud.notify('请至少选择一列', CRUD.NOTIFICATION_TYPE.WARNING)
        this.$nextTick(function() {
          item.visible = true
        })
        return
      }
      this.allColumnsSelected = selectedCount === totalCount
      this.allColumnsSelectedIndeterminate = selectedCount !== totalCount && selectedCount !== 0
    }
  }
}
</script>

<style>
  .table-opts {
    padding: 6px 0;
    display: -webkit-flex;
    display: flex;
    align-items: center;
  }
  .table-opts .table-opts-right {
    margin-left: auto;
  }
</style>
