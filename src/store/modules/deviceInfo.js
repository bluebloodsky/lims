import apiDeviceInfo from '../../api/deviceInfo'
import apiSysInfo from '../../api/sysInfo'
const state = {
  sysInfo: {},
  devStatus: [],
  currentData: [],
  devices: []
}

const getters = {
  sysInfo: state => state.sysInfo,
  devStatus: state => state.devStatus,
  currentData: state => state.currentData,
  devices: state => state.devices
}

const actions = {
  getSysInfo({ commit }) {
    apiSysInfo.getSysInfo(data => {
      commit('getSysInfo', { data })
    })
  },
  getDevStatus({ commit }) {
    apiDeviceInfo.getDevStatus(data => {
      commit('getDevStatus', { data })
    })
  },
  getCurrentData({ commit }) {
    apiDeviceInfo.getCurrentData(data => {
      commit('getCurrentData', { data })
    })
  },
  getDevices({ commit }) {
    apiDeviceInfo.getDevices(data => {
      commit('getDevices', { data })
    })
  },
  addDevice({ commit }, device) {
    apiDeviceInfo.addDevice(data => {
      device.sen_id = data
      commit('addDevice', device)
    }, device)
  },
  modifyDevice({ commit }, device) {
    apiDeviceInfo.modifyDevice(data => {
      commit('modifyDevice', device)
    }, device)
  },
  delDevice({ commit }, sen_id) {
    apiDeviceInfo.delDevice(() => {
      commit('delDevice', sen_id)
    }, sen_id)
  }
}

const mutations = {
  getSysInfo(state, { data }) {
    state.sysInfo = data
  },
  getDevStatus(state, { data }) {
    state.devStatus = data
  },
  getCurrentData(state, { data }) {
    state.currentData = data
  },
  getDevices(state, { data }) {
    state.devices = data
  },
  addDevice(state, data) {
    state.devices.push(data)
  },
  modifyDevice(state, data) {
    let index = state.devices.findIndex((val) =>
      val.sen_id == data.sen_id
    )
    state.devices.splice(index, 1, data)
  },
  delDevice(state, sen_id) {
    let index = state.devices.findIndex((val) =>
      val.sen_id == sen_id
    )
    state.devices.splice(index, 1)
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}
