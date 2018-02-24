<template>
  <div class="form-item">
    <span>{{attr.name_cn}}</span>
    <span v-if="attr.required">*</span>
    <div class="right-info">
      <input :type="attr.attr_type" :disabled="attr.readonly" v-model="realValue" v-if="attr.attr_type=='input'">
      <el-date-picker v-model="realValue" type="date" placeholder="选择日期" format="yyyy 年 MM 月 dd 日" value-format="yyyy-MM-dd" v-else-if="attr.attr_type=='date'">
      </el-date-picker>
      <select v-model="realValue" v-else-if="attr.attr_type=='select'">
        <option :value="opt" v-for="opt in attr.options">{{opt}}</option>
      </select>
      <template v-else-if="attr.attr_type=='checkbox'">
        <template v-for="(opt,opt_index) in attr.options">
          <input type="checkbox" :id="opt_index" class="checkbox" :value="opt" v-model="realValue"> {{opt}}
        </template>
      </template>
    </div>
  </div>
</template>
<script>
export default {
  name: 'AttrRender',
  data() {
    return { realValue: '' }
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
  mounted() {
    if (this.attr.attr_type == "checkbox") {
      this.realValue = this.value ? this.value.split(",") : []
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
.form-item {
  width: 45%;
  position: relative;
  margin: 5px 10px;
  text-align: left;
  display: inline-block;
}

span {
  width: 150px;
  display: inline-block;
  text-align: left;
}

.right-info {
  width: 350px;
  display: inline-block;
}

.right-info>input,
.right-info>select {
  width: 100%;
  font-size: 14px;
}

.right-info>.checkbox {
  width: 15px;
  height: 15px;
}

</style>
