db = connect("localhost:27017/lims")

/*试验室*/
db.dept.drop()
db.dept.insertMany([{
  name: 'blq',
  name_cn: '避雷器站',
  type: 'testStation'
}, {
  name: 'byq',
  name_cn: '变压器站',
  type: 'testStation'
}, {
  name: 'hgq',
  name_cn: '互感器站',
  type: 'testStation'
}, {
  name: 'ywb',
  name_cn: '业务管理',
  type: 'manage'
}])

db.user.drop()
db.user.insertMany([{
  username: 'admin',
  name_cn: '管理员',
  dept: 'ywb',
  role: 'administrator',
  pwd: '123'
}, {
  username: 'blq_admin',
  name_cn: '避雷器管理员',
  dept: 'blq',
  role: 'administrator',
  pwd: '123'
}, {
  username: 'byq_admin',
  name_cn: '变压器管理员',
  dept: 'byq',
  role: 'administrator',
  pwd: '123'
}, {
  username: 'hgq_admin',
  name_cn: '互感器管理员',
  dept: 'blq',
  role: 'administrator',
  pwd: '123'
}])
/*菜单*/
db.menu.drop()
db.menu.insertMany([{
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
    url: 'pro_check'
  }]
}, {
  name: '客户服务',
  level: 2,
  items: [{
    name: '收费状态',
    level: 0,
    url: 'money_status'
  }, {
    name: '客户管理',
    level: 0,
    url: 'client_manager'
  }, {
    name: '协议管理',
    level: 0,
    url: 'protocol_manager'
  }]
}, {
  name: '样品管理',
  level: 1,
  url: 'sample_manager'
}, {
  name: '体系管理',
  level: 1,
  items: [{
    name: '设备管理',
    level: 1,
    url: 'instr_manager'
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
    name: '人事管理',
    level: 1,
    url: 'person_manager'
  }]
}, {
  name: '权限管理',
  level: 0,
  items: [{
    name: '用户管理',
    level: 1,
    url: 'user_manager'
  }, {
    name: '部门管理',
    level: 1,
    url: 'department_manager'
  }, {
    name: '角色管理',
    level: 1,
    url: 'role_manager'
  }]
}])

/*基本属性*/
db.blq.baseattr.drop()
db.blq.baseattr.insertMany([{
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
}])
