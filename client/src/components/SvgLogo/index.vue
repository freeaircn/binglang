<template>
  <svg :class="svgClass" aria-hidden="true">
    <use :xlink:href="logoName" />
  </svg>
</template>

<script>
export default {
  name: 'SvgLogo',
  props: {
    logoClass: {
      type: String,
      required: true
    },
    className: {
      type: String,
      default: ''
    }
  },
  computed: {
    logoName() {
      return `#logo-${this.logoClass}`
    },
    svgClass() {
      if (this.className) {
        return 'svg-logo ' + this.className
      } else {
        return 'svg-logo'
      }
    }
  }
}
</script>

<style scoped>
.svg-logo {
  width: 48px;
  height: 48px;
  fill: currentColor;
  overflow: hidden;
}
</style>
