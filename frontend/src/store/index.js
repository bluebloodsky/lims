import Vue from 'vue'
import Vuex from 'vuex'
import frontInfo from './modules/frontInfo'
import attrInfo from './modules/attrInfo'
import auth from './modules/auth'
Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
  	frontInfo,
    attrInfo,
    auth
  }
})
