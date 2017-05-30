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
      window.location = '/bind'
    }
    return Promise.reject(error)
  })
// 检查是否已经登录
store.dispatch('check')
// 如果localStorage没有token，向后台申请
// 后台如果发现之前openid已经绑定，返回token
// 如果未绑定，跳转至绑定页
if (!store.state.authenticated) {
  window.axios.get('api/wesite/check')
  .then((response) => {
    store.dispatch('bind', response.data.data)
    store.dispatch('check')
  })
  .catch((error) => {
    router.push({name: 'Bind'})
  })
}


router.beforeEach((to, from, next) => {
  if (to.matched.some(m => m.meta.auth) && !store.state.authenticated) {
    next({
      name: 'Bind'
    })
  } else if (to.matched.some(m => m.meta.guest) && store.state.authenticated) {
    next({
      name: 'User'
    })
  } else {
    next()
  }
})

Vue.config.productionTip = false
/* eslint-disable no-new */

new Vue(Vue.util.extend({router, store}, App)).$mount('#app')
