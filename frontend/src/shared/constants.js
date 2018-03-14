export const ATTR_TYPES = [{
  type: 'input',
  type_cn: '输入框'
}, {
  type: 'date',
  type_cn: '日期'
}, {
  type: 'select',
  type_cn: '下拉框'
}, {
  type: 'checkbox',
  type_cn: '多选框'
}, {
  type: 'radio',
  type_cn: '单选'
}, {
  type: 'pictureTable',
  type_cn: '图片列表'
}]

export const TABS_LIST = [
  { url: 'desktop', name: '工作台' },
  { url: 'test-item-config', name: '试验项配置', component: 'PageTestItemConfig' },
  { url: 'attr-config', name: '属性配置', component: 'PageAttrConfig' },
  { url: 'workflow-config', name: '工作流配置', component: 'PageWorkflowConfig' },
  { url: 'order-rec', name: '业务受理', component: 'PageOrderRec' },
  { url: 'tpl-config', name: '模板配置', component: 'PageTplConfig' },  
  { url: 'process-handle', name: '流程处理', component: 'PageProcessHandle' },
  { url: 'order-todo', name: '待办业务', component: 'PageOrderTodo' },
]

export const ATTR_FIELDS = [{
  name: 'name',
  caption: '属性名'
}, {
  name: 'name_cn',
  caption: '属性描述'
}, {
  name: 'attr_type',
  caption: '类型'
}, {
  name: 'options',
  caption: '选项'
}, {
  name: 'readonly',
  caption: '只读'
}, {
  name: 'default_value',
  caption: '默认值'
}]

export const WORKFLOW_STEPS = [{
  name: 'ORDER_RECEIVE',
  name_cn: '业务受理',
  key_link: true, //关键环节
  point: [20, 20]
}, {
  name: 'ORDER_REVIEW',
  name_cn: '协议审核',
  key_link: false, 
  point: [20, 80]
}, {
  name: 'RECORD_EDIT',
  name_cn: '原始记录录入',  
  key_link: true, 
  point: [20, 140]
}, {
  name: 'RECORD_REVIEW',
  name_cn: '原始记录审核',  
  key_link: false, 
  point: [20, 200]
}, {
  name: 'ARCHIVE',
  name_cn: '归档',  
  key_link: true, 
  point: [20, 260]
}]
