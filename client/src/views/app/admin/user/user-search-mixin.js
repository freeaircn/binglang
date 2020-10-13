/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors: freeair
 * @LastEditTime: 2020-06-02 10:41:47
 */
import { validChineseLetter, validEnglishChineseLetter, validSpecIndividual } from '@/utils/app/validator/search-com'

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
          individual: [{ validator: validSpecIndividual, trigger: 'blur' }],
          dept: [{ validator: validEnglishChineseLetter, trigger: 'blur' }],
          job: [{ validator: validEnglishChineseLetter, trigger: 'blur' }],
          politic: [{ validator: validChineseLetter, trigger: 'blur' }],
          professional_title: [{ validator: validEnglishChineseLetter, trigger: 'blur' }]
        }
      }
    }
  }
}

export default searchOptionsConfig
