import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Page from '../components/Page'
import Guide from '../components/Guide'
import User from '../components/User'
import Bind from '../components/Bind'
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
  path: '/user',
  name: 'User',
  component: User,
  meta: { auth: true }
},
{
  path: '/guide/:id',
  component: Guide
},
{
  path: '/bind',
  name: 'Bind',
  component: Bind,
  meta: { guest: true }
}
]
})
