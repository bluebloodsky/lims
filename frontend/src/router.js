import Vue from 'vue'
import Router from 'vue-router'
import PageHome from './pages/PageHome'
import PageDeskTop from './pages/PageDeskTop'
import PageOrderRec from './pages/PageOrderRec'
import PageTestItemConfig from './pages/PageTestItemConfig'
import PageTplConfig from './pages/PageTplConfig'
import PageWorkflowConfig from './pages/PageWorkflowConfig'
import PageProcessHandle from './pages/PageProcessHandle'
import PageAttrConfig from './pages/PageAttrConfig'
import store from './store'

Vue.use(Router)
/*
if (window.localStorage.getItem('om_token')) {
  store.commit('login', {
    token: window.localStorage.getItem('om_token'),
    cur_user: JSON.parse(window.localStorage.getItem('om_user'))
  })
}
*/
const router = new Router({
  routes: [{
    path: '/',
    redirect: '/home'
  }, {
    name:'home',
    path: '/home',
    redirect: '/home/desktop',
    component: PageHome,
    children: [{
      name:'desktop',
      path: 'desktop',
      component: PageDeskTop
    }, {
      path: 'order-rec',
      component: PageOrderRec
    }, {
      path: 'test-item-config',
      component: PageTestItemConfig
    }, {
      path: 'tpl-config',
      component: PageTplConfig
    }, {
      path: 'workflow-config',
      component: PageWorkflowConfig
    }, {
      path: 'process-handle',
      component: PageProcessHandle
    }, {
      path: 'attr-config',
      component: PageAttrConfig
    }]
  }]
})

router.beforeEach((to, from, next) => {
  next()
})

export default router
