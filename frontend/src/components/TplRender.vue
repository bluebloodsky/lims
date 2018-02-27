<template>
  <TempTestItemParam :tpl="tpl">
    <template v-for="attr in attrs">
      <input :slot="attr.name" v-model="realValue[attr.name]" :disabled="attr.readonly" v-if="attr.attr_type=='input'">
      <select :slot="attr.name" :disabled="attr.readonly" v-model="realValue[attr.name]" v-else-if="attr.attr_type=='select'">
        <option v-for="option in attr.options">
          {{option}}
        </option>
      </select>
      <template v-else-if="attr.attr_type=='radio'||attr.attr_type=='checkbox'">
        <template v-for="(opt,opt_index) in attr.options">
          <input :slot="attr.name+'_'+opt_index" :disabled="attr.readonly" type="radio" :id="opt_index" :name="attr.name" :value="opt" v-model="realValue[attr.name]" v-if="attr.attr_type=='radio'">
          <input :slot="attr.name+'_'+opt_index" :disabled="attr.readonly" type="checkbox" :id="opt_index" :name="attr.name" :value="opt"  v-model="realValue[attr.name]" v-else>
        </template>
      </template>
      <table :slot="attr.name" v-if="attr.attr_type=='table'" :disabled="attr.readonly" class="dataintable" border="1">
        <tbody>
          <tr>
            <th v-for="col in attr.cols">{{col.label}}</th>
            <th>
              <button>+</button>
            </th>
          </tr>
          <tr v-for="row in [1,2,3]">
            <td v-for="col in param.cols">
              <input type="" name="" :disabled="col.readonly" :class="{disabled:col.readonly}">
            </td>
            <td style="text-align:center">
              <button>-</button>
            </td>
          </tr>
        </tbody>
      </table>
    </template>
  </TempTestItemParam>
</template>
<script>
var TempTestItemParam = {
  props: {
    tpl: {
      type: String,
      requied: true
    }
  },
  created() {
    let reg = /\$\{(\S+?)\}/g
    let real_tpl = this.tpl.replace(reg, `<slot name='$1'></slot>`)
    this.$options.template = '<div>' + real_tpl + '</div>'
  }
}
export default {
  components: { TempTestItemParam },
  props: {
    attrs: {
      type: Array,
      requied: true
    },
    tpl: {
      type: String,
      requied: true
    },
    value: {   
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
      this.$set(this.realValue , attr.name , l_value)
    })
  },
  watch: {
    realValue: {
      handler(newVal) {
        this.$emit('input', newVal)
      },
      deep: true
    }
  }
}

</script>
