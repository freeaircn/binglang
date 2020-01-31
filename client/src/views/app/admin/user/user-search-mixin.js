/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-31 20:13:05
 */
function searchOptionsConfig() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'individual',
            placeholder: '工号，姓名，手机号...',
            tooltip: '查询字段：工号，姓名，手机号，邮箱，身份证号',
            width: 150
          },
          {
            prop: 'dept',
            placeholder: '字段：部门',
            tooltip: '查询字段：部门',
            width: 150
          },
          {
            prop: 'job',
            placeholder: '字段：岗位',
            tooltip: '查询字段：岗位',
            width: 150
          },
          {
            prop: 'politic',
            placeholder: '字段：党派',
            tooltip: '查询字段：党派',
            width: 150
          },
          {
            prop: 'professional_title',
            placeholder: '字段：职称',
            tooltip: '查询字段：职称',
            width: 150
          }
        ],
        searchOptionsSelects: [
          {
            prop: 'sex',
            placeholder: '性别',
            tooltip: '查询字段:性别',
            width: 80,
            options: [{
              value: '0',
              label: '男'
            }, {
              value: '1',
              label: '女'
            }]
          }
        ]
      }
    }
  }
}

export default searchOptionsConfig
