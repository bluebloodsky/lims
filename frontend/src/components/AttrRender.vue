<template>
  <div class="form-item" :class="{wrapper:attr.attr_type=='pictureTable'}">
    <div class="left-desc">
      <span>{{attr.name_cn}}</span>
      <span v-if="attr.required">*</span>
    </div>
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
      <PictureList v-model="realValue" v-else-if="attr.attr_type=='pictureTable'"></PictureList>
    </div>
  </div>
</template>
<script>
import PictureList from './PictureList'
export default {
  name: 'AttrRender',
  components: {
    PictureList
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
      set(val){
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

.wrapper {
  width: 1000px;
}

.left-desc {
  width: 150px;
  display: inline-block;
  text-align: left;
}

.right-info {
  min-width: 300px;
  max-width: 800px;
  display: inline-block;
}

input,
select {
  width: 100%;
  font-size: 14px;
}

.right-info>.checkbox {
  width: 15px;
  height: 15px;
}

</style>
