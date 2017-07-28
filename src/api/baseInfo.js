import Qs from 'qs'
import axios from '../http'

const _menuItems = [{
  name: '业务管理',
  level: 0,
  items: [{
    name: '业务受理',
    level: 0,
    url: '/pro_rec'
  }, {
    name: '待办业务',
    level: 0,
    url: '/pro_todo'
  }, {
    name: '已办业务',
    level: 0,
    url: '/pro_done'
  }, {
    name: '业务单查询',
    level: 0,
    url: '/his_data'
  }]
}, {
  name: '客户服务',
  level: 2,
  items: [{
    name: '收费状态',
    level: 0,
    url: '/real_data'
  }, {
    name: '客户管理',
    level: 0,
    url: '/his_data'
  }, {
    name: '协议管理',
    level: 0,
    url: '/his_data'
  }]
}, {
  name: '样品管理',
  level: 1,
  url: '/monitor_device'
}, {
  name: '体系管理',
  level: 1,
  items: [{
    name: '收费标准',
    level: 1,
    url: '/pro_ied'
  }, {
    name: '设备管理',
    level: 1,
    url: '/i2_upload'
  }, {
    name: '文件管理',
    level: 1,
    url: '/file_manager'
  }, {
    name: '模板管理',
    level: 1,
    url: '/file_manager'
  }, {
    name: '内审管理',
    level: 1,
    url: '/file_manager'
  }, {
    name: '人事管理',
    level: 1,
    url: '/file_manager'
  }]
}, {
  name: '权限管理',
  level: 0,
  items: [{
      name: '用户管理',
      level: 1,
      url: '/time_set'
    }, {
      name: '部门管理',
      level: 1,
      url: '/net_set'
    }, {
      name: '角色管理',
      level: 1,
      url: '/restart'
    }, {
      name: '分组管理',
      level: 1,
      url: '/restart'
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
