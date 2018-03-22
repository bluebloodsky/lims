<template>
  <div class="form-item" :class="{table:attr.attr_type=='pictureTable'}">
    <div class="left-desc">
      <span>{{attr.name_cn}}</span>
      <span v-if="attr.required">*</span>
    </div>
    <div class="right-info">
      <input :disabled="attr.readonly" v-model="realValue" v-if="attr.attr_type=='input'">
      <ZlDatePicker v-model="realValue" v-else-if="attr.attr_type=='date'">
      </ZlDatePicker>
      <select v-model="realValue" v-else-if="attr.attr_type=='select'">
        <option :value="opt" v-for="opt in attr.options">{{opt}}</option>
      </select>
      <ul v-else-if="attr.attr_type=='checkbox'">
        <li v-for="(opt,opt_index) in attr.options">
          <input type="checkbox" :id="opt_index" :value="opt" v-model="realValue"> {{opt}}
        </li>
      </ul>
      <PictureList v-model="realValue" v-else-if="attr.attr_type=='pictureTable'"></PictureList>
    </div>
  </div>
</template>
<script>
import PictureList from './PictureList'
import ZlDatePicker from './ZlDatePicker'

export default {
  name: 'AttrRender',
  components: {
    PictureList,ZlDatePicker
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
  },
  watch: {

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
