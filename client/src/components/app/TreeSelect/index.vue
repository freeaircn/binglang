<!--
 * @Description: tree-select component based element-ui
 * @Author: freeair
 * @Date: 2020-02-08 20:15:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-09 13:46:03
 -->
<template>
  <el-select :value="valueId" :placeholder="placeholder">
    <el-option :value="valueId" :label="valueLabel">
      <el-tree
        id="tree-option"
        ref="selectTree"
        :data="options"
        :props="props"
        :node-key="nodeKey"
        @node-click="handleNodeClick"
      />
    </el-option>
  </el-select>
</template>

<script>

export default {
  name: 'TreeSelectE',
  props: {
    value: {
      type: String,
      default() {
        return ''
      }
    },
    /* 选项列表数据(树形结构的对象数组) */
    options: {
      type: Array,
      default() {
        return []
      }
    },

    /* 配置项 */
    nodeKey: {
      type: String,
      default() {
        return 'id'
      }
    },
    props: {
      type: Object,
      default() {
        return {
          label: 'label', // 显示名称
          children: 'children' // 子级字段名
        }
      }
    },
    /* 自动收起 */
    accordion: {
      type: Boolean,
      default() {
        return false
      }
    },
    placeholder: {
      type: String,
      default() {
        return '请选择'
      }
    }
  },
  data() {
    return {

    }
  },
  computed: {
    valueId: function() {
      return this.value
    },
    valueLabel: function() {
      return this.value === '' ? '' : this.$refs.selectTree.getNode(this.value).data[this.props.label]
    }
  },
  methods: {
    // 切换选项
    handleNodeClick(node) {
      this.$emit('update:value', node[this.nodeKey])
    }
  }
}
</script>

<style scoped>
  .el-scrollbar .el-scrollbar__view .el-select-dropdown__item{
    height: auto;
    max-height: 274px;
    padding: 0;
    overflow: hidden;
    overflow-y: auto;
  }
  .el-select-dropdown__item.selected{
    font-weight: normal;
  }
  ul li >>>.el-tree .el-tree-node__content{
    height:auto;
    padding: 0 20px;
  }
  .el-tree-node__label{
    font-weight: normal;
  }
  /* .el-tree >>>.is-current .el-tree-node__label{
    color: #409EFF;
    font-weight: 700;
  }
  .el-tree >>>.is-current .el-tree-node__children .el-tree-node__label{
    color:#606266;
    font-weight: normal;
  } */
</style>
