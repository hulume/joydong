require('./config/bootstrap')

import store from './vuex/store.js'
import VueRouter from 'vue-router'
import routes from './config/routes.js'
import App from './App.vue'
import ElementUI from 'element-ui'

Vue.use(VueRouter)
Vue.use(ElementUI)
const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes: routes
})

new Vue(Vue.util.extend({router, store}, App)).$mount('#app')
