export default {
  url: '/user',
  method: 'get', // default
  baseURL: cfgInfo.baseURL,
  transformRequest: [function (data) {
    return data
  }],
  transformResponse: [function (data) {
    return data
  }],
  headers: { 'X-Requested-With': 'XMLHttpRequest' },
  params: {},
  data: {},
  responseType: 'json'
}
