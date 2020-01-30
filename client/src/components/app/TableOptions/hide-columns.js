/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-23 19:35:05
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 00:47:16
 */
// import Vue from 'vue'

function hideColumns() {
  function obColumns(columns) {
    return {
      visible(col) {
        return !columns || !columns[col] ? true : columns[col].visible
      }
    }
  }
  return {
    data() {
      return {
        columnOpt: obColumns(),
        columns: {}
      }
    },
    mounted() {
      const temp = {}
      this.$refs.table.columns.forEach(e => {
        if (!e.property || e.type !== 'default') {
          return
        }
        temp[e.property] = {
          label: e.label,
          visible: true
        }
        if (e.columnKey === 'pre-hide') {
          temp[e.property].visible = false
        }
      })
      this.columnOpt = obColumns(temp)
      this.columns = temp
    },
    methods: {
      updateColumns() {
        this.$refs.table.columns.forEach(e => {
          if (!e.property || e.type !== 'default') {
            return
          }

          var found = false
          for (const key in this.columns) {
            if (this.columns[key].label === e.label) {
              found = true
            }
          }

          if (!found) {
            this.$set(this.columns, e.property, {
              label: e.label,
              visible: true
            })
            if (e.columnKey === 'pre-hide') {
              this.columns[e.property].visible = false
            }
          }
        })
        this.columnOpt = obColumns(this.columns)
      }
    }
  }
}

export default hideColumns
