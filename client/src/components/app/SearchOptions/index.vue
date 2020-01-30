<!--
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 18:31:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 00:54:37
 -->
<template>
  <div class="search-options">
    <el-form ref="form" :model="query" :rules="rules" :inline="true" size="mini" label-width="80px">
      <el-form-item
        v-for="(item, index) in inputs"
        :key="item.prop+'_'+index"
        :prop="item.prop"
      >
        <el-tooltip effect="dark" :content="item.tooltip" placement="top">
          <el-input v-model="query[item.prop]" clearable :placeholder="item.placeholder" />
        </el-tooltip>
      </el-form-item>

      <el-form-item
        v-for="(item, index) in selects"
        :key="item.prop+'_'+index"
        :prop="item.prop"
      >
        <el-tooltip effect="dark" :content="item.tooltip" placement="top">
          <el-select v-model="query[item.prop]" clearable :placeholder="item.placeholder" style="width:80px;">
            <el-option
              v-for="(option, key) in item.options"
              :key="item.prop+'_'+index+'_'+key"
              :label="option.label"
              :value="option.value"
            />
          </el-select>
        </el-tooltip>
      </el-form-item>

      <el-form-item
        v-for="(item, index) in buttons"
        :key="'btn_'+index"
      >
        <el-tooltip effect="dark" :content="item.tooltip" placement="top">
          <el-button :type="item.type" :icon="item.icon" @click="handleQuery">{{ item.label }}</el-button>
        </el-tooltip>
        <el-button type="info" icon="el-icon-refresh-left" @click.prevent="resetForm">重置</el-button>
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
    buttons: {
      type: Array,
      default: () => { return [] }
    }
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
  methods: {
    handleQuery() {
      this.$emit('search-event', this.query)
    },
    resetForm() {
      this.$refs['form'].resetFields()
    }

  }
}
</script>

<style scoped>
</style>
