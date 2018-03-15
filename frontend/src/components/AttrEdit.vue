<template>
  <div style="padding:15px;">
    <el-form label-width="120px" onsubmit="return false;">
      <el-form-item label="名称">
        <el-input v-model="value.name"></el-input>
      </el-form-item>
      <el-form-item label="描述">
        <el-input v-model="value.name_cn"></el-input>
      </el-form-item>
      <el-form-item label="类型">
        <el-select v-model="value.attr_type" label="类型">
          <el-option :label="attrType.type_cn" :value="attrType.type" v-for="attrType in attrTypes" />
        </el-select>
      </el-form-item>
      <el-form-item label="选项" v-if="value.attr_type != 'tbody'">
        <TodoList v-model="value.options"></TodoList>
      </el-form-item>
      <el-form-item label="只读" v-if="value.attr_type != 'tbody'">
        <el-switch v-model="value.readonly"></el-switch>
      </el-form-item>
      <el-form-item label="默认值" v-if="value.attr_type != 'tbody'">
        <el-input v-model="value.default_value"></el-input>
      </el-form-item>
    </el-form>
    <div class="box" v-if="value.attr_type == 'tbody'">
      <div class="h">
        <span>表格列属性列表</span>
        <div class="right-btn">
          <el-button type="text" @click="addAttr"><i class="iconfont icon-plus"></i></el-button>
        </div>
      </div>
      <div class="b">
        <AttrList v-model="value.columns"></AttrList>
      </div>
    </div>
  </div>
</template>
<script>
import { ATTR_TYPES } from '@/shared/constants'
import TodoList from './TodoList'
import AttrList from './AttrList'
export default {
  name: 'AttrEdit',
  props: {
    value: {
      type: Object,
      requied: true
    }
  },
  data() {
    return {
      attrTypes: [],
      flg_col_edit: false
    }
  },
  components: {
    TodoList,
    AttrList
  },
  mounted() {
    this.attrTypes = ATTR_TYPES
  },
  watch: {
    value: {
      handler(newVal) {
        this.$emit('input', newVal)
      },
      deep: true
    }
  },
  methods: {
    addAttr() {
      if (!this.value.columns) {
        this.value.columns = []
      }
      this.value.columns.push({})
    }
  }
}

</script>
<style scoped>
.box {
  width: 100%;
  height: 100%;
}
</style>
