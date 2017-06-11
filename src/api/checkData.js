import Qs from 'qs'
import cfg from './common'
import axios from 'axios'

export default {
  getHisData(cb, sen_id, option) {
      axios.get("/sensors/" + sen_id + "/sen_datas?" + Qs.stringify(option), cfg).then(response => {
        cb(response.data)
      })
    },
    getDoAttrs(cb, option) {
      axios.get("/do_attrs?" + Qs.stringify(option), cfg).then(response => {
        cb(response.data)
      })
    }
}
