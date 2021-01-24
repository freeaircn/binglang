<!--
 * @Description:
 * @Author: freeair
 * @Date: 2019-12-27 18:10:06
 * @LastEditors: freeair
 * @LastEditTime: 2021-01-25 01:05:43
-->
<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
  beforeMount() {
    this.is_mobile()
  },
  methods: {
    is_mobile() {
      let flag = false
      flag = navigator.userAgent.match(/(phone|pad|pod|iPhone|iPod|ios|iPad|Android|Mobile|IEMobile|MQQBrowser|JUC|Fennec|wOSBrowser|BrowserNG|WebOS)/i)
      if (flag) {
        this.$store.dispatch('app/toggleDesktop', false)
      } else {
        // this.$store.dispatch('app/toggleDesktop', true)
        this.$store.dispatch('app/toggleDesktop', false)
      }
    }
  }
}
</script>
