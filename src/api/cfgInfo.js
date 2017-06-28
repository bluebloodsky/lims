import Qs from 'qs'

export default {
  getInfo(url, cb, option) {
      axios.get(url + "?" + Qs.stringify(option)).then(response => {
        cb(response.data)
      })
    },
    updateInfo(url, data, cb) {
      axios.post(url, Qs.stringify(data))
        .then(cb)
    },
    postInfoByJson(url, data, cb) {
      axios.post(url, JSON.stringify(data))
        .then(cb)
    },
    getCommuAttrs(cb) {
      axios.get("/commu_proc_attrs").then(response => {
        cb(response.data)
      })
    },
    postCommuAttrs(cb, data) {
      axios.post("/commu_proc_attrs", Qs.stringify(data)).then(response => {
        cb(response.data)
      })
    }
}
