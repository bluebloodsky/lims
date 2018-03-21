<template>
  <TplBox :tpl="tpl">
    <template v-for="attr in attrs">
      <input :slot="attr.name" v-model="realValue[attr.name]" :disabled="attr.readonly" v-if="attr.attr_type=='input'">
      <select :slot="attr.name" :disabled="attr.readonly" v-model="realValue[attr.name]" v-else-if="attr.attr_type=='select'">
        <option v-for="option in attr.options">
          {{option}}
        </option>
      </select>
       <el-date-picker :slot="attr.name" :disabled="attr.readonly" v-model="realValue[attr.name]" type="date" placeholder="选择日期" format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd" v-else-if="attr.attr_type=='date'">
      </el-date-picker>
      <template v-else-if="attr.attr_type=='radio'||attr.attr_type=='checkbox'">
        <template v-for="(opt,opt_index) in attr.options">
          <input :slot="attr.name+'_'+opt_index" :disabled="attr.readonly" type="radio" :id="opt_index" :name="attr.name" :value="opt" v-model="realValue[attr.name]" v-if="attr.attr_type=='radio'">
          <input :slot="attr.name+'_'+opt_index" :disabled="attr.readonly" type="checkbox" :id="opt_index" :name="attr.name" :value="opt" v-model="realValue[attr.name]" v-else>
        </template>
      </template>
      <table :slot="attr.name" v-if="attr.attr_type=='tbody'" class="data-table">
        <thead>
          <tr>
            <th v-for="col in attr.columns">{{col.name_cn}}</th>
            <th>
              <button type="text" @click="addRow(attr)"><i class="iconfont icon-plus"></i></button>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(row,row_id) in realValue[attr.name]">
            <td v-for="(col,col_id) in row">
              <input type="text" v-model="realValue[attr.name][row_id][col_id]">
            </td>
            <td>
              <button type="text" @click="realValue[attr.name].splice(row_id , 1)"><i class="iconfont icon-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </template>
  </TplBox>
</template>
<script>
import TplBox from './TplBox'

export default {
  name:'TplRender',
  components: { TplBox },
  props: {
    attrs: {
      type: [Array,undefined],
      required: true
    },
    tpl: {
      type: [String,undefined],
      required: true
    },
    value: {
      type:[Object,undefined]
    }
  },
  data() {
    return {
      realValue: {}
    }
  },
  mounted() {
    this.attrs.map(attr => {
      let l_value = ''
      if (attr.attr_type == 'input' || attr.attr_type == 'select' || attr.attr_type == 'radio') {
        l_value = this.value && this.value[attr.name] ? this.value[attr.name] : ''
      } else if (attr.attr_type == 'checkbox') {
        l_value = this.value && this.value[attr.name] ? this.value[attr.name].split(",") : []
      }
      this.$set(this.realValue, attr.name, l_value)
    })
  },
  watch: {
    realValue: {
      handler(newVal) {
        this.$emit('input', newVal)
      },
      deep: true
    }
  },
  methods: {
    addRow(attr) {
      let arr = new Array(attr.columns.length)
      if(!this.realValue[attr.name] || !Array.isArray(this.realValue[attr.name])){
        this.realValue[attr.name] = []
      }
      this.realValue[attr.name].push(arr)
    }
  }
}

</script>
<style scoped>

td{
  border: 1px solid #ccc;
  padding: 0;
}
.data-table input[type="text"]{
 width: 100%;
 height: 100%;
 font-size: 18px;
 text-align: center;
 margin: 0;
 box-sizing: border-box;
}
</style>
