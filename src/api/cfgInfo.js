import Qs from 'qs'
import cfg from './common'
import axios from 'axios'

export default {
  getInfo(url, cb) {
      axios.get(url, cfg).then(response => {
        cb(response.data)
      })
    },
    updateInfo(url, data, cb) {
      axios.post(url, Qs.stringify(data), cfg)
        .then(cb)
    },
    getCommuAttrs(cb) {
      axios.get("/commu_proc_attrs", cfg).then(response => {
        cb(response.data)
      })
    },
    postCommuAttrs(cb, data) {
      axios.post("/commu_proc_attrs", Qs.stringify(data), cfg).then(response => {
        cb(response.data)
      })
    }
}
