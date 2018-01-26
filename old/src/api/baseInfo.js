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
    url: 'tpl_manager'
  }, {
    name: '工作流管理',
    level: 1,
    url: 'workflow_manager'
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
  name: 'name',
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
  name: 'name',
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
}, {
  name: 'f1',
  desc: '抗弯负荷',
  type: 'select',
  options: 'seal_type'
}]

const _product_common_attrs = [{
  name: 'lig_imp_vol',
  desc: '雷电冲击电压',
  type: 'input'
}, {
  name: 'step_imp_vol',
  desc: '陡坡冲击电压',
  type: 'input'
}, {
  name: 'ope_imp_vol',
  desc: '操作冲击电压',
  type: 'input'
}, {
  name: 'dc_vol_down',
  desc: '直流参考电压下限',
  type: 'input'
}, {
  name: 'lg_imp_up',
  desc: '直流参考电压上限',
  type: 'input'
}, {
  name: 'pf_ref_vol',
  desc: '工频参考电压',
  type: 'input'
}, {
  name: 'um',
  desc: '设备最高电压',
  type: 'input'
}, {
  name: 'tot_cur_con',
  desc: '全电流持续电流',
  type: 'input'
}, {
  name: 'ris_cur_con',
  desc: '阻性电流持续电流',
  type: 'input'
}, {
  name: 'pf_dry_ws_vol',
  desc: '工频干耐受电压',
  type: 'input'
}, {
  name: 'lig_imp_ws_level',
  desc: '雷电冲击耐受水平',
  type: 'input'
}, {
  name: 'ope_imp_ws_level',
  desc: '操作冲击耐受水平',
  type: 'input'
}, {
  name: 'pf_wet_ws_vol',
  desc: '工频湿耐受电压',
  type: 'input'
}]

const _product_base_attrs = [{
  name: 'product_name',
  desc: '试品名称',
  type: 'table',
  children: [{
    name: 'product_name',
    desc: '试品名称',
    type: 'input'
  }]
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
    label: '复合外套外观检查',
    tpl: `<div>总缺陷面积应不大于外套总表面积的0.2%</div><slot name='wg_data'></slot>`,
    params:[{
      name:'wg_data',
      label:'结果数据',
      type:'table',
      readonly:false,
      options: '',
      cols:[{
        name:'data1',
        label:'温度',
        type:'input',
        readonly:true
      },{
        name:'data2',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data3',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data2',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data3',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data2',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data3',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data2',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data3',
        label:'湿度',
        type:'input',
        readonly:false
      },{
        name:'data2',
        label:'顶层液体温度（℃）',
        type:'input',
        readonly:false
      },{
        name:'data3',
        label:'顶层液体温度（℃）',
        type:'input',
        readonly:false
      },{
        name:'data2',
        label:'顶层液体温度（℃）',
        type:'input',
        readonly:false
      },{
        name:'data3',
        label:'顶层液体温度（℃）',
        type:'input',
        readonly:false
      }]
    }]
  }, {
    id: '2',
    name: 'pdbjjc',
    label: '爬电比距检查',
    tpl: `<div>
    爬电比距≥
    <slot name='pdbj'></slot>mm/kV
  </div>`,
    params: [{
      name: 'pdbj',
      label: '爬电比距',
      type: 'input',
      options: '',
      readonly: false
    }]
  },
  {
    id: "3",
    name: 'zlckdysy',
    label: '直流参考电压试验',
    tpl: `<div>73kV≤U1mA.DC≤76kV</div>`,
    params: [{
      name: 'min',
      label: '最小值',
      type: 'input',
      options: '',
      readonly: false
    }, {
      name: 'max',
      label: '最大值',
      type: 'input',
      options: '',
      readonly: false
    }]
  }, {
    id: '4',
    name: 'cysy',
    label: '残压试验',
    children: [{
      id: '31',
      name: 'ldcjcysy',
      label: '雷电冲击残压试验',
      tpl: `<div>≤ <slot name="ldcjcy"></slot> kVP</div>`,
      params: [{
        name: 'ldcjcy',
        label: '雷电冲击残压',
        type: 'input',
        options: '',
        readonly: true
      }]
    }, {
      id: '32',
      name: 'czcjcysy',
      label: '操作冲击残压试验',
      tpl: `<div>≤ <slot name="czcjcy"></slot> kVP</div>`,
      params: [{
        name: 'czcjcy',
        label: '操作冲击残压',
        type: 'input',
        options: '',
        readonly: false
      }]
    }, {
      id: '33',
      name: 'dbcjcysy',
      label: '陡波冲击残压试验',
      tpl: `<div>≤ <slot name="dbcjcy"></slot> kVP</div>`,
      params: [{
        name: 'dbcjcy',
        label: '陡波冲击残压',
        type: 'input',
        options: '',
        readonly: false
      }]
    }]
  }, {
    id: '5',
    name: 'jxfhsy',
    label: '机械负荷试验',
    children: [{
      id: '51',
      name: 'bend',
      label: '弯曲',
      tpl: `<div>
    <slot name="bend_select_1"></slot>根据抗弯符合F1
    <slot name="bend_f1"></slot>N计算
    <br>
    <slot name="bend_select_2"></slot>宣称整只抗弯强度应不小于
    <slot name="bend_min_f"></slot>kN,
    <slot name="bend_min_s"></slot>s~
    <slot name="bend_max_s"></slot>s,而不破坏
  </div>`,
      params: [{
        name: 'bend_select',
        label: '弯曲参数选择',
        type: 'radio',
        options: 'bend_select',
        readonly: false
      }, {
        name: 'bend_f1',
        label: '抗弯负荷',
        type: 'input',
        options: '',
        readonly: false
      }, {
        name: 'bend_min_f',
        label: '宣称最小强度',
        type: 'input',
        options: '',
        readonly: false
      }, {
        name: 'bend_min_s',
        label: '宣称最小秒数',
        type: 'input',
        options: '',
        readonly: false
      }, {
        name: 'bend_max_s',
        label: '宣称最大秒数',
        type: 'input',
        options: '',
        readonly: false
      }]
    }, {
      id: '52',
      name: 'stretch',
      label: '拉伸',
      tpl: `<div>
    <slot name="stretch_select_1"></slot>根据15倍避雷器自重计算
    <br>
    <slot name="stretch_select_2"></slot>宣称整只拉伸强度应不小于
    <slot name="stretch_min_f"></slot>kN,
    <slot name="stretch_min_s"></slot>s~
    <slot name="stretch_max_s"></slot>s,而不破坏
  </div>`,
      params: [{
        name: 'stretch_select',
        label: '弯曲参数选择',
        type: 'radio',
        options: 'stretch_select',
        readonly: false
      }, {
        name: 'stretch_min_f',
        label: '宣称最小强度',
        type: 'input',
        options: '',
        readonly: false
      }, {
        name: 'stretch_min_s',
        label: '宣称最小秒数',
        type: 'input',
        options: '',
        readonly: false
      }, {
        name: 'stretch_max_s',
        label: '宣称最大秒数',
        type: 'input',
        options: '',
        readonly: false
      }]
    }]
  },
]
const _base_attrs = {
  "test_type": {
    '1': '型式试验',
    '2': '例行检验',
    '3': '特殊试验',
    '4': '性能试验(单项)',
    '5': '性能试验(多项)'
  },
  "report_type": {
    '1': '检测报告',
    '2': '试验报告'
  },
  "report_format": {
    '1': '中文版',
    '2': '英文版'
  },
  "report_unit": {
    '1': '中国电力科学研究院',
    '2': '电力工业电气设备质量检验测试中心'
  },
  "stamp_type": {
    '1': 'CNAS L0699',
    '2': 'CMA 中国电力科学研究院',
    '3': 'CMA 电力工业电气设备质量检验测试中心',
    '4': '检测报告专用章',
    '5': '试验报告专用章',
    '6': '型式评价专用章'
  },
  "come_type": {
    '1': '送样',
    '2': '抽样'
  },
  "comp_do": {
    '1': '自提',
    '2': '自弃',
    '3': '检测方代办托运'
  },
  "product_status": {
    '1': '完好',
    '2': '其他'
  },
  "nom_dis_cur": {
    '1': '1.5',
    '2': '2.5',
    '3': '5',
    '4': '10',
    '5': '20'
  },
  "pol_level": {
    '1': 'Ⅰ',
    '2': 'Ⅱ',
    '3': 'Ⅲ',
    '4': 'Ⅳ'
  },
  "cell_num": {
    '1': '1',
    '2': '2',
    '3': '3',
    '4': '4',
    '5': '5'
  },
  "sea_height": {
    '1': '0',
    '2': '1000',
    '3': '2000',
    '4': '3000',
    '5': '其他'
  },
  "sur_type": {
    '1': '复合',
    '2': '瓷',
    '3': 'GIS',
    '4': '插拔式',
    '5': '外壳不带电'
  },
  "bend_select": {
    "1": "计算值",
    "2": "宣称值"
  },
  "stretch_select": {
    "1": "计算值",
    "2": "宣称值"
  },
  "task_type": {
    '1': '业务流程',
    '2': '实验流程'
  },
  "task_step": {
    "1": '业务受理',
    "2": '协议审核',
    "3": '价格审核',
    "4": '客户确认',
    "5": '任务下发',
    "6": '原始记录编制',
    "7": '原始记录审核',
    "8": '实验报告编制',
    "9": '实验报告审核'
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
  getProductCommonAttrs(cb, option) {
    cb(_product_common_attrs)
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
