db = connect("localhost:27017/lims")
db.dropDatabase()
/*试验室*/
db.depts.drop()
db.depts.insertMany([{
    name: 'blq',
    name_cn: '避雷器站',
    type: 'lab'
}, {
    name: 'byq',
    name_cn: '变压器站',
    type: 'lab'
}, {
    name: 'hgq',
    name_cn: '互感器站',
    type: 'lab'
}, {
    name: 'ywb',
    name_cn: '业务管理',
    type: 'manage'
}])

db.users.drop()
db.users.insertMany([{
    username: 'admin',
    name_cn: '管理员',
    dept: 'ywb',
    role: 'admin',
    pwd: '123'
}, {
    username: 'blq_admin',
    name_cn: '避雷器管理员',
    dept: 'blq',
    role: 'admin',
    pwd: '123'
}, {
    username: 'byq_admin',
    name_cn: '变压器管理员',
    dept: 'byq',
    role: 'admin',
    pwd: '123'
}, {
    username: 'hgq_admin',
    name_cn: '互感器管理员',
    dept: 'blq',
    role: 'admin',
    pwd: '123'
}])
/*菜单*/
db.menus.drop()
db.menus.insertMany([{
    name: '业务管理',
    items: [{
        name: '业务受理',
        url: 'order-rec'
    }, {
        name: '待办业务',
        url: 'order-todo'
    }, {
        name: '已办业务',
        url: 'order-done'
    }, {
        name: '业务单查询',
        url: 'order-check'
    }]
}, {
    name: '客户服务',
    items: [{
        name: '收费状态',
        url: 'money-status'
    }, {
        name: '客户管理',
        url: 'client-manager'
    }, {
        name: '协议管理',
        url: 'protocol-manager'
    }]
}, {
    name: '样品管理',
    url: 'sample-manager'
}, {
    name: '体系管理',
    items: [{
        name: '设备管理',
        url: 'instr-manager'
    }, {
        name: '文件管理',
        url: 'file-manager'
    }, {
        name: '属性配置',
        url: 'attr-config'
    }, {
        name: '模板管理',
        url: 'tpl-manager'
    }, {
        name: '工作流管理',
        url: 'workflow-manager'
    }, {
        name: '人事管理',
        url: 'person-manager'
    }]
}, {
    name: '权限管理',
    items: [{
        name: '用户管理',
        url: 'user-manager'
    }, {
        name: '部门管理',
        url: 'department-manager'
    }, {
        name: '角色管理',
        url: 'role-manager'
    }]
}])

db.blq.order.attrs.drop()
db.blq.order.attrs.insertMany([{
    name: 'order_client_attrs',
    name_cn: '委托方属性',
    attrs: [{
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
    }],
    logs: [{
        logTime: "1970-01-01 00:00:00",
        user: "blq_admin",
        contents: []
    }]
}, {
    name: 'order_server_attrs',
    name_cn: '检测方属性',
    attrs: [{
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
    }],
    logs: [{
        logTime: "1970-01-01 00:00:00",
        user: "blq_admin",
        contents: []
    }]
}, {
    name: 'order_content_attrs',
    name_cn: '委托内容属性',
    attrs: [{
        name: 'report_type',
        name_cn: '报告类型',
        type: 'select',
        options: ['检测报告', '试验报告']
    }, {
        name: 'report_unit',
        name_cn: '出具报告机构名称',
        type: 'select',
        options: ['中国电力科学研究院', '电力工业电气设备质量检验测试中心']
    }, {
        name: 'test_type',
        name_cn: '检测类别',
        type: 'checkbox',
        options: ['型式试验', '例行检验', '特殊试验', '性能试验(单项)', '性能试验(多项)']
    }, {
        name: 'stamp_type',
        name_cn: '印章类型',
        type: 'checkbox',
        options: ['CNAS L0699',
            'CMA 中国电力科学研究院',
            'CMA 电力工业电气设备质量检验测试中心',
            '检测报告专用章', '试验报告专用章', '型式评价专用章'
        ]
    }, {
        name: 'report_format',
        name_cn: '报告版式',
        type: 'select',
        options: ['中文版', '英文版']
    }, {
        name: 'report_num',
        name_cn: '份数',
        type: 'input',
    }, {
        name: 'test_money',
        name_cn: '检测费用',
        type: 'input',
    }],
    logs: [{
        logTime: "1970-01-01 00:00:00",
        user: "blq_admin",
        contents: []
    }]
}])

db.blq.sample.attrs.drop()
db.blq.sample.attrs.insertMany([{
    name: 'sample_base_info_attrs',
    name_cn: '试品基本属性',
    attrs: [{
        name: 'product_name',
        name_cn: '试品名称',
        type: 'input'
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
        options: ['送样', '抽样']
    }, {
        name: 'comp_do',
        name_cn: '试毕处置',
        type: 'select',
        options: ['自提', '自弃', '检测方代办托运']
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
        options: ['完好', '其他']
    }, {
        name: 'product_num',
        name_cn: '样品数量',
        type: 'input'
    }],
    logs: [{
        logTime: "1970-01-01 00:00:00",
        user: "blq_admin",
        contents: []
    }]
}, {
    name: 'sample_import_para_attrs',
    name_cn: '试品重要参数属性',
    attrs: [{
        name: 'sample_type',
        name_cn: '避雷器类型',
        type: 'select',
        options: ['复合无间隙避雷器']
    }, {
        name: 'rat_vol',
        name_cn: '额定电压',
        type: 'input',
    }],
    logs: [{
        logTime: "1970-01-01 00:00:00",
        user: "blq_admin",
        contents: []
    }]
}, {
    name: 'sample_main_para_attrs',
    name_cn: '试品主要技术参数属性',
    attrs: [{
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
        options: [1.5, 2.5, 5, 10, 20]
    }, {
        name: 'pol_level',
        name_cn: '污秽等级',
        type: 'select',
        options: ['Ⅰ', 'Ⅱ', 'Ⅲ', 'Ⅳ']
    }, {
        name: 'cell_num',
        name_cn: '元件个数',
        type: 'select',
        options: [1, 2, 3, 4, 5]
    }, {
        name: 'sea_height',
        name_cn: '海拔高度',
        type: 'select',
        options: ['0', '1000', '2000', '3000', '其他']
    }, {
        name: 'sur_type',
        name_cn: '外套类型',
        type: 'select',
        options: ['复合', '瓷', 'GIS', '插拔式', '外壳不带电']
    }, {
        name: 'seal_type',
        name_cn: '密封类型',
        type: 'select',
        options: ['无独立密封系统', '有独立密封系统']
    }, {
        name: 'use_place',
        name_cn: '应用场所',
        type: 'select',
        options: ['电站', '配电']
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
        options: [1.2, 3.4, 5.6]
    }, {
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
    }],
    logs: [{
        logTime: "1970-01-01 00:00:00",
        user: "blq_admin",
        contents: []
    }]
}])