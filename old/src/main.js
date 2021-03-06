// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import axios from './http'
import tabs from './tabs'
import store from './store'
import App from './App'
import ElementUI from 'element-ui'
import 'element-theme-default'
import fullCalendar from 'vue-fullcalendar'

Date.prototype.Format = function(fmt) {
  var o = {
    "M+": this.getMonth() + 1, //月份 
    "d+": this.getDate(), //日 
    "h+": this.getHours(), //小时 
    "m+": this.getMinutes(), //分 
    "s+": this.getSeconds(), //秒 
    "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
    "S": this.getMilliseconds() //毫秒 
  };
  if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
  for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
  return fmt;
}

Vue.config.productionTip = false
Vue.prototype.cfgInfo = cfgInfo
Vue.prototype.axios = axios
Vue.use(ElementUI)

Vue.component('full-calendar', fullCalendar)
/* eslint-disable no-new */
new Vue({
  el: '#app',
  taber: tabs,
  store,
  render: h => h(App)
})
