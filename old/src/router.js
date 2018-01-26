import Vue from 'vue'
import Router from 'vue-router'
import PageHome from '@/pages/PageHome'
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
    path: '/home',
    component: PageHome
  }]
})
/*
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
*/

export default router
