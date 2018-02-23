<template>
  <div v-for="(item,key) in logs" style="border-bottom:#ccc 1px solid">
  </div>
</template>
<script>
var TplLogItem = {
  template: `
  <span :class="log.type">
  【{{key}}】: {{logText}}  	
  </span>
  `,
  props: {
    log: {
      type: Object,
      requied: true
    },
    key: {
      type: String,
      requied: true
    }
  },
  computed: {
    logText() {
      if (log.type == "add") {
        return '添加：' + JSON.stringify(log.newvalue)
      } else if (log.type == "remove") {
        return '删除：' + JSON.stringify(log.oldvalue)
      } else if (log.type == "change") {
        return JSON.stringify(log.oldvalue) + '=>' + JSON.stringify(log.newvalue)
      }
    }
  }
}
export default {
  name: 'ChangeLog',

}

</script>
<style scoped>
.add {
  color: green;
}

.remove {
  color: red;
  text-decoration: line-through;
}

</style>
