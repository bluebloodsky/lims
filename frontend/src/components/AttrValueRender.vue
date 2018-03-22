<template>
  <input :disabled="attr.readonly" v-model="realValue" v-if="attr.attr_type=='input'">
  <ul  v-else-if="attr.attr_type=='checkbox'||attr.attr_type=='radio'">
    <li v-for="(opt,opt_index) in attr.options">
      <input :disabled="attr.readonly" :name="attr.name" :type="attr.attr_type" :value="opt" @click="checkVal" :checked="isChecked(opt)">{{opt}}
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
import { copyObject, remove } from '@/shared/util'
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
  methods: {
    checkVal(e) {
      if (this.attr.attr_type == "checkbox") {
        if (e.target.checked) {
          let newVal = copyObject(this.realValue)
          newVal.push(e.target.value)
          this.realValue = newVal
        } else {
          let newVal = copyObject(this.realValue)
          remove(newVal, e.target.value)
          this.realValue = newVal
        }
      } else {
        realValue = e.target.value
      }
    },
    isChecked(opt) {
      if (this.attr.attr_type == "checkbox") {
        return this.realValue.indexOf(opt) != -1
      } else {
        return this.realValue == opt
      }
    }
  },
  computed: {
    realValue: {
      get() {
        if (this.attr.attr_type == "checkbox") {
          return this.value ? Array.isArray(this.value) ? this.value : this.value.split(",") : []
        } else if (this.attr.attr_type == "pictureTable") {
          return this.value && Array.isArray(this.value) ? this.value : []
        } else {
          return this.value
        }
      },
      set(val) {
        this.$emit('input', val)
      }
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
