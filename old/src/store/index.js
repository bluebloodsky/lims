import Vue from 'vue'
import Vuex from 'vuex'

import baseInfo from './modules/baseInfo'
import attrInfo from './modules/attrInfo'
import auth from './modules/auth'
Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    baseInfo,
    attrInfo,
    auth
  }
})
