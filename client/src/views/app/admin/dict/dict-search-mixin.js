/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-30 23:51:55
 */
function searchOptionsInit() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'individual',
            placeholder: '工号，姓名，手机号...',
            tooltip: '查询字段:工号，姓名，手机号，邮箱，身份证号'
          },
          {
            prop: 'dept',
            placeholder: '字段：部门',
            tooltip: '查询字段:部门'
          }
        ],
        searchOptionsSelects: [
          {
            prop: 'sex',
            placeholder: '性别',
            tooltip: '查询字段:性别',
            options: [{
              value: '0',
              label: '男'
            }, {
              value: '1',
              label: '女'
            }]
          }
        ],
        searchOptionsButtons: [
          {
            type: 'primary',
            icon: 'el-icon-search',
            label: '查询',
            tooltip: '各字段按“与”组合查询'
          }
        ]
      }
    }
  }
}

export default searchOptionsInit
