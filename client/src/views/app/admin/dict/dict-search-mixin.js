/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 20:42:05
 */
function searchOptionsConfig() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'label',
            placeholder: '字段：标签',
            tooltip: '查询字段：标签',
            width: 150
          },
          {
            prop: 'name',
            placeholder: '字段：类名',
            tooltip: '查询字段：类名',
            width: 150
          }
        ]
      }
    }
  }
}

export default searchOptionsConfig
