<template>
  <div class="zl-calendar">
    <div class="zl-calendar-header">
      <div>
        <button @click="goPrevYear"></button>
        <button @click="goPrevMonth"></button>
      </div>
      <span>{{title}}</span>
      <div>
        <button @click="goNextMonth"></button>
        <button @click="goNextYear"></button>
      </div>
    </div>
    <div class="zl-calendar-body">
      <div class="weeks">
        <span class="week" v-for="week in weekNames">{{week}}</span>
      </div>
      <div class="dates">
        <div class="week-row" v-for="week in currentDates">
          <a class="week-num">{{week.num}}</a>
          <a class="day-cell" v-for="day in week.days" :class="{'today':day.isToday , 'not-cur-month': day.isLastMonth || day.isNextMonth}" @click="selectDate(day)">{{day.num}}</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
const DateUtil = {
  getMonthStartDate(date) {
    return new Date(date.getFullYear(), date.getMonth(), 1)
  },
  getMonthEndDate(date) {
    let dt = new Date(date.getFullYear(), date.getMonth() + 1, 1)
    dt.setDate(dt.getDate() - 1)
    return dt
  },
  getLastMonthEndDate(date) {
    let dt = new Date(date.getFullYear(), date.getMonth(), 1)
    dt.setDate(dt.getDate() - 1)
    return dt
  },
  getYearStartDate(date) {
    return new Date(date.getFullYear(), 0, 1)
  }
}

export default {
  props: {
    value: {
      type: Date
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
  data() {
    return {
      currentDate: null
    }
  },
  computed: {
    title() {
      return this.currentDate.format(this.titleFormat)
    },
    currentDates() {
      let dates = []
      let yearStartDate = DateUtil.getYearStartDate(this.currentDate)
      let startDate = DateUtil.getMonthStartDate(this.currentDate)
      let endDate = DateUtil.getMonthEndDate(this.currentDate)
      let yearStartWeek = yearStartDate.getDay() //当年第一天星期几
      let weekDay = startDate.getDay() //本月第一天星期几
      let weekNum = Math.floor(((startDate.getTime() - yearStartDate.getTime()) / 86400000 + yearStartWeek) / 7) + 1
      let [start, end] = [1, endDate.getDate()]

      if (weekDay != 0) { //补充上一个月最后几天
        dates.push({
          num: weekNum++,
          days: []
        })
        let lastMonthEndDate = DateUtil.getLastMonthEndDate(this.currentDate).getDate()
        for (let i = 0; i < weekDay; i++) {
          dates[0].days.unshift({
            num: lastMonthEndDate--,
            isLastMonth: true
          })
        }
      }
      while (start < end + 1) {
        if (weekDay == 0) {
          dates.push({
            num: weekNum++,
            days: []
          })
        }
        dates[dates.length - 1].days.push({
          num: start++
        })
        weekDay = (weekDay + 1) % 7
      }

      if (weekDay != 0) {
        for (let i = 1; i < 8 - weekDay; i++) {
          dates[dates.length - 1].days.push({
            num: i,
            isNextMonth: true
          })
        }
      }

      return dates
    }
  },
  created() {
    this.currentDate = this.value ? new Date(this.value) : new Date()
  },
  methods: {
    goPrevYear() {
      let newDate = new Date(this.currentDate.getFullYear() - 1, this.currentDate.getMonth(), 1)
      this.$set(this, "currentDate", newDate)
    },
    goPrevMonth() {
      let newDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1)
      this.$set(this, "currentDate", newDate)
    },
    goNextYear() {
      let newDate = new Date(this.currentDate.getFullYear() + 1, this.currentDate.getMonth(), 1)
      this.$set(this, "currentDate", newDate)
    },
    goNextMonth() {
      let newDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1)
      this.$set(this, "currentDate", newDate)
    },
    selectDate(day) {
      let monthDiff = day.isLastMonth ? -1 : day.isNextMonth ? 1 : 0
      let newDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + monthDiff , day.num)
      this.$emit("input" , newDate)
    }
  }
}

</script>
<style scoped>
.zl-calendar {
  width: 238px;
  border: 1px solid #8BA0BC;
  background-color: #CFDDEE;
  font-size: 14px;
  height: auto;
  padding-bottom: 15px;
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
  display: flex;
  justify-content: space-between;
  line-height: 18px;
  text-align: center;
}

.week-row {
  width: 220px;
  height: 30px;
  margin-right: 30px;
  display: flex;
  justify-content: space-between;
  text-align: center;
}

.day-cell {
  width: 26px;
}

.week-num {
  width: 35px;
  color: gray;
  pointer-events: none;
  cursor: default;
  opacity: 0.6;
}

.not-cur-month {
  color: gray;
}

</style>
