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
  { url: 'tpl-config', name: '模板配置', component: 'PageTplConfig' },
  { url: 'desktop', name: '工作台', component: 'PageDeskTop' },
  { url: 'attr-config', name: '属性配置', component: 'PageAttrConfig' },
  { url: 'workflow-config', name: '工作流配置', component: 'PageWorkflowConfig' },
  { url: 'order-rec', name: '业务受理', component: 'PageOrderRec' },
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
},{
  name: 'default_value',
  caption: '默认值'
}]

