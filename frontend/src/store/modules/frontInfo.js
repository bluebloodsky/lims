import * as types from '../mutation-types'
import {TABS_LIST} from '@/shared/constants'
const state = {
  tabs: [TABS_LIST[0]]
}

const getters = {
  tabs: state => state.tabs
}

const mutations = {
  addTab(state, url) {
  	let _index = state.tabs.findIndex(tab => tab.url == url)
    if (_index == -1) {
      let tab_info = TABS_LIST.find(tab => tab.url == url)
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
