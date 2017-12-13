import Vue from 'vue'
import VueTaber from 'vue-tabs'
import './plugins/vue-tabs.css'
import PageDeskTop from '@/pages/PageDeskTop'
import PageProRec from '@/pages/PageProRec'
import PageProTodo from '@/pages/PageProTodo'
import PageProHandle from '@/pages/PageProHandle'
import PageRecordEdit from '@/pages/PageRecordEdit'
import PageTplManager from '@/pages/PageTplManager'
import PageWorkflowManager from '@/pages/PageWorkflowManager'
import store from './store'
const tabs = [{
  name: 'desktop',
  title: '工作台',
  component: PageDeskTop
}, {
  name: 'pro_rec',
  title: '业务受理',
  component: PageProRec
}, {
  name: 'pro_todo',
  title: '待办业务',
  component: PageProTodo
}, {
  name: 'pro_handle',
  title: '业务办理',
  component: PageProHandle
}, {
  name: 'tpl_manager',
  title: '模板管理',
  component: PageTplManager
}, {
  name: 'workflow_manager',
  title: '工作流管理',
  component: PageWorkflowManager
}, {
  name: 'record_edit',
  title: '原始记录编制',
  component: PageRecordEdit
}]

const vueTaber = new VueTaber({
  tabs,
  persist: true
})
/*
vueTaber.beforeCreateEach((tab, next) => {
  if (tab.name === 'setting') {
    next('home')
  } else {
    next()
  }
})
*/
Vue.use(VueTaber)
export default vueTaber
