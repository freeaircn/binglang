/*
 * @Description:
 * @Author: freeair
 * @Date: 2020-01-30 19:26:36
 * @LastEditors  : freeair
 * @LastEditTime : 2020-02-01 21:53:45
 */
import { validChineseLetter, validLowerLetterUnderline } from '@/utils/app/validator/search-com'

var searchOptionsConfig = {
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
      searchOptionsRules: {
        label: [{ validator: validChineseLetter, trigger: 'blur' }],
        name: [{ validator: validLowerLetterUnderline, trigger: 'blur' }]
      }
    }
  }
}

export default searchOptionsConfig
