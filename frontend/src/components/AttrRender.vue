<template>
  <div class="form-item">
    <span>{{attr.name_cn}}</span>
    <span v-if="attr.required">*</span>
    <div class="right-info">
      <input v-if="attr.attr_type=='input'" :disabled="attr.readonly" v-model="realValue">
      <select v-else-if="attr.attr_type=='select'" v-model="realValue">
        <option :value="opt" v-for="opt in attr.options">{{opt}}</option>
      </select>
      <template v-else-if="attr.attr_type=='checkbox'">
        <template v-for="item in realValue">
          <input type="checkbox" class="checkbox" :value="item.value" v-model="item.checked"> {{item.value}}
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
    if (this.attr.attr_type == 'checkbox') {
      this.realValue = []
      this.attr.options.map(opt => {
        let item = {
          value: opt,
          checked: false
        }
        if (this.value.includes(opt)) {
          item.checked = true
        }
        this.realValue.push(item)
      })
    } else {
      this.realValue = this.value
    }
  },
  watch: {
    realValue: {
      handler(newVal) {
        if (this.attr.attr_type == 'checkbox') {
          let result = []
          newVal.map(item => {
            if (item.checked) {
              result.push(item.value)
            }
          })
          this.$emit('input', result)
        } else {
          this.$emit('input', newVal)
        }
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
