import Qs from 'qs'
import axios from '../http'

const _menuItems = [{
  name: '业务管理',
  level: 0,
  items: [{
    name: '业务受理',
    level: 0,
    url: 'pro_rec'
  }, {
    name: '待办业务',
    level: 0,
    url: 'pro_todo'
  }, {
    name: '已办业务',
    level: 0,
    url: 'pro_done'
  }, {
    name: '业务单查询',
    level: 0,
    url: 'his_data'
  }]
}, {
  name: '客户服务',
  level: 2,
  items: [{
    name: '收费状态',
    level: 0,
    url: 'real_data'
  }, {
    name: '客户管理',
    level: 0,
    url: 'his_data'
  }, {
    name: '协议管理',
    level: 0,
    url: 'his_data'
  }]
}, {
  name: '样品管理',
  level: 1,
  url: 'monitor_device'
}, {
  name: '体系管理',
  level: 1,
  items: [{
    name: '收费标准',
    level: 1,
    url: 'pro_ied'
  }, {
    name: '设备管理',
    level: 1,
    url: 'i2_upload'
  }, {
    name: '文件管理',
    level: 1,
    url: 'file_manager'
  }, {
    name: '模板管理',
    level: 1,
    url: 'file_manager'
  }, {
    name: '内审管理',
    level: 1,
    url: 'file_manager'
  }, {
    name: '人事管理',
    level: 1,
    url: 'file_manager'
  }]
}, {
  name: '权限管理',
  level: 0,
  items: [{
    name: '用户管理',
    level: 1,
    url: 'time_set'
  }, {
    name: '部门管理',
    level: 1,
    url: 'net_set'
  }, {
    name: '角色管理',
    level: 1,
    url: 'restart'
  }, {
    name: '分组管理',
    level: 1,
    url: 'restart'
  }]
}]

const _client_attrs = [{
  name: 'del_name',
  desc: '委托单位',
  type: 'input'
}, {
  name: 'address',
  desc: '联系地址',
  type: 'input'
}, {
  name: 'email',
  desc: '邮箱',
  type: 'input'
}, {
  name: 'linkman',
  desc: '联系人',
  type: 'input'
}, {
  name: 'tel',
  desc: '电话/传真',
  type: 'input'
}, {
  name: 'postcode',
  desc: '邮编',
  type: 'input'
}]

const _service_attrs = [{
  name: 'service_name',
  desc: '检测单位',
  type: 'input'
}, {
  name: 'address',
  desc: '联系地址',
  type: 'input'
}, {
  name: 'email',
  desc: '邮箱',
  type: 'input'
}, {
  name: 'linkman',
  desc: '联系人',
  type: 'input'
}, {
  name: 'tel',
  desc: '电话/传真',
  type: 'input'
}, {
  name: 'open_unit',
  desc: '开户单位',
  type: 'input'
}, {
  name: 'postcode',
  desc: '邮编',
  type: 'input'
}, {
  name: 'bank_account',
  desc: '银行账户',
  type: 'input'
}, {
  name: 'open_bank',
  desc: '开户银行',
  type: 'input'
}, {
  name: 'tax_num',
  desc: '税号',
  type: 'input'
}]

const _protocol_attrs = [{
  name: 'report_type',
  desc: '报告类型',
  type: 'select',
  options: 'report_type'
}, {
  name: 'report_unit',
  desc: '出具报告机构名称',
  type: 'select',
  options: 'report_unit'
}, {
  name: 'test_type',
  desc: '检测类别',
  type: 'checkbox',
  options: 'test_type'
}, {
  name: 'stamp_type',
  desc: '印章类型',
  type: 'checkbox',
  options: 'stamp_type'
}, {
  name: 'report_format',
  desc: '报告版式',
  type: 'select',
  options: 'report_format'
}, {
  name: 'report_num',
  desc: '份数',
  type: 'input',
}, {
  name: 'test_money',
  desc: '检测费用',
  type: 'input',
}]

const _product_import_attrs = [{
  name: 'rat_vol',
  desc: '额定电压(kV)',
  type: 'input'
}, {
  name: 'con_run_vol',
  desc: '持续运行电压(kV)',
  type: 'input'
}, {
  name: 'nom_dis_cur',
  desc: '标称放电电流(kA)',
  type: 'select',
  options: 'nom_dis_cur'
}, {
  name: 'pol_level',
  desc: '污秽等级',
  type: 'select',
  options: 'pol_level'
}, {
  name: 'cell_num',
  desc: '元件个数',
  type: 'select',
  options: 'cell_num'
}, {
  name: 'sea_height',
  desc: '海拔高度',
  type: 'select',
  options: 'sea_height'
}, {
  name: 'sur_type',
  desc: '外套类型',
  type: 'select',
  options: 'sur_type'
}, {
  name: 'seal_type',
  desc: '密封类型',
  type: 'select',
  options: 'seal_type'
}, {
  name: 'use_place',
  desc: '应用场所',
  type: 'select',
  options: 'use_place'
}, {
  name: 'dc_ref_cur',
  desc: '直流参考电流',
  type: 'input'
}, {
  name: 'pf_ref_cur',
  desc: '工频参考电流',
  type: 'input'
}]

const _product_base_attrs = [{
  name: 'product_name',
  desc: '试品名称',
  type: 'input'
}, {
  name: 'product_model',
  desc: '型号规格',
  type: 'input'
}, {
  name: 'manufacture',
  desc: '制造单位',
  type: 'input'
}, {
  name: 'come_type',
  desc: '来样方式',
  type: 'select',
  options: 'come_type'
}, {
  name: 'comp_do',
  desc: '试毕处置',
  type: 'select',
  options: 'comp_do'
}, {
  name: 'attach_file',
  desc: '附带资料',
  type: 'input'
}, {
  name: 'get_date',
  desc: '收样日期',
  type: 'date'
}, {
  name: 'product_status',
  desc: '样品外观',
  type: 'select',
  options: 'product_status'
}, {
  name: 'product_num',
  desc: '样品数量',
  type: 'input'
}]

const _test_item_attrs = [{
  id: '1',
  name: 'fhwtwgjc',
  desc: '复合外套外观检查',
}, {
  id: '2',
  name: 'pdbjjc',
  desc: '爬电比距检查',
  params: [{
    name: 'pdbj',
    desc: '爬电比距',
    unit: 'kV',
    type: 'input'
  }]
}, {
  id: '3',
  name: 'cysy',
  desc: '残压试验',
  subItems: [{
    name: 'ldcjcysy',
    desc: '雷电冲击残压试验',
    params: [{
      name: 'cy',
      desc: '残压',
      type: 'input',
      unit: 'kVp'
    }]
  }, {
    name: 'cxcjcysy',
    desc: '操作冲击残压试验',
    params: [{
      name: 'cy',
      desc: '残压',
      type: 'input',
      unit: 'kVp'
    }]
  }, {
    name: 'dbcjcysy',
    desc: '陡波冲击残压试验',
    params: [{
      name: 'cy',
      desc: '残压',
      type: 'input',
      unit: 'kVp'
    }]
  }]
}]
const _base_attrs = {
  test_type: {
    '1': '型式试验',
    '2': '例行检验',
    '3': '特殊试验',
    '4': '性能试验(单项)',
    '5': '性能试验(多项)'
  },
  report_type: {
    '1': '检测报告',
    '2': '试验报告'
  },
  report_format: {
    '1': '中文版',
    '2': '英文版'
  },
  report_unit: {
    '1': '中国电力科学研究院',
    '2': '电力工业电气设备质量检验测试中心'
  },
  stamp_type: {
    '1': 'CNAS L0699',
    '2': 'CMA 中国电力科学研究院',
    '3': 'CMA 电力工业电气设备质量检验测试中心',
    '4': '检测报告专用章',
    '5': '试验报告专用章',
    '6': '型式评价专用章'
  },
  come_type: {
    '1': '送样',
    '2': '抽样'
  },
  comp_do: {
    '1': '自提',
    '2': '自弃',
    '3': '检测方代办托运'
  },
  product_status: {
    '1': '完好',
    '2': '其他'
  },
  nom_dis_cur: {
    '1': '1.5',
    '2': '2.5',
    '3': '5',
    '4': '10',
    '5': '20'
  },
  pol_level: {
    '1': 'Ⅰ',
    '2': 'Ⅱ',
    '3': 'Ⅲ',
    '4': 'Ⅳ'
  },
  cell_num: {
    '1': '1',
    '2': '2',
    '3': '3',
    '4': '4',
    '5': '5'
  },
  sea_height: {
    '1': '0',
    '2': '1000',
    '3': '2000',
    '4': '3000',
    '5': '其他'
  },
  sur_type: {
    '1': '复合',
    '2': '瓷',
    '3': 'GIS',
    '4': '插拔式',
    '5': '外壳不带电'
  }
}
export default {
  getBaseAttrs(cb) {
    cb(_base_attrs)
  },
  getClientAttrs(cb, option) {
    cb(_client_attrs)
  },
  getServiceAttrs(cb, option) {
    cb(_service_attrs)
  },
  getProtocolAttrs(cb, option) {
    cb(_protocol_attrs)
  },
  getProductBaseAttrs(cb, option) {
    cb(_product_base_attrs)
  },
  getProductImportAttrs(cb, option) {
    cb(_product_import_attrs)
  },
  getTestItemAttrs(cb, option) {
    cb(_test_item_attrs)
  },
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
