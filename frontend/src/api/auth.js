import axios from '../http'

import md5 from 'js-md5'
export default {
  login(user, cb, errorCb) {
    user.password = md5(user.username + user.password)
    axios.post('/users/validate', JSON.stringify(user)).then(response => {
      cb(response.data)
    }).catch(err => {
      errorCb(err)
    })

    /*
    axios.post('/home/Login/login', user, cfg).then(response => {
      if (response.data.success) {
        let token = '123456'
        cb(token)
      } else {
        errorCb()
      }
    }).catch(err => {
      errorCb(err)
    })
    */
  }
}
