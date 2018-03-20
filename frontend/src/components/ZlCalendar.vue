<template>
  <div class="zl-calendar">
    <div class="zl-calendar-header">
      <div>
        <button @click="goPrevYear"></button>
        <button @click="goPrevMonth"></button>
      </div>
      <span>{{title}}</span>
      <div>
        <button @click="goNextYear"></button>
        <button @click="goNextMonth"></button>
      </div>
    </div>
    <div class="zl-calendar-body">
      <div class="weeks">
        <span class="week" v-for="week in weekNames">{{week}}</span>
      </div>
      <div class="dates">
        <div class="week-row" v-for="week in currentDates">
          <span class="week-num">{{week.num}}</span>
          <a class="day-cell" v-for="day in week.days" :class="{'today':day.isToday , 'not-cur-month': !day.isCurMonth}" @click="selectDate">{{day.num}}</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
Date.prototype.format = function(fmt) {
  var o = {
    "M+": this.getMonth() + 1, //月份 
    "d+": this.getDate(), //日 
    "h+": this.getHours(), //小时 
    "m+": this.getMinutes(), //分 
    "s+": this.getSeconds(), //秒 
    "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
    "S": this.getMilliseconds() //毫秒 
  };
  if (/(y+)/.test(fmt)) {
    fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length))
  }
  for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt))
      fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
  return fmt;
}

Date.prototype.getStartDate = function() {
  return new Date(this.getFullYear(), this.getMonth(), 1)
}

Date.prototype.getEndDate = function() {
  let dt = new Date(this.getFullYear(), this.getMonth() + 1 , 1)
  return dt.setDate(dt.getDate() - 1)
}

export default {
  props: {
    currentDate: {
      type: Date,
      default: new Date()
    },
    weekNames: {
      type: Array,
      default: function() {
        return ['周日', '周一', '周二', '周三', '周四', '周五', '周六']
      }
    },
    titleFormat: {
      type: String,
      default: 'yyyy年MM月'
    }
  },
  computed: {
    title() {
      return this.currentDate.format(this.titleFormat)
    },
    currentDates() {
      let startDate = this.currentDate.getStartDate()
      let endDate = this.currentDate.getEndDate()
      let curWeek = startDate.getDay()
      
    }
  },
  methods: {
    goPrevYear() {

    },
    goPrevMonth() {

    },
    goNextYear() {

    },
    goNextMonth() {

    },
    selectDate() {

    }
  }
}

</script>
<style scoped>
.zl-calendar {
  width: 238px;
  height: 245px;
  border: 1px solid #8BA0BC;
  background-color: #CFDDEE;
  font-size: 14px;
}

.zl-calendar-header {
  height: 34px;
  display: flex;
  justify-content: space-around;
  align-items: center;
}

.zl-calendar-header button {
  height: 16px;
  width: 16px;
  background-color: transparent;
  border: 1px solid #8BA0BC;
  border-radius: 8px;
  outline: none;
}

.zl-calendar-body {}

.weeks {
  width: 182px;
  height: 40px;
  border-bottom: 1px solid #B7C8DD;
  margin: 0 20px 0 36px;
  word-wrap: all;
  display: flex;
  justify-content: space-between;
  line-height: 18px;
  text-align: center;
}

.week {}

</style>
