import { getCookieValue } from './util'

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.axios.interceptors.request.use(config => {
  config.headers['X-XSRF-TOKEN'] = getCookieValue('XSRF-TOKEN')

  return config
})

// catch(error => error.response || error)を簡略化するため
window.axios.interceptors.response.use(
  response => response,
  error => error.response || error
)