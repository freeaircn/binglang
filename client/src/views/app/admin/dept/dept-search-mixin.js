/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-06 21:48:57
 */
import { validEnglishChineseLetter } from '@/utils/app/validator/search-com'

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
          label: [{ validator: validEnglishChineseLetter, trigger: 'blur' }]
        }
      }
    }
  }
}

export default searchOptionsConfig
