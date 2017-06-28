
export default {
  getCacInfo(cb) {
      axios.get("/cac_infos").then(response => {
        cb(response.data)
      })
    },
    getCagInfo(cb) {
      axios.get("/cag_infos").then(response => {
        cb(response.data)
      })
    },
    getSensorInfo(cb) {
      axios.get("/i2_sensors").then(response => {
        cb(response.data)
      })
    },
    delCagInfo(cb, id) {
      axios.delete("/cag_infos/" + id).then(response => {
        cb(response.data)
      })
    },
    updateCacInfo(cb, cac_info) {
      axios.post("/cac_infos", JSON.stringify(cac_info)).then(response => {
        cb(response.data)
      })
    },
    delSensorInfo(cb, id) {
      axios.delete("/i2_sensors/" + id).then(response => {
        cb(response.data)
      })
    },
    addSensorInfo(cb, data) {
      axios.post("/i2_sensors", JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    },
    addCagInfo(cb, data) {
      axios.post("/cag_infos", JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    },
    modifySensorInfo(cb, data) {
      axios.post("/i2_sensors/" + data.sensor_id, JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    },
    modifyCagInfo(cb, data) {
      axios.post("/cag_infos/" + data.cag_id, JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    }
}
