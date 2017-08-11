<template>
  <div>
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
  </div>
</template>
<script>
export default {
  props: ['item_name']
}

</script>
