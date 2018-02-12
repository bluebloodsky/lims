<template>
  <el-form label-width="120px" onsubmit="return false;">
    <el-form-item label="名称">
      <el-input v-model="value.name"></el-input>
    </el-form-item>
    <el-form-item label="描述">
      <el-input v-model="value.name_cn"></el-input>
    </el-form-item>
    <el-form-item label="类型">
      <el-select v-model="value.type" label="类型">
        <el-option :label="attrType.type_cn" :value="attrType.type" v-for="attrType in attrTypes" />
      </el-select>
    </el-form-item>
    <el-form-item label="选项" v-if="value.type=='select'||value.type=='radio'||value.type=='checkbox'">
      <TodoList v-model="value.options" />
    </el-form-item>
    <el-form-item label="只读">
      <el-switch v-model="value.readonly"></el-switch>
    </el-form-item>
    <el-form-item label="默认值">
      <el-input v-model="value.default_value"></el-input>
    </el-form-item>
  </el-form>
</template>
<script>
import { ATTR_TYPES } from '@/shared/constants'
import TodoList from './TodoList'
export default {
  props: {
    value: {
      type: Object,
      requied: true
    }
  },
  data() {
    return {
      attrTypes: []
    }
  },
  components: {
    TodoList
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
}

</script>
