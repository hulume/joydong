import {API_ROOT} from './config'
import '../../theme-default/index.css'
import { MessageBox } from 'element-ui'

window._ = require('lodash')
window.axios = require('axios')
window.Vue = require('vue')

window.axios.interceptors.request.use(function(config){
    config.headers['X-CSRF-TOKEN'] = Wemesh.csrfToken
    return config
})
window.axios.defaults.baseURL = API_ROOT
window.axios.defaults.params = { id: window.Wemesh.id}
window.axios.defaults.headers.post = {
	'Content-Type': 'application/x-www-form-urlencoded'
}
window.axios.interceptors.response.use(
  response => response,
  (error) => {
    if (error.response.status === 401) {
      window.location = '/login'
    }
    return Promise.reject(error)
  })
