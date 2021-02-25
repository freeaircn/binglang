/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors: freeair
 * @LastEditTime: 2021-02-25 16:03:11
 */
import * as validator from '@/utils/app/validator/common'

function searchOptionsConfig() {
  return {
    data() {
      return {
        searchOptionsInputs: [
          {
            prop: 'title',
            placeholder: '字段：标题',
            tooltip: '查询字段：标题',
            maxlength: 15,
            width: 150
          }
        ],
        searchOptionsRules: {
          title: [{ pattern: validator.chineseLetter.regex, message: validator.chineseLetter.msg }]
        }
      }
    }
  }
}

export default searchOptionsConfig
