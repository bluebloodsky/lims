import axios from 'axios'
import router from './router'
import store from './store'

console.log(store)
console.log(router)
  // axios 配置
axios.defaults.timeout = 10000
axios.defaults.baseURL = cfgInfo.baseURL

/*
// http request 拦截器
axios.interceptors.request.use(
  config => {
    if (store.state.token) {
      config.headers.Authorization = `token ${store.state.token}`;
    }
    return config;
  },
  err => {
    return Promise.reject(err);
  });

// http response 拦截器
axios.interceptors.response.use(
  response => {
    return response;
  },
  error => {
    if (error.response) {
      switch (error.response.status) {
      case 401:
        // 401 清除token信息并跳转到登录页面
        store.commit('logout');
        router.replace({
          path: 'login',
          query: { redirect: router.currentRoute.fullPath }
        })
      }
    }
    return Promise.reject(error)
  });
*/
export default axios;
