import cfg from './common'
import axios from 'axios'
export default {
  getCfgFileList(cb) {
    axios.get("/cfg_filelist", cfg).then(response => {
      cb(response.data)
    })
  }
}
