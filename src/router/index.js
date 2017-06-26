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

Vue.use(Router)

const router = new Router({
  routes: [{
    path: '/',
    component: PageLogin
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
      path: '/his_data',
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
    component: PageRegister
  }]
})


router.beforeEach((to, from, next) => {
  console.log("before route")
  next()
})

export default router
