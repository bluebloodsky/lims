import Vue from 'vue'
import Vuex from 'vuex'
import frontInfo from './modules/frontInfo'
import auth from './modules/auth'
Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
  	frontInfo,
    auth
  }
})
