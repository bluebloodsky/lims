import Qs from 'qs'
import axios from '../http'

export default {
  getHisData(cb, sen_id, option) {
    axios.get("/sensors/" + sen_id + "/sen_datas?" + Qs.stringify(option)).then(response => {
      cb(response.data)
    })
  },
  getDoAttrs(cb, option) {
    axios.get("/do_attrs?" + Qs.stringify(option)).then(response => {
      cb(response.data)
    })
  }
}
