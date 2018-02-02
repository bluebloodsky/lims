import * as types from '../mutation-types'
const state = {
  tabs: [{ url: 'desktop', name: '工作台', component:'PageDeskTop'}],
  currentTab : 0,
  attrTypes:[{
    type:'input',
    type_cn:'输入框'
  },{
    type:'select',
    type_cn:'下拉框'
  },{
    type:'checkbox',
    type_cn:'多选框'
  },{
    type:'radio',
    type_cn:'单选'
  }]
}

const getters = {
  tabs: state => state.tabs,
  currentTab : state => state.currentTab,
  attrTypes:state => state.attrTypes,
}

const mutations = {
  addTab(state, tab_info) {
  	let _index = state.tabs.findIndex(tab => tab.url == tab_info.url)
    if (_index == -1) {
      state.tabs.push(tab_info)
      _index = state.tabs.length -1
    }
    state.currentTab = _index
  },
  removeTab(state, tab_index) {
    state.tabs.splice(tab_index, 1)
    state.currentTab = 0
  },
  setCurrentTab(state,num){
  	state.currentTab = num
  }
}

export default {
  state,
  getters,
  mutations
}
