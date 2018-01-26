<!--
<template>
  <div v-if="item_name=='fhwtwgjc'">
    总缺陷面积应不大于外套总表面积的0.2%
  </div>
  <div v-else-if="item_name=='pdbjjc'">
    爬电比距≥
    <slot name='pdbj'></slot>mm/kV
  </div>
  <div v-else-if="item_name=='zlckdysy'">
    73kV≤U1mA.DC≤76kV
  </div>
  <div v-else-if="item_name=='ldlsy'">
    IL(0.75U1mA.DC) ≤50μA
  </div>
  <div v-else-if="item_name=='bend'">
    <slot name="bend_select_1"></slot>根据抗弯符合F1
    <slot name="bend_f1"></slot>N计算
    <br>
    <slot name="bend_select_2"></slot>宣称整只抗弯强度应不小于
    <slot name="bend_min_f"></slot>kN,
    <slot name="bend_min_s"></slot>s~
    <slot name="bend_max_s"></slot>s,而不破坏
  </div>
  <div v-else-if="item_name=='stretch'">
    <slot name="stretch_select_1"></slot>根据15倍避雷器自重计算
    <br>
    <slot name="stretch_select_2"></slot>宣称整只拉伸强度应不小于
    <slot name="stretch_min_f"></slot>kN,
    <slot name="stretch_min_s"></slot>s~
    <slot name="stretch_max_s"></slot>s,而不破坏
  </div>
  <div v-else-if="item_name=='ldcjcysy'">
    ≤ <slot name="ldcjcy"></slot> kVP
  </div>
  <div v-else-if="item_name=='cxcjcysy'">
    ≤ <slot name="czcjcy"></slot> kVP
  </div>
  <div v-else-if="item_name=='dbcjcysy'">
    ≤ <slot name="dbcjcy"></slot> kVP
  </div>
</template>
!-->
<script>
export default {
  /*
  render(createElement) {
    var els = [];
    if (this.tpl) {
      var ss = this.tpl.split("$$");
      for (var i = 0; i < ss.length; i++) {
        if (i % 2) {
          els.push(this.$slots[ss[i]])
        } else {
          els.push(createElement('span',{domProps:{
            innerHTML:ss[i]}}))
        }
      }
    }
    return createElement('div', els)
  },
  */

  props: ['item_name', 'tpl'],
  created(){
    this.$options.template = this.tpl
  }
}

</script>
