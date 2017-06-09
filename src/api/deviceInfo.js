import cfg from './common'
import axios from 'axios'
export default {
  getDevStatus(cb) {
      //setTimeout(() => cb(_status), 50)
      axios.get("/sensors/sen_basic_status?top=1", cfg).then(response => {
        cb(response.data)
      })
    },
    getCurrentData(cb) {
      //setTimeout(() => cb(_currentData), 50)
      axios.get("/sensors/sen_datas?top=1&&import_level=2", cfg).then(response => {
        cb(response.data)
      })
    },
    getDevices(cb) {
      axios.get("/sensors/all", cfg).then(response => {
        cb(response.data)
      })
    },
    addDevice(cb, device) {
      axios.post("/sensors", JSON.stringify(device), cfg).then(response => {
        cb(response.data)
      })
    },
    modifyDevice(cb, device) {
      axios.post("/sensors/" + device.sen_id, JSON.stringify(device), cfg).then(response => {
        cb(response.data)
      })
    },
    delDevice(cb, sen_id) {
      axios.delete("/sensors/" + sen_id, cfg).then(response => {
        cb(response.data)
      })
    }
}
