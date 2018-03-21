import * as types from '../mutation-types'
import { TABS_LIST } from '@/shared/constants'
const state = {
  tabs: [TABS_LIST[0]]
}

const getters = {
  tabs: state => state.tabs
}

const mutations = {
  addTab(state, fullPath) {
    let path = fullPath.indexOf('?') == -1 ? fullPath : fullPath.substr(0, fullPath.indexOf('?'))
    let _index = state.tabs.findIndex(tab => tab.url == path)
    if (_index == -1) {
      let tab_info = TABS_LIST.find(tab => tab.url == path)
      state.tabs.push(tab_info)
    }
  },
  removeTab(state, tab_index) {
    state.tabs.splice(tab_index, 1)
  }
}

export default {
  state,
  getters,
  mutations
}
