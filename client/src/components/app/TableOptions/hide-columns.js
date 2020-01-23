/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-23 19:35:05
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-24 00:23:19
 */
// import Vue from 'vue'

function hideColumns() {
  function obColumns(columns) {
    console.log('#5')
    console.log(columns)
    return {
      visible(col) {
        console.log('#4')
        // console.log(columns)
        console.log(col)
        return !columns || !columns[col] ? true : columns[col].visible
      }
    }
  }
  return {
    data() {
      return {
        isColumnVisible: obColumns(),
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
      this.isColumnVisible = obColumns(temp)
      this.columns = temp
      console.log('#1')
      console.log(this.isColumnVisible)
    }
  }
}

export default hideColumns
