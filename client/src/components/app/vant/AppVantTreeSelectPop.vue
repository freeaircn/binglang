<!--
 * @Description:
 * @Author: freeair
 * @Date: 2021-01-24 19:51:24
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-25 00:55:48
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
    <van-popup v-model="isShow" round position="bottom">
      <van-cascader
        v-model="id"
        :title="'请选择' + label"
        :options="options"
        :field-names="fieldNames"
        @close="isShow = false"
        @finish="onFinish"
      />
    </van-popup>
  </div>
</template>

<script>
export default {
  name: 'AppVantTreeSelectPop',
  props: {
    label: {
      type: String,
      default: () => { return '' }
    },
    value: {
      type: String,
      default: () => { return '' }
    },
    options: {
      type: Array,
      default: () => { return [] }
    }
  },
  data() {
    return {
      isShow: false,
      text: '',
      id: '',
      fieldNames: {
        text: 'label',
        value: 'id',
        children: 'children'
      }
    }
  },
  watch: {
    value: {
      handler(newVal) {
        this.id = newVal
        var selectedOptions = this.getSelectedOptionsByValue(this.options, this.id)
        if (typeof selectedOptions !== 'undefined') {
          this.text = selectedOptions.map((option) => option.label).join(' / ')
        } else {
          this.text = ''
        }
      },
      immediate: true
    },
    options: {
      handler() {
        var selectedOptions = this.getSelectedOptionsByValue(this.options, this.id)
        if (typeof selectedOptions !== 'undefined') {
          this.text = selectedOptions.map((option) => option.label).join(' / ')
        } else {
          this.text = ''
        }
      },
      deep: true,
      immediate: true
    }
  },
  methods: {
    getSelectedOptionsByValue(options, value) {
      for (var i = 0; i < options.length; i++) {
        var option = options[i]

        if (option['id'] === value) {
          return [option]
        }

        if (option['children']) {
          var selectedOptions = this.getSelectedOptionsByValue(option['children'], value)

          if (selectedOptions) {
            return [option].concat(selectedOptions)
          }
        }
      }
    },

    onFinish() {
      this.$emit('update:value', this.id)
      // this.text = selectedOptions.map((option) => option.label).join(' /')
      this.isShow = false
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>

</style>
