<!--
 * @Description: tree-select component based element-ui
 * @Author: freeair
 * @Date: 2020-02-08 20:15:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-08 23:16:49
 -->
<template>
  <div>
    <div v-show="isShowSelect" class="mask" @click="isShowSelect = !isShowSelect" />
    <el-popover
      v-model="isShowSelect"
      placement="bottom-start"
      :width="width"
      trigger="manual"
    >
      <el-tree
        ref="tree"
        class="common-tree"
        :style="style"
        :data="data"
        :props="defaultProps"
        :show-checkbox="multiple"
        :node-key="nodeKey"
        :check-strictly="checkStrictly"
        default-expand-all
        :expand-on-click-node="false"
        :check-on-click-node="multiple"
        :highlight-current="true"
        @node-click="handleNodeClick"
        @check-change="handleCheckChange"
      />
      <el-select
        slot="reference"
        ref="select"
        v-model="selectedData"
        :style="selectStyle"
        :size="size"
        :multiple="multiple"
        :clearable="clearable"
        :collapse-tags="collapseTags"
        :placeholder="placeholder"
        class="tree-select"
        @click.native="isShowSelect = !isShowSelect"
        @remove-tag="removeSelectedNodes"
        @clear="removeSelectedNode"
        @change="changeSelectedNodes"
      >
        <el-option v-for="item in options" :key="item.value" :label="item.label" :value="item.value" />
      </el-select>
    </el-popover>
  </div>
</template>

<script>
export default {
  name: 'TreeSelectE',
  props: {
    selected: [String, Array],
    // 树结构数据
    data: {
      type: Array,
      default() {
        return []
      }
    },
    nodeKey: {
      type: String,
      default() {
        return 'id'
      }
    },
    defaultProps: {
      type: Object,
      default() {
        return { children: 'children', label: 'label' }
      }
    },
    // 配置是否可多选
    multiple: {
      type: Boolean,
      default() {
        return false
      }
    },
    // 配置是否可清空选择
    clearable: {
      type: Boolean,
      default() {
        return false
      }
    },
    // 配置多选时是否将选中值按文字的形式展示
    collapseTags: {
      type: Boolean,
      default() {
        return false
      }
    },
    // 显示复选框情况下，是否严格遵循父子不互相关联
    checkStrictly: {
      type: Boolean,
      default() {
        return false
      }
    },
    // 默认选中的节点key数组
    checkedKeys: {
      type: Array,
      default() {
        return []
      }
    },
    placeholder: {
      type: String,
      default() {
        return '请选择'
      }
    },
    size: {
      type: String,
      default() {
        return 'small'
      }
    },
    width: {
      type: Number,
      default() {
        return 180
      }
    },
    height: {
      type: Number,
      default() {
        return 300
      }
    }
  },
  data() {
    return {
      isShowSelect: false, // 是否显示树状选择器
      options: [],
      selectedData: [], // 选中的节点
      // style: 'width:' + this.width + 'px;' + 'height:' + this.height + 'px;',
      style: 'width:' + this.width + 'px;',
      selectStyle: 'width:' + (this.width) + 'px;',
      checkedIds: [],
      checkedData: []
    }
  },
  watch: {
    selectedData(val) {
      if (this.multiple) {
        this.$emit('update:selected', this.selectedData)
      } else {
        this.$emit('update:selected', this.selectedData[0])
      }
      console.log('watch selectedData # ')
      console.log(this.selected)
    }
  },
  mounted() {
    this.initCheckedData()
    console.log('Comp Init # ')
    console.log(this.selectedData)
  },
  methods: {
    initCheckedData() {
      if (this.multiple) {
        // 多选
        if (this.checkedKeys.length > 0) {
          this.checkSelectedNodes(this.checkedKeys)
        } else {
          this.clearSelectedNodes()
        }
      } else {
        // 单选
        if (this.checkedKeys.length > 0) {
          this.checkSelectedNode(this.checkedKeys)
        } else {
          this.clearSelectedNode()
        }
      }
    },
    // 单选，选中传进来的节点
    checkSelectedNode(checkedKeys) {
      var item = checkedKeys[0]
      this.$refs.tree.setCurrentKey(item)
      var node = this.$refs.tree.getNode(item)
      this.setSelectOption(node)
    },
    // 多选，勾选上传进来的节点
    checkSelectedNodes(checkedKeys) {
      this.$refs.tree.setCheckedKeys(checkedKeys)
    },
    // 单选，清空选中
    clearSelectedNode() {
      this.selectedData = []
      this.$refs.tree.setCurrentKey(null)
    },
    // 多选，清空所有勾选
    clearSelectedNodes() {
      var checkedKeys = this.$refs.tree.getCheckedKeys() // 所有被选中的节点的 key 所组成的数组数据
      for (let i = 0; i < checkedKeys.length; i++) {
        this.$refs.tree.setChecked(checkedKeys[i], false)
      }
    },

    // 单选，节点被点击时的回调,返回被点击的节点数据
    handleNodeClick(data, node) {
      if (!this.multiple) {
        this.setSelectOption(node)
        this.isShowSelect = !this.isShowSelect
        this.$emit('change', this.selectedData)
      }
      console.log('el-tree event: node-click # ')
      console.log(this.selectedData)
    },
    // 单选时点击tree节点，设置select选项
    setSelectOption(node) {
      const tmpMap = {}
      tmpMap.value = node.key
      tmpMap.label = node.label
      this.options = []
      this.options.push(tmpMap)
      this.selectedData = [node.key]
    },

    // 多选，节点勾选状态发生变化时的回调
    handleCheckChange() {
      console.log('el-tree event: check-change # ')
      var checkedKeys = this.$refs.tree.getCheckedKeys() // 所有被选中的节点的 key 所组成的数组数据
      this.options = checkedKeys.map((item) => {
        var node = this.$refs.tree.getNode(item) // 所有被选中的节点对应的node
        const tmpMap = {}
        tmpMap.value = node.key
        tmpMap.label = node.label
        return tmpMap
      })
      this.selectedData = this.options.map((item) => {
        return item.value
      })
      this.$emit('change', this.selectedData)
    },

    // 单选,清空select输入框的回调
    removeSelectedNode() {
      this.clearSelectedNode()
      this.$emit('change', this.selectedData)
      console.log('el-select event: clear # ')
      console.log(this.selectedData)
    },

    // 选中的select选项改变的回调
    changeSelectedNodes(selectedData) {
      // 多选,清空select输入框时，清除树勾选
      if (this.multiple && selectedData.length <= 0) {
        this.clearSelectedNodes()
      }
      this.$emit('change', this.selectedData)
      console.log('el-select event: change # ')
      console.log(this.selectedData)
    },

    // 多选,删除任一select选项的回调
    removeSelectedNodes(val) {
      this.$refs.tree.setChecked(val, false)
      var node = this.$refs.tree.getNode(val)
      if (!this.checkStrictly && node.childNodes.length > 0) {
        this.treeToList(node).map(item => {
          if (item.childNodes.length <= 0) {
            this.$refs.tree.setChecked(item, false)
          }
        })
        this.handleCheckChange()
      }
      this.$emit('change', this.selectedData)
    },

    treeToList(tree) {
      var queen = []
      var out = []
      queen = queen.concat(tree)
      while (queen.length) {
        var first = queen.shift()
        if (first.childNodes) {
          queen = queen.concat(first.childNodes)
        }
        out.push(first)
      }
      return out
    }

  }
}
</script>

<style scoped>
  .mask{
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    opacity: 0;
    z-index: 11;
  }
  .common-tree{
    overflow: auto;
  }
  .tree-select{
    z-index: 111;
  }
</style>
