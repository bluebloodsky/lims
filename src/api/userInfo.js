import axios from '../http'
export default {
  getAllUsers(cb) {
    axios.get("/users").then(response => {
      cb(response.data)
    })
  },
  validateUser(cb,data) {
    axios.post("/users/validate",JSON.stringify(data)).then(response => {
      cb(response.data)
    })
  },
  addOrModifyUser(cb,data) {
    axios.post("/users/modify",JSON.stringify(data)).then(response => {
      cb(response.data)
    })
  },
  removeUser(cb,data) {
    axios.post("/users/delete",JSON.stringify(data)).then(response => {
      cb(response.data)
    })
  }
}
