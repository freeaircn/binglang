<!--
 * @Description: tree-select component based element-ui
 * @Author: freeair
 * @Date: 2020-02-08 20:15:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-09 23:00:32
 -->
<template>
  <el-select ref="select" :value="valueId" :placeholder="placeholder" @visible-change="handleVisible">
    <el-option :value="valueId" :label="valueLabel">
      <el-tree
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
  name: 'TreeSelect',
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
      valueId: this.value,
      valueLabel: ''
    }
  },
  watch: {
    value: function(val) {
      this.$nextTick(() => {
        if (this.$refs['selectTree'].getNode(val)) {
          this.valueLabel = this.$refs['selectTree'].getNode(val).data[this.props.label]
          this.valueId = val
        } else {
          this.valueLabel = ''
          this.valueId = ''
        }
      })
    }
  },
  mounted() {
    if (this.$refs['selectTree'].getNode(this.valueId)) {
      this.valueLabel = this.$refs['selectTree'].getNode(this.valueId).data[this.props.label]
    } else {
      this.valueLabel = ''
    }
  },
  methods: {
    // 切换选项
    handleNodeClick(node) {
      this.$emit('update:value', node[this.nodeKey])
      this.$refs['select'].blur()
    }
  }
}
</script>

<style scoped>
  .el-select-dropdown__item{
    padding: 0;
    height: auto;
    overflow: hidden;
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
