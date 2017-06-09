import apiBaseInfo from '../../api/baseInfo'
const state = {
  map_params: null
}

const getters = {
  map_params: state => state.map_params
}

const actions = {
  getMapParams({ commit }, cb) {
    apiBaseInfo.getMapParams(data => {
      commit('getMapParams', { data })
      cb()
    })
  }
}

const mutations = {
  getMapParams(state, { data }) {
    state.map_params = data
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
