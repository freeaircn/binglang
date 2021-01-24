<!--
 * @Description:
 * @Author: freeair
 * @Date: 2021-01-24 19:51:24
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-24 23:05:12
-->
<template>
  <div>
    <van-field
      readonly
      clickable
      :label="label"
      :value="text"
      placeholder="点击选择"
      @click="isShow = true"
    />
    <van-popup v-model="isShow" position="bottom">
      <van-picker
        show-toolbar
        :value-key="valueKey"
        :columns="list"
        @confirm="onConfirm"
        @cancel="isShow = false"
      />
    </van-popup>
  </div>
</template>

<script>
export default {
  name: 'AppVantSelectPop',
  props: {
    label: {
      type: String,
      default: () => { return '' }
    },
    value: {
      type: String,
      default: () => { return '' }
    },
    valueKey: {
      type: String,
      default: () => { return 'label' }
    },
    list: {
      type: Array,
      default: () => { return [] }
    }
  },
  data() {
    return {
      isShow: false
      // text: ''
    }
  },
  computed: {
    text: function() {
      let temp = ''
      for (const i in this.list) {
        if (this.list[i].id === this.value) {
          temp = this.list[i].label
        }
      }
      return temp
    }
  },
  methods: {
    onConfirm(data) {
      this.$emit('update:value', data.id)
      this.isShow = false
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
