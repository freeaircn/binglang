/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-25 16:03:20
 */
import * as validator from '@/utils/app/validator/common'

function searchOptionsConfig() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'label',
            placeholder: '字段：标签',
            tooltip: '查询字段：标签',
            maxlength: 15,
            width: 150
          }
        ],
        searchOptionsRules: {
          label: [{ pattern: validator.chineseLetter.regex, message: validator.chineseLetter.msg }]
        }
      }
    }
  }
}

export default searchOptionsConfig
