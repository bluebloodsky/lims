import Vue from 'vue'
import Vuex from 'vuex'
import apiAuth from './api/auth'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    token: null
  },
  getters: {
    user: state => state.user,
    token: state => state.token
  },
  mutations: {
    login(state, { cur_user, token }) {
      localStorage.om_token = token
      localStorage.om_user = JSON.stringify(cur_user)
      state.token = token
      state.user = cur_user
    },
    logout(state) {
      localStorage.removeItem('om_token')
      localStorage.removeItem('om_user')
      state.token = null
      state.user = null
    }
  },
  actions: {
    login({ commit }, { user, cb, cb_err }) {
      apiAuth.login(user, data => {
        if (data.pass) // 验证通过
        {
          let cur_user = {
            username: user.username,
            level: data['level'],
            desc: data["desc"]
          }
          let token = '123'
          commit('login', { cur_user, token })
          cb()
        } else {
          cb_err(data.msg)
        }
      }, cb_err)
    }
  }
})
