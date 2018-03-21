<template>
  <div class="zl-date-picker" @click.stop>
    <input placeholder="请选择日期" :value="showValue" @change="changeValue" @focus="focus" ref="input">
    <button @click="chooseDate">V</button>
    <ZlCalendar class="calendar" :class="{'show-calendar':showCalendar}" :value="currentDate" @input="receiveDate"></ZlCalendar>
  </div>
</template>
<script>
import ZlCalendar from './ZlCalendar'
export default {
  name: 'ZlDatePicker',
  components: { ZlCalendar },
  props: {
    value: {
      type: String
    },
  },
  data() {
    return {
      currentDate: null,
      showCalendar: false
    }
  },
  created() {
    this.currentDate = this.value ? new Date(this.value) : new Date()
    window.onclick = e => this.showCalendar = false
  },
  computed: {
    showValue() {
      return this.currentDate ? this.currentDate.format("yyyy-MM-dd") : ''
    }
  },
  methods: {
    chooseDate() {
      this.showCalendar = true;
      this.$nextTick(() => this.$refs.input.focus())
    },
    receiveDate(val) {
      this.showCalendar = false;
      this.$nextTick(() => this.$refs.input.focus())
      this.currentDate = new Date(val)
      this.$emit('input', val)
    },
    changeValue(e) {
      let newValue = e.target.value
      if (new Date(newValue).getDate() == newValue.substring(newValue.length - 2)) {
        this.currentDate = new Date(newValue)
        this.$emit('input', newValue)
      }
      e.target.value = this.currentDate.format("yyyy-MM-dd")
    },
    focus() {
      this.$refs.input.select()
    }
  }
}

</script>
<style scoped>
.zl-date-picker {
  position: relative;
  height: 20px;
  line-height: 18px;
  width: 100px;
}

.zl-date-picker input {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0 20px 0 0;
  box-sizing: border-box;
  text-align: center;
  border: none;
  border: 1px solid #8BA0BC;
}

.zl-date-picker button {
  position: absolute;
  right: 1px;
  top: 1px;
  width: 18px;
  height: 18px;
  border: none;
  border-left: 1px solid #8BA0BC;
  background-color: #CADAEA;
}

.calendar {
  position: absolute;
  top: 20px;
  left: -9999px;
}

.show-calendar {
  left: 0;
  z-index: 9999;
}

</style>
