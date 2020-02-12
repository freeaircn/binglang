/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-07 22:02:47
 */
import { validChineseLetter } from '@/utils/app/validator/search-com'

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
          title: [{ validator: validChineseLetter, trigger: 'blur' }]
        }
      }
    }
  }
}

export default searchOptionsConfig
