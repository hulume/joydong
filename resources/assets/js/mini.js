import {API_ROOT} from './config/config'

window.$ = window.jQuery = require('jquery');
window._ = require('lodash');
window.axios = require('axios');
require('../bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js');
// require('./dropzone');
window.Vue = require('vue');
window.toastr = require('toastr/build/toastr.min.js')
window.swal = require('sweetalert')
window.toastr.options = {
    positionClass: "toast-top-right"
}

window.axios.interceptors.request.use(function(config){
    config.headers['X-CSRF-TOKEN'] = Wemesh.csrfToken
    return config;
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
    if (error.response.status === 404) {
    	window.location = '/'
    }
    return Promise.reject(error)
  });
Vue.prototype.$http = axios;

 import store from './vuex/store.js'
Vue.component('logo', require('./components/Logo.vue'))
Vue.component('register', require('./components/Register.vue'))
Vue.component('reset', require('./components/Reset.vue'))
// const app = new Vue({
//     el: '#app'
// })
new Vue(Vue.util.extend({store})).$mount('#app')
