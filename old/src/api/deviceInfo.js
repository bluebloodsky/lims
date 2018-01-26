import axios from '../http'

const _currentData = [{
  "sen_id": "14",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "15",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "16",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "17",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "18",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "19",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "20",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "21",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "22",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "23",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "24",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}, {
  "sen_id": "25",
  "data_time": "2017-07-06 10:10:24",
  "H2Alm": "0",
  "H2Warn": "0",
  "C2H2Alm": "0",
  "C2H2Warn": "0",
  "Mst": "2",
  "H2ppm": "13.1692",
  "COppm": "803.956",
  "CO2ppm": "1409.56",
  "CH4ppm": "8.41384",
  "C2H2ppm": "0",
  "C2H4ppm": "1.09235",
  "C2H6ppm": "2.9792",
  "O2ppm": "1284.82",
  "Hydrocarbon": "12.4854"
}]
export default {
  getDevStatus(cb) {
    //setTimeout(() => cb(_status), 50)
    axios.get("/sensors/sen_basic_status?top=1").then(response => {
      cb(response.data)
    })
  },
  getCurrentData(cb) {
    //setTimeout(() => cb(_currentData), 50)
    axios.get("/sensors/sen_update?import_level=2").then(response => {
      cb(response.data)
    })
  },
  getDevices(cb) {
    axios.get("/sensors/all").then(response => {
      cb(response.data)
    })
  },
  addDevice(cb, device) {
    axios.post("/sensors", JSON.stringify(device)).then(response => {
      cb(response.data)
    })
  },
  modifyDevice(cb, device) {
    axios.post("/sensors/" + device.sen_id, JSON.stringify(device)).then(response => {
      cb(response.data)
    })
  },
  delDevice(cb, sen_id) {
    axios.delete("/sensors/" + sen_id).then(response => {
      cb(response.data)
    })
  }
}
