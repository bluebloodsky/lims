import * as types from '../mutation-types'
import baseInfo from '../../api/baseInfo'
const state = {
  baseAttrs:[],
  orderClientAttrs: [],
  orderServerAttrs: [],
  orderContentAttrs: [],
  sampleBaseInfoAttrs: [],
  sampleMainParaAttrs: [],
  sampleCommonParaAttrs: [],
  testItemAttrs: []
}
const getters = {
  baseAttrs: state => state.baseAttrs,
  orderClientAttrs: state => state.orderClientAttrs,
  orderServerAttrs: state => state.orderServerAttrs,
  orderContentAttrs: state => state.orderContentAttrs,
  sampleBaseInfoAttrs: state => state.sampleBaseInfoAttrs,
  sampleMainParaAttrs: state => state.sampleMainParaAttrs,
  sampleCommonParaAttrs: state => state.sampleCommonParaAttrs,
  testItemAttrs: state => state.testItemAttrs
}
const actions = {
   getBaseAttrs({ commit }) {
    baseInfo.getBaseAttrs(data => {
      commit(types.GET_BASE_ATTRS, data)
    })
  },
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
  getProductCommonAttrs({ commit }) {
    baseInfo.getProductCommonAttrs(data => {
      commit(types.GET_PRODUCT_COMMON_ATTRS, data)
    })
  },
  getTestItemAttrs({ commit }) {
    baseInfo.getTestItemAttrs(data => {
      commit(types.GET_TESTITEM_ATTRS, data)
    })
  }
}
const mutations = {
  [types.GET_BASE_ATTRS](state, data) {
    state.baseAttrs = data
  },
  [types.GET_CLIENT_ATTRS](state, data) {
    state.orderClientAttrs = data
  },
  [types.GET_SERVICE_ATTRS](state, data) {
    state.orderServerAttrs = data
  },
  [types.GET_PROTOCOL_ATTRS](state, data) {
    state.orderContentAttrs = data
  },
  [types.GET_PRODUCT_BASE_ATTRS](state, data) {
    state.sampleBaseInfoAttrs = data
  },
  [types.GET_PRODUCT_IMPORT_ATTRS](state, data) {
    state.sampleMainParaAttrs = data
  },
  [types.GET_PRODUCT_COMMON_ATTRS](state, data) {
    state.sampleCommonParaAttrs = data
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
