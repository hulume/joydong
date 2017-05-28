import Vue from 'vue'
import router from './router'
window.axios = require('axios')
import store from './vuex/store.js'
import 'mint-ui/lib/style.css'
import './style/app.css'
import 'font-awesome/css/font-awesome.css'
import App from './App'

window.axios.interceptors.response.use(
  response => response,
  (error) => {
    if (error.response.status === 401) {
      window.location = '/login'
    }
    if (error.response.status === 404) {
      window.location = '/'
    }
    return Promise.reject(error)
  })

Vue.config.productionTip = false
/* eslint-disable no-new */

new Vue(Vue.util.extend({router, store}, App)).$mount('#app')
