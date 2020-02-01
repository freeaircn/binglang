<!--
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 18:31:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 20:47:22
 -->
<template>
  <div class="search-options">
    <el-form ref="form" :model="query" :rules="rules" :inline="true" size="mini">
      <el-form-item
        v-for="(item, index) in inputs"
        :key="item.prop+'_'+index"
        :prop="item.prop"
      >
        <el-tooltip effect="dark" :content="item.tooltip" placement="top">
          <el-input v-model="query[item.prop]" :placeholder="item.placeholder" :maxlength="item.maxlength" :style="{width:item.width+'px'}" @change="inputChange" />
        </el-tooltip>
      </el-form-item>

      <el-form-item
        v-for="(item, index) in selects"
        :key="item.prop+'_'+index"
        :prop="item.prop"
      >
        <el-tooltip effect="dark" :content="item.tooltip" placement="top">
          <el-select v-model="query[item.prop]" :placeholder="item.placeholder" :style="{width:item.width+'px'}" @change="selectChange">
            <el-option
              v-for="(option, key) in item.options"
              :key="item.prop+'_'+index+'_'+key"
              :label="option.label"
              :value="option.value"
            />
          </el-select>
        </el-tooltip>
      </el-form-item>

      <el-form-item>
        <el-tooltip effect="dark" content="各字段按“与”组合查询" placement="top">
          <el-button type="primary" icon="el-icon-search" @click="handleQuery">查询</el-button>
        </el-tooltip>
        <el-button type="primary" plain icon="el-icon-refresh-left" @click.prevent="resetForm">清空</el-button>
      </el-form-item>

    </el-form>
  </div>
</template>

<script>
export default {
  name: 'SearchOptions',
  props: {
    inputs: {
      type: Array,
      default: () => { return [] }
    },
    selects: {
      type: Array,
      default: () => { return [] }
    },
    rules: {
      type: Array,
      default: () => { return [] }
    }
    // buttons: {
    //   type: Array,
    //   default: () => { return [] }
    // }
  },
  data() {
    return {
      query: {}
    }
  },
  created: function() {
    for (var i in this.inputs) {
      this.$set(this.query, this.inputs[i].prop, '')
    }
    for (var k in this.selects) {
      this.$set(this.query, this.selects[k].prop, '')
    }
  },
  mounted: function() {
    this.$emit('change', this.query)
  },
  methods: {
    inputChange() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.$emit('change', this.query)
        }
      })
    },
    // inputClear() {
    //   this.$refs['form'].validate((valid) => {
    //     if (valid) {
    //       this.$emit('change', this.query)
    //     }
    //   })
    // },
    selectChange() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.$emit('change', this.query)
        }
      })
    },
    // selectClear() {
    //   this.$refs['form'].validate((valid) => {
    //     if (valid) {
    //       this.$emit('change', this.query)
    //     }
    //   })
    // },

    handleQuery() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.$emit('change', this.query)
        }
      })
    },
    resetForm() {
      this.$refs['form'].resetFields()
      this.$emit('change', this.query)
    }
  }
}
</script>

<style scoped>
</style>
