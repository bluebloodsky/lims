import Qs from 'qs'
import axios from '../http'

const _menuItems = [{
  name: '状态总览',
  url: '/status'
}, {
  name: '数据查看',
  items: [{
    name: '实时数据',
    url: '/real_data'
  }, {
    name: '历史数据',
    url: '/his_data'
  }]
}, {
  name: '报警记录',
  url: '/alarm'
}, {
  name: '配置总览',
  items: [{
    name: '监测设备管理',
    url: '/monitor_device'
  }, {
    name: 'I2台帐录入',
    url: '/i2_device'
  }, {
    name: '参数配置',
    url: '/config_param'
  }, {
    name: 'mms配置',
    url: '/mms_config'
  }]
}, {
  name: '程序调试',
  items: [{
    name: '采集程序调试',
    url: '/pro_ied'
  }, {
    name: 'I2上传调试',
    url: '/i2_upload'
  }, {
    name: '文件管理',
    url: '/file_manager'
  }]
}]
export default {
  getMapParams(cb, option) {
      axios.get("/map_params?" + Qs.stringify(option)).then(response => {
        cb(response.data)
      })
    },
    getMenuInfo(cb) {
      cb(_menuItems)
    },
    getMonitorParams(cb, option) {
      axios.get("/do_attrs" + Qs.stringify(option)).then(response => {
        cb(response.data)
      })
    },
    getI2Params(cb, option) {
      axios.get("/i2_params" + Qs.stringify(option)).then(response => {
        cb(response.data)
      })
    },
    delMonitorParam(cb, id) {
      axios.delete("/do_attrs/" + id).then(response => {
        cb(response.data)
      })
    },
    delI2Param(cb, id) {
      axios.delete("/i2_params/" + id).then(response => {
        cb(response.data)
      })
    },
    addMonitorParam(cb, data) {
      axios.post("/do_attrs", JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    },
    modifyMonitorParam(cb, data) {
      axios.post("/do_attrs/" + data.do_id, JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    },
    addI2Param(cb, data) {
      axios.post("/i2_params", JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    },
    modifyI2Param(cb, data) {
      axios.post("/i2_params/" + data.i2_paramgroup_id, JSON.stringify(data)).then(response => {
        cb(response.data)
      })
    }
}
