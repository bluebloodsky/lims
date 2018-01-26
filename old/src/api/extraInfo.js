import axios from '../http'
export default {
  getNetCfg(cb) {
    axios.get("/net_set").then(response => {
      cb(response.data)
    })
  },
  setNetCfg(cb, data) {
    axios.post("/net_set", JSON.stringify(data)).then(response => {
      cb(response.data)
    })
  },
  getTime(cb) {
    axios.get("/sys_time").then(response => {
      cb(response.data)
    })
  },
  setTime(cb, data) {
    axios.post("/sys_time", JSON.stringify(data)).then(response => {
      cb(response.data)
    })
  },
  getLocalTime() {
    var time = new Date();
    var ymd = "";
    ymd += time.getFullYear() + "-";
    ymd += (time.getMonth() + 1) + "-";
    ymd += time.getDate();

    var hour = (time.getHours() < 10) ? ("0" + time.getHours()) : time.getHours();
    var min = (time.getMinutes() < 10) ? ("0" + time.getMinutes()) : time.getMinutes();
    var sec = (time.getSeconds() < 10) ? ("0" + time.getSeconds()) : time.getSeconds();
    ymd += " " + hour + ":";
    ymd += min + ":";
    ymd += sec;

    return ymd;
  },
  DateToUnix(string) {
    var f = string.split(' ', 2);
    var d = (f[0] ? f[0] : '').split('-', 3);
    var t = (f[1] ? f[1] : '').split(':', 3);
    return (new Date(
      parseInt(d[0], 10) || null,
      (parseInt(d[1], 10) || 1) - 1,
      parseInt(d[2], 10) || null,
      parseInt(t[0], 10) || null,
      parseInt(t[1], 10) || null,
      parseInt(t[2], 10) || null
    )).getTime() / 1000;
  },
  UnixToDate(unixTime, isFull, timeZone) {
    if (typeof(timeZone) == 'number') {
      unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
    }
    var time = new Date(unixTime * 1000);
    var ymdhis = "";
    ymdhis += time.getUTCFullYear() + "-";
    ymdhis += (time.getUTCMonth() + 1) + "-";
    ymdhis += time.getUTCDate();
    if (isFull === true) {
      var hour = (time.getUTCHours() < 10) ? ("0" + time.getUTCHours()) : time.getUTCHours();
      var min = (time.getUTCMinutes() < 10) ? ("0" + time.getUTCMinutes()) : time.getUTCMinutes();
      var sec = (time.getUTCSeconds() < 10) ? ("0" + time.getUTCSeconds()) : time.getUTCSeconds();
      ymdhis += " " + hour + ":";
      ymdhis += min + ":";
      ymdhis += sec;
    }
    return ymdhis;
  },
  checkDate(date) {
    var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
    if (DATE_FORMAT.test(date)) {
      return true;
    } else {
      return false;
    }
  },
  checkTime(time) {

    var regTime = /^([0-2][0-9]):([0-5][0-9]):([0-5][0-9])$/;
    var result = false;
    if (regTime.test(time)) {
      if ((parseInt(RegExp.$1) < 24) && (parseInt(RegExp.$2) < 60) && (parseInt(RegExp.$3) < 60)) {
        result = true;
      }
    }
    return result;
  },
  checkDateTime(string) {
    var f = string.split(' ', 2);
    if ((f.length == 2) && this.checkDate(f[0]) && this.checkTime(f[1])) {
      return true;
    } else {
      return false;
    }
  }
}
