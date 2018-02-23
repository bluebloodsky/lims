<template>
  <TempTestItemParam :tpl="tpl">
    <template v-for="attr in attrs">
      <input v-if="attr.attr_type=='radio'||attr.attr_type=='checkbox'" :disabled="attr.readonly" :type="attr.attr_type" :name="attr.name" :slot="attr.name+'_'+opt_key" :value="opt_key" v-for="(option,opt_key) in attr.options">
      <input :slot="attr.name" v-if="attr.attr_type=='input'" :disabled="attr.readonly">
      <select :slot="attr.name" v-else-if="attr.attr_type=='select'" :disabled="attr.readonly">
        <option v-for="option in attr.options">
          {{option}}
        </option>
      </select>
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
    let real_tpl = this.tpl.replace(reg,`<slot name='$1'></slot>`)
    this.$options.template = '<div>' + real_tpl + '</div>'
  }
}
export default {
  props: {
    attrs: {
      type: Array,
      requied: true
    },
    tpl: {
      type: String,
      requied: true
    }
  },
  components: { TempTestItemParam },  
}

</script>
