import axios from '../http'
const _all = {
  'host_status': {
    'runTime': 1000,
    'freeTime': 200,
    'cpuNum': 4,
    'cpuFree': 1
  },
  'mem_status': {
    'totalMem': 16,
    'freeMem': 3,
    'freePercent': 12
  },
  'disk_status': [{
    'fileSystem': '/mnt/nand/db',
    'blocks': 12,
    'used': 22,
    'available': 20,
    'usePercent': 55,
    'mountedOn': '/tmp'
  }, {
    'fileSystem': '/mnt/nand/db',
    'blocks': 12,
    'used': 22,
    'available': 20,
    'usePercent': 55,
    'mountedOn': '/tmp'
  }, {
    'fileSystem': '/mnt/nand/db',
    'blocks': 12,
    'used': 22,
    'available': 20,
    'usePercent': 55,
    'mountedOn': '/tmp'
  }]
}
export default {
  getSysInfo(cb) {
    cb(_all)
    /*
    axios.get("/sys_status").then(response => {
      cb(response.data)
    })
    */
  },
  getProStatus(cb) {
    axios.get("/proc_status").then(response => {
      cb(response.data)
    })
  }
}
