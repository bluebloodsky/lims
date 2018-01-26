import * as types from '../mutation-types'
import auth from '../../api/auth'
const state = {
  user: null,
  token: null
}
const getters = {
  user: state => state.user,
  token: state => state.token
}
const actions = {
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
const mutations = {
  [types.LOGIN](state, { cur_user, token }) {
    localStorage.lims_token = token
    localStorage.lims_user = JSON.stringify(cur_user)
    state.token = token
    state.user = cur_user
  },
  [types.LOGOUT](state) {
    localStorage.removeItem('lims_token')
    localStorage.removeItem('lims_user')
    state.token = null
    state.user = null
  }
}
export default {
  state,
  getters,
  actions,
  mutations
}
