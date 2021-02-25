/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-25 16:02:28
 */
import * as validator from '@/utils/app/validator/common'

function searchOptionsConfig() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'label',
            placeholder: '字段：词条名',
            tooltip: '查询字段：词条名',
            maxlength: 15,
            width: 150
          },
          {
            prop: 'name',
            placeholder: '字段：注释',
            tooltip: '查询字段：注释',
            maxlength: 60,
            width: 150
          }
        ],
        searchOptionsSelects: [
          {
            prop: 'dict',
            placeholder: '所属词典',
            tooltip: '过滤词典',
            width: 150,
            options: []
          }
        ],
        searchOptionsRules: {
          label: [{ pattern: validator.chineseLetter.regex, message: validator.chineseLetter.msg }],
          name: [{ pattern: validator.lowerLetterUnderline.regex, message: validator.lowerLetterUnderline.msg }]
        }
      }
    }
  }
}

export default searchOptionsConfig
