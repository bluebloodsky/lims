import Qs from 'qs'
import axios from '../http'

const _menuItems = [{
  name: '业务管理',
  level: 0,
  items: [{
    name: '业务受理',
    level: 0,
    url: 'pro_rec',
    component: 'PageProRec'
  }, {
    name: '待办业务',
    level: 0,
    url: 'pro_todo',
    component: 'PageProTodo'
  }, {
    name: '已办业务',
    level: 0,
    url: 'pro_done',
    component: 'PageProDone'
  }, {
    name: '业务单查询',
    level: 0,
    url: 'pro_check',
    component: 'PageProCheck'
  }]
}, {
  name: '客户服务',
  level: 2,
  items: [{
    name: '收费状态',
    level: 0,
    url: 'money_status',
    component: 'PageMoneyStatus'
  }, {
    name: '客户管理',
    level: 0,
    url: 'client_manager',
    component: 'PageClientManager'
  }, {
    name: '协议管理',
    level: 0,
    url: 'protocol_manager',
    component: 'PageProtocolManager'
  }]
}, {
  name: '样品管理',
  level: 1,
  url: 'sample_manager',
  component: 'PageSampleManager'
}, {
  name: '体系管理',
  level: 1,
  items: [{
    name: '设备管理',
    level: 1,
    url: 'instr_manager',
    component: 'PageInstrManager'
  }, {
    name: '文件管理',
    level: 1,
    url: 'file_manager',
    component: 'PageFileManager'
  }, {
    name:'属性配置',
    url:'attr_config',
    component:'PageAttrConfig'
  },{
    name: '模板管理',
    level: 1,
    url: 'tpl_manager',
    component: 'PageTplManager'
  }, {
    name: '工作流管理',
    level: 1,
    url: 'workflow_manager',
    component: 'PageWorkflowManager'
  }, {
    name: '人事管理',
    level: 1,
    url: 'person_manager',
    component: 'PagePersonManager'
  }]
}, {
  name: '权限管理',
  level: 0,
  items: [{
    name: '用户管理',
    level: 1,
    url: 'user_manager',
    component: 'PageUserManager'
  }, {
    name: '部门管理',
    level: 1,
    url: 'department_manager',
    component: 'PageDepartmentManager'
  }, {
    name: '角色管理',
    level: 1,
    url: 'role_manager',
    component: 'PageRoleManager'
  }]
}]

const _client_attrs = [{
  name: 'name',
  name_cn: '委托单位',
  type: 'input'
}, {
  name: 'address',
  name_cn: '联系地址',
  type: 'input'
}, {
  name: 'email',
  name_cn: '邮箱',
  type: 'input'
}, {
  name: 'linkman',
  name_cn: '联系人',
  type: 'input'
}, {
  name: 'tel',
  name_cn: '电话/传真',
  type: 'input'
}, {
  name: 'postcode',
  name_cn: '邮编',
  type: 'input'
}]

const _service_attrs = [{
  name: 'name',
  name_cn: '检测单位',
  type: 'input'
}, {
  name: 'address',
  name_cn: '联系地址',
  type: 'input'
}, {
  name: 'email',
  name_cn: '邮箱',
  type: 'input'
}, {
  name: 'linkman',
  name_cn: '联系人',
  type: 'input'
}, {
  name: 'tel',
  name_cn: '电话/传真',
  type: 'input'
}, {
  name: 'open_unit',
  name_cn: '开户单位',
  type: 'input'
}, {
  name: 'postcode',
  name_cn: '邮编',
  type: 'input'
}, {
  name: 'bank_account',
  name_cn: '银行账户',
  type: 'input'
}, {
  name: 'open_bank',
  name_cn: '开户银行',
  type: 'input'
}, {
  name: 'tax_num',
  name_cn: '税号',
  type: 'input'
}]

const _protocol_attrs = [{
  name: 'report_type',
  name_cn: '报告类型',
  type: 'select',
  options: 'report_type'
}, {
  name: 'report_unit',
  name_cn: '出具报告机构名称',
  type: 'select',
  options: 'report_unit'
}, {
  name: 'test_type',
  name_cn: '检测类别',
  type: 'checkbox',
  options: 'test_type'
}, {
  name: 'stamp_type',
  name_cn: '印章类型',
  type: 'checkbox',
  options: 'stamp_type'
}, {
  name: 'report_format',
  name_cn: '报告版式',
  type: 'select',
  options: 'report_format'
}, {
  name: 'report_num',
  name_cn: '份数',
  type: 'input',
}, {
  name: 'test_money',
  name_cn: '检测费用',
  type: 'input',
}]

const _product_import_attrs = [{
  name: 'rat_vol',
  name_cn: '额定电压(kV)',
  type: 'input'
}, {
  name: 'con_run_vol',
  name_cn: '持续运行电压(kV)',
  type: 'input'
}, {
  name: 'nom_dis_cur',
  name_cn: '标称放电电流(kA)',
  type: 'select',
  options: 'nom_dis_cur'
}, {
  name: 'pol_level',
  name_cn: '污秽等级',
  type: 'select',
  options: 'pol_level'
}, {
  name: 'cell_num',
  name_cn: '元件个数',
  type: 'select',
  options: 'cell_num'
}, {
  name: 'sea_height',
  name_cn: '海拔高度',
  type: 'select',
  options: 'sea_height'
}, {
  name: 'sur_type',
  name_cn: '外套类型',
  type: 'select',
  options: 'sur_type'
}, {
  name: 'seal_type',
  name_cn: '密封类型',
  type: 'select',
  options: 'seal_type'
}, {
  name: 'use_place',
  name_cn: '应用场所',
  type: 'select',
  options: 'use_place'
}, {
  name: 'dc_ref_cur',
  name_cn: '直流参考电流',
  type: 'input'
}, {
  name: 'pf_ref_cur',
  name_cn: '工频参考电流',
  type: 'input'
}, {
  name: 'f1',
  name_cn: '抗弯负荷',
  type: 'select',
  options: 'seal_type'
}]

const _product_common_attrs = [{
  name: 'lig_imp_vol',
  name_cn: '雷电冲击电压',
  type: 'input'
}, {
  name: 'step_imp_vol',
  name_cn: '陡坡冲击电压',
  type: 'input'
}, {
  name: 'ope_imp_vol',
  name_cn: '操作冲击电压',
  type: 'input'
}, {
  name: 'dc_vol_down',
  name_cn: '直流参考电压下限',
  type: 'input'
}, {
  name: 'lg_imp_up',
  name_cn: '直流参考电压上限',
  type: 'input'
}, {
  name: 'pf_ref_vol',
  name_cn: '工频参考电压',
  type: 'input'
}, {
  name: 'um',
  name_cn: '设备最高电压',
  type: 'input'
}, {
  name: 'tot_cur_con',
  name_cn: '全电流持续电流',
  type: 'input'
}, {
  name: 'ris_cur_con',
  name_cn: '阻性电流持续电流',
  type: 'input'
}, {
  name: 'pf_dry_ws_vol',
  name_cn: '工频干耐受电压',
  type: 'input'
}, {
  name: 'lig_imp_ws_level',
  name_cn: '雷电冲击耐受水平',
  type: 'input'
}, {
  name: 'ope_imp_ws_level',
  name_cn: '操作冲击耐受水平',
  type: 'input'
}, {
  name: 'pf_wet_ws_vol',
  name_cn: '工频湿耐受电压',
  type: 'input'
}]

const _product_base_attrs = [{
  name: 'product_name',
  name_cn: '试品名称',
  type: 'table',
  children: [{
    name: 'product_name',
    name_cn: '试品名称',
    type: 'input'
  }]
}, {
  name: 'product_model',
  name_cn: '型号规格',
  type: 'input'
}, {
  name: 'manufacture',
  name_cn: '制造单位',
  type: 'input'
}, {
  name: 'come_type',
  name_cn: '来样方式',
  type: 'select',
  options: 'come_type'
}, {
  name: 'comp_do',
  name_cn: '试毕处置',
  type: 'select',
  options: 'comp_do'
}, {
  name: 'attach_file',
  name_cn: '附带资料',
  type: 'input'
}, {
  name: 'get_date',
  name_cn: '收样日期',
  type: 'date'
}, {
  name: 'product_status',
  name_cn: '样品外观',
  type: 'select',
  options: 'product_status'
}, {
  name: 'product_num',
  name_cn: '样品数量',
  type: 'input'
}]

const _test_item_attrs = [{
  id: '1',
  name: 'fhwtwgjc',
  label: '复合外套外观检查',
  tpl: `<div>总缺陷面积应不大于外套总表面积的0.2%</div>`,
  params: [{
    name: 'wg_data',
    label: '结果数据',
    type: 'table',
    readonly: false,
    options: '',
    cols: [{
      name: 'data1',
      label: '温度',
      type: 'input',
      readonly: true
    }, {
      name: 'data2',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data3',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data2',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data3',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data2',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data3',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data2',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data3',
      label: '湿度',
      type: 'input',
      readonly: false
    }, {
      name: 'data2',
      label: '顶层液体温度（℃）',
      type: 'input',
      readonly: false
    }, {
      name: 'data3',
      label: '顶层液体温度（℃）',
      type: 'input',
      readonly: false
    }, {
      name: 'data2',
      label: '顶层液体温度（℃）',
      type: 'input',
      readonly: false
    }, {
      name: 'data3',
      label: '顶层液体温度（℃）',
      type: 'input',
      readonly: false
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
const _base_attrs = [{
  name: 'test_type',
  name_cn: '试验类型',
  options: ['型式试验', '例行检验', '特殊试验', '性能试验(单项)', '性能试验(多项)']
}, {
  name: 'report_type',
  name_cn: '报告类型',
  options: ['检测报告', '试验报告']
}, {
  name: 'report_format',
  name_cn: '报告版式',
  options: ['中文版', '英文版']
}, {
  name: 'report_unit',
  name_cn: '报告机构',
  options: ['中国电力科学研究院', '电力工业电气设备质量检验测试中心']
}, {
  name: 'stamp_type',
  name_cn: '印章类型',
  options: ['CNAS L0699',
    'CMA 中国电力科学研究院',
    'CMA 电力工业电气设备质量检验测试中心',
    '检测报告专用章', '试验报告专用章', '型式评价专用章'
  ]
}, {
  name: 'come_type',
  name_cn: '来样方式',
  options: ['送样', '抽样']
}, {
  name: 'comp_do',
  name_cn: '试毕处置',
  options: ['自提', '自弃', '检测方代办托运']
}, {
  name: 'product_status',
  name_cn: '样品外观',
  options: ['完好', '其他']
}, {
  name: 'nom_dis_cur',
  name_cn: '标称放电电流',
  options: [1.5, 2.5, 5, 10, 20]
}, {
  name: 'pol_level',
  name_cn: '污秽等级',
  options: ['Ⅰ', 'Ⅱ', 'Ⅲ', 'Ⅳ']
}, {
  name: 'cell_num',
  name_cn: '元器件个数',
  options: [1, 2, 3, 4, 5]
}, {
  name: 'sea_height',
  name_cn: '海拔高度',
  options: ['0', '1000', '2000', '3000', '其他']
}, {
  name: 'sur_type',
  name_cn: '外套类型',
  options: ['复合', '瓷', 'GIS', '插拔式', '外壳不带电']
}]
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
