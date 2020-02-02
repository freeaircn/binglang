/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-02 16:04:43
 */
import { validChineseLetter, validLowerLetterUnderline } from '@/utils/app/validator/search-com'

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
          label: [{ validator: validChineseLetter, trigger: 'blur' }],
          name: [{ validator: validLowerLetterUnderline, trigger: 'blur' }]
        }
      }
    }
  }
}

export default searchOptionsConfig
