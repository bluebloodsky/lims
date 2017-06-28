
export default {
  getCfgFileList(cb) {
    axios.get("/cfg_filelist").then(response => {
      cb(response.data)
    })
  }
}
