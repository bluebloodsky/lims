import cfg from './common'
import axios from 'axios'

export default {
  getMapParams(cb) {
      axios.get("/map_params", cfg).then(response => {
        cb(response.data)
      })
    }
    
}
