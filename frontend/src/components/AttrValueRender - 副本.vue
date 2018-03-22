<template>
  <input :disabled="attr.readonly" v-model="realValue" v-if="attr.attr_type=='input'">
  <ul :disabled="attr.readonly" v-else-if="attr.attr_type=='checkbox'||attr.attr_type=='radio'">
    <li v-for="(opt,opt_index) in attr.options">
      <input type="checkbox" :id="opt_index" :value="opt" v-model="realValue" v-if="attr.attr_type=='checkbox'">
      <input type="radio" :id="opt_index" :value="opt" v-model="realValue" v-else> {{opt}}
    </li>
  </ul>
  <ZlDatePicker v-model="realValue" v-else-if="attr.attr_type=='date'">
  </ZlDatePicker>
  <select v-model="realValue" v-else-if="attr.attr_type=='select'">
    <option :value="opt" v-for="opt in attr.options">{{opt}}</option>
  </select>
  <PictureList v-model="realValue" v-else-if="attr.attr_type=='pictureTable'"></PictureList>
</template>
<script>
import PictureList from './PictureList'
import ZlDatePicker from './ZlDatePicker'

export default {
  name: 'AttrValueRender',
  components: {
    PictureList,
    ZlDatePicker
  },
  props: {
    attr: {
      type: Object,
      required: true
    },
    value: {

    }
  },
  data() {
    return {
      realValue: ''
    }
  },
  mounted() {
    if (this.attr.attr_type == "checkbox") {
      this.realValue = this.value ? Array.isArray(this.value) ? this.value : this.value.split(",") : []
    } else if (this.attr.attr_type == "pictureTable") {
      this.realValue = this.value && Array.isArray(this.value) ? this.value : []
    } else {
      this.realValue = this.value
    }
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
<style scoped>
td {
  border: 1px solid #ccc;
  padding: 0;
}

</style>
