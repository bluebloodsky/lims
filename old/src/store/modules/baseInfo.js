import * as types from '../mutation-types'
import baseInfo from '../../api/baseInfo'

const state = {
  baseAttrs: []
}
const getters = {
  baseAttrs: state => state.baseAttrs
}
const actions = {
  getBaseAttrs({ commit }) {
    baseInfo.getBaseAttrs(data => {
      commit(types.GET_BASE_ATTRS, data)
    })
  }
}
const mutations = {
  [types.GET_BASE_ATTRS](state, data) {
    state.baseAttrs = data
  }
}
export default {
  state,
  getters,
  actions,
  mutations
}
