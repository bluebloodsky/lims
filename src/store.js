import Vue from 'vue'
import Vuex from 'vuex'
//import Qs from 'qs'
//import axios from './http'
import apiBaseInfo from '@/api/baseInfo'
import apiAuth from '@/api/auth'
import apiDeviceInfo from '@/api/deviceInfo'
import apiSysInfo from '@/api/sysInfo'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    token: null,
    map_params: null,
    sysInfo: {},
    proStatus: [],
    devStatus: [],
    currentData: [],
    devices: []
  },
  getters: {
    user: state => state.user,
    token: state => state.token,
    map_params: state => state.map_params,
    sysInfo: state => state.sysInfo,
    proStatus: state => state.proStatus,
    devStatus: state => state.devStatus,
    currentData: state => state.currentData,
    devices: state => state.devices
  },
  mutations: {
    login(state, { userName, token }) {
      localStorage.token = token
      localStorage.user = userName
      state.token = token
      state.user = userName
    },
    logout(state) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      state.token = null
      state.user = null
    },
    getMapParams(state, { data }) {
      state.map_params = data
    },
    getSysInfo(state, data) {
      state.sysInfo = data
    },
    getProStatus(state, data) {
      state.proStatus = data
    },
    getDevStatus(state, data) {
      state.devStatus = data
    },
    getCurrentData(state, data) {
      state.currentData = data
    },
    getDevices(state, data) {
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
  },
  actions: {
    login({ commit }, { user, cb, cberr }) {
      apiAuth.login(user, token => {
          let userName = user.username
          commit('login', { userName, token })
          cb()
        },
        cberr)
    },
    getMapParams({ commit }, cb) {
      apiBaseInfo.getMapParams(data => {
        commit('getMapParams', { data })
        cb()
      })
    },
    getSysInfo({ commit }) {
      apiSysInfo.getSysInfo(data => {
        commit('getSysInfo', data)
      })
    },
    getProStatus({ commit }) {
      apiSysInfo.getProStatus(data => {
        commit('getProStatus', data)
      })
    },
    getDevStatus({ commit }) {
      apiDeviceInfo.getDevStatus(data => {
        commit('getDevStatus', data)
      })
    },
    getCurrentData({ commit }) {
      apiDeviceInfo.getCurrentData(data => {
        commit('getCurrentData', data)
      })
    },
    getDevices({ commit }) {
      apiDeviceInfo.getDevices(data => {
        commit('getDevices', data)
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
})
