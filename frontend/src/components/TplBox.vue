<script>
export default {
  name: 'TplBox',
  props: {
    tpl: {
      type: String,
      required: true
    }
  },
  created() {
    let reg = /\$\{(\S+?)\}/g
    let real_tpl = this.tpl.replace(reg, `<slot name='$1'></slot>`)
    this.$options.template = '<div style="margin:10px;">' + real_tpl + '</div>'
  }
}

</script>
<style scoped>
div {
  white-space: pre-wrap;
}

table {
  width: 100%;
  border: 1px solid #ccc;
  border-collapse: collapse;
  text-align: center;
}

td,
th {
  border: 1px solid #ccc;
  padding: 0;
}

tfoot{
  text-align: left;
}

table input:not([type]) {
  width: auto;
  height: auto;
}
table.data-table input:not([type]) {
  width: 100%;
  height: 100%;
  font-size: 18px;
  text-align: center;
  margin: 0;
  box-sizing: border-box;
}

</style>
