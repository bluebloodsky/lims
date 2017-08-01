import Vue from 'vue'
import VueTaber from 'vue-tabs'
import './plugins/vue-tabs.css'
import PageProRec from '@/pages/PageProRec'
import PageProTodo from '@/pages/PageProTodo'
import store from './store'
const tabs = [{
  name: 'pro_rec',
  title: '业务受理',
  component: PageProRec
}, {
  name: 'pro_todo',
  title: '待办业务',
  component: PageProTodo
}, {
  name: 'pro_done',
  title: '已办业务',
  component: {
    template: '<h2>已办业务</h2>'
  }
}, {
  name: 'async',
  title: '异步组件',
  component: resolve => {
    setTimeout(() => {
      resolve({
        template: '<h2>我是异步组件, 我也是受害者</h2>'
      })
    }, 3000)
  }
}, {
  name: 'setting',
  title: '设置',
  component: {
    template: '<h2>设置</h2>'
  }
}]
const vueTaber = new VueTaber({
  tabs,
  persist: true
})
vueTaber.beforeCreateEach((tab, next) => {
  if (tab.name === 'setting') {
    next('home')
  } else {
    next()
  }
})
Vue.use(VueTaber)
export default vueTaber
