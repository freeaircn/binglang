/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-04 22:39:53
 */
import { validChineseLetter } from '@/utils/app/validator/search-com'

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
          // {
          //   prop: 'name',
          //   placeholder: '字段：类名',
          //   tooltip: '查询字段：类名',
          //   maxlength: 60,
          //   width: 150
          // }
        ],
        searchOptionsRules: {
          label: [{ validator: validChineseLetter, trigger: 'blur' }]
          // name: [{ validator: validLowerLetterUnderline, trigger: 'blur' }]
        }
      }
    }
  }
}

export default searchOptionsConfig
