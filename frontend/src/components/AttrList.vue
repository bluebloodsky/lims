<template>
  <div>
    <el-table :data="value" border>
      <el-table-column align="center" :prop="item.name" :label="item.caption" v-for="item in attrFields" :formatter="cellFormatter">
      </el-table-column>
      <el-table-column label="操作" align="center">
        <template scope="scope">
          <el-button @click.native.prevent="editRow(scope.row)" type="text"><i class="iconfont icon-edit"></i>
          </el-button>
          <el-button @click.native.prevent="delRow(scope.row)" type="text"><i class="iconfont icon-trash"></i>
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="right-pad-box" :class="{ 'show-box':flg_showRightBox}">
      <div class="h">
        <span>属性编辑</span>
        <div class="right-btn">
          <el-button type="text" @click="hideDetail"><i class="iconfont icon-cancel"></i></el-button>
        </div>
      </div>
      <div class="b">
        <AttrEdit v-model="currentRow" />
      </div>
    </div>
  </div>
</template>
<script>
import { ATTR_FIELDS, ATTR_TYPES } from '@/shared/constants'
import { copyObject, rollbackMap } from '@/shared/util'

export default {
  name: 'AttrList',
  props: {
    value: {
      requied: true
    }
  },
  data() {
    return {
      attrFields: [],
      attrTypes: [],
      currentRow: {},
      flg_showRightBox: false
    }
  },
  created() {
    this.attrFields = ATTR_FIELDS
    this.attrTypes = ATTR_TYPES
    this.$options.components.AttrEdit = () =>
      import ('./AttrEdit.vue')
  },
  methods: {
    cellFormatter(row, column, cellValue) {
      if (Array.isArray(cellValue))
        return cellValue.join('/')
      else if (typeof cellValue == 'boolean') {
        return cellValue ? '是' : '否'
      } else if (column.property == 'attr_type') {
        let attr_type = this.attrTypes.find(i => i.type == cellValue)
        return attr_type ? attr_type.type_cn : cellValue
      }
      return cellValue
    },
    editRow(row) {
      this.flg_showRightBox = true
      this.currentRow = row
    },
    delRow(row) {
      this.value.map((attr, index) => {
        if (attr.name == row.name) {
          this.value.splice(index, 1)
        }
      })
    },
    hideDetail() {
      this.flg_showRightBox = false
    }
  }
}

</script>
<style>
.show-box {
  width: 60%;
}

</style>
