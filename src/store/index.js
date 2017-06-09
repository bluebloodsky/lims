import Vue from 'vue'
import Vuex from 'vuex'
import infoExtra from './modules/infoExtra'
import deviceInfo from './modules/deviceInfo'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: { infoExtra , deviceInfo}
})
