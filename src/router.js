import Vue from 'vue'
import Router from 'vue-router'
import PageLogin from '@/pages/PageLogin'
import PageHome from '@/pages/PageHome'
import PageRegister from '@/pages/PageRegister'
import PageStatus from '@/pages/PageStatus'
import PageCurrentData from '@/pages/PageCurrentData'
import PageMonitorDevice from '@/pages/PageMonitorDevice'
import PageFileManager from '@/pages/PageFileManager'
import PageProIed from '@/pages/PageProIed'
import PageHisData from '@/pages/PageHisData'
import PageAlarm from '@/pages/PageAlarm'
import PageI2Device from '@/pages/PageI2Device'
import PageConfigParam from '@/pages/PageConfigParam'
import PageMmsConfig from '@/pages/PageMmsConfig'
import PageI2Upload from '@/pages/PageI2Upload'
import PageUserInfo from '@/pages/PageUserInfo'
import store from './store'
console.log('router:')
console.log(store)
Vue.use(Router)
if (window.localStorage.getItem('token')) {
  store.commit('login', {
    token: window.localStorage.getItem('token'),
    user: window.localStorage.getItem('user')
  })
}

const router = new Router({
  routes: [{
    path: '/',
    redirect: '/status'
  }, {
    path: '/login',
    component: PageLogin,
    meta: {
      noRequireAuth: true
    }
  }, {
    path: '/home',
    component: PageHome,
    children: [{
      path: '/status',
      component: PageStatus
    }, {
      path: '/real_data',
      component: PageCurrentData
    }, {
      path: '/monitor_device',
      component: PageMonitorDevice
    }, {
      path: '/i2_device',
      component: PageI2Device
    }, {
      path: '/file_manager',
      component: PageFileManager
    }, {
      path: '/pro_ied',
      component: PageProIed
    }, {
      path: '/alarm',
      component: PageAlarm
    }, {
      path: '/config_param',
      component: PageConfigParam
    }, {
      path: '/his_data/:sen_id',
      component: PageHisData
    }, {
      path: '/mms_config',
      component: PageMmsConfig
    }, {
      path: '/i2_upload',
      component: PageI2Upload
    }, {
      path: '/user',
      component: PageUserInfo
    }]
  }, {
    path: '/register',
    component: PageRegister,
    meta: {
      noRequireAuth: true
    }
  }]
})

router.beforeEach((to, from, next) => {
  if (!to.matched.some(r => r.meta.noRequireAuth)) {
    if (store.state.token) {
      next();
    } else {
      next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
    }
  } else {
    next();
  }
})

export default router
