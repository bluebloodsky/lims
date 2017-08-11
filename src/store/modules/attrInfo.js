import * as types from '../mutation-types'
import baseInfo from '../../api/baseInfo'
const state = {
  clientAttrs: [],
  serviceAttrs: [],
  protocolAttrs: [],
  productBaseAttrs: [],
  productImportAttrs: [],
  testItemAttrs: []
}
const getters = {
  clientAttrs: state => state.clientAttrs,
  serviceAttrs: state => state.serviceAttrs,
  protocolAttrs: state => state.protocolAttrs,
  productBaseAttrs: state => state.productBaseAttrs,
  productImportAttrs: state => state.productImportAttrs,
  testItemAttrs: state => state.testItemAttrs
}
const actions = {
  getClientAttrs({ commit }) {
    baseInfo.getClientAttrs(data => {
      commit(types.GET_CLIENT_ATTRS, data)
    })
  },
  getServiceAttrs({ commit }) {
    baseInfo.getServiceAttrs(data => {
      commit(types.GET_SERVICE_ATTRS, data)
    })
  },
  getProtocolAttrs({ commit }) {
    baseInfo.getProtocolAttrs(data => {
      commit(types.GET_PROTOCOL_ATTRS, data)
    })
  },
  getProductBaseAttrs({ commit }) {
    baseInfo.getProductBaseAttrs(data => {
      commit(types.GET_PRODUCT_BASE_ATTRS, data)
    })
  },
  getProductImportAttrs({ commit }) {
    baseInfo.getProductImportAttrs(data => {
      commit(types.GET_PRODUCT_IMPORT_ATTRS, data)
    })
  },
  getTestItemAttrs({ commit }) {
    baseInfo.getTestItemAttrs(data => {
      commit(types.GET_TESTITEM_ATTRS, data)
    })
  }
}
const mutations = {
  [types.GET_CLIENT_ATTRS](state, data) {
    state.clientAttrs = data
  },
  [types.GET_SERVICE_ATTRS](state, data) {
    state.serviceAttrs = data
  },
  [types.GET_PROTOCOL_ATTRS](state, data) {
    state.protocolAttrs = data
  },
  [types.GET_PRODUCT_BASE_ATTRS](state, data) {
    state.productBaseAttrs = data
  },
  [types.GET_PRODUCT_IMPORT_ATTRS](state, data) {
    state.productImportAttrs = data
  },
  [types.GET_TESTITEM_ATTRS](state, data) {
    state.testItemAttrs = data
  }
}
export default {
  state,
  getters,
  actions,
  mutations
}
