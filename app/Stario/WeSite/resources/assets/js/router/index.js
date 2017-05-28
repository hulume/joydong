import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Page from '../components/Page'
import Guide from '../components/Guide'
import Login from '../components/Login'
Vue.use(Router)

export default new Router({
  routes: [
  {
      path: '/',
      name: 'Home',
      component: Home
  },
  {
   path: '/page/:id',
   component: Page
},
{
    path: '/guide/:id',
    component: Guide
},
{
  path: '/login',
  name: 'Login',
  component: Login
}
]
})
