import Vue from 'vue'
import router from './router'
window.axios = require('axios')
import store from './vuex/store.js'
import 'mint-ui/lib/style.css'
import './style/app.css'
import 'font-awesome/css/font-awesome.css'
import App from './App'
import { getCookie } from './utils'

window.axios.interceptors.response.use(
  response => response,
  (error) => {
    if (error.response.status === 401) {
      window.location = '/login'
    }
    return Promise.reject(error)
  })

router.beforeEach((to, from, next) => {
  if (to.matched.some(m => m.meta.auth) && !store.state.authenticated) {
    next({
      name: 'Login'
    })
  } else {
    next()
  }
})

Vue.config.productionTip = false
/* eslint-disable no-new */

new Vue(Vue.util.extend({router, store}, App)).$mount('#app')
