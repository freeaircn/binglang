/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-25 15:59:36
 */
import * as validator from '@/utils/app/validator/common'

function searchOptionsConfig() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'individual',
            placeholder: '工号，姓名，手机号...',
            tooltip: '查询字段：工号，姓名，手机号，邮箱，身份证号',
            maxlength: 40,
            width: 150
          },
          {
            prop: 'dept',
            placeholder: '字段：部门',
            tooltip: '查询字段：部门',
            maxlength: 40,
            width: 150
          },
          {
            prop: 'job',
            placeholder: '字段：岗位',
            tooltip: '查询字段：岗位',
            maxlength: 40,
            width: 150
          },
          {
            prop: 'politic',
            placeholder: '字段：政治面貌',
            tooltip: '查询字段：政治面貌',
            maxlength: 15,
            width: 150
          },
          {
            prop: 'professional_title',
            placeholder: '字段：职称',
            tooltip: '查询字段：职称',
            maxlength: 40,
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
        ],
        searchOptionsRules: {
          dept: [{ pattern: validator.englishChineseLetter.regex, message: validator.englishChineseLetter.msg }],
          job: [{ pattern: validator.englishChineseLetter.regex, message: validator.englishChineseLetter.msg }],
          politic: [{ pattern: validator.chineseLetter.regex, message: validator.chineseLetter.msg }],
          professional_title: [{ pattern: validator.englishChineseLetter.regex, message: validator.englishChineseLetter.msg }]
          // individual: [{ validator: validSpecIndividual, trigger: 'blur' }],
        }
      }
    }
  }
}

export default searchOptionsConfig
