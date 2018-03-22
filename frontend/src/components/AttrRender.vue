<template>
  <div class="form-item" :class="{table:attr.attr_type=='pictureTable'}">
    <div class="left-desc">
      <span>{{attr.name_cn}}</span>
      <span v-if="attr.required">*</span>
    </div>
    <div class="right-info">     
      <AttrValueRender v-model="realValue" :attr="attr"></AttrValueRender>
    </div>
  </div>
</template>
<script>
import AttrValueRender from './AttrValueRender'

export default {
  name: 'AttrRender',
  components: {
    AttrValueRender
  },
  props: {
    attr: {
      type: Object,
      required: true
    },
    value: {
      required: true
    }
  },
  computed: {
    realValue: {
      get() {
        return this.value
      },
      set(val) {
        this.$emit('input', val)
      }
    }
  }
}

</script>
<style scoped>
.form-item {
  position: relative;
  margin: 5px 10px;
  text-align: left;
  display: inline-block;
  width: 500px;
}

.table {
  width: 100%;
}

.left-desc {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 150px;
  /*Flex布局*/
  text-align: left;
}

.right-info {
  margin-left: 150px;
  min-width: 300px;
  max-width: 1000px;
  display: inline-block;
}

input:not([type]),
input[type=text],
select {
  width: 100%;
  font-size: 14px;
  height: 25px;
  box-sizing: border-box;
}

</style>
