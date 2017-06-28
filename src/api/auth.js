export default {
  login(user, cb, errorCb) {
    let token = '123456'
    cb(token)
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
