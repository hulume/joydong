import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions.js'
import * as mutations from './mutations.js'
Vue.use(Vuex)
const state = {
    loading: false,
    userInfo: {
      profile: {},
      rolemission: {
        permissions: {},
        roles: {}
      }
    },
    weather: {}
}

const getters = {
    userInfo: state => state.userInfo,
    permissions: state => state.userInfo.rolemission.permissions,
    weather: state => state.weather
}

export default new Vuex.Store({
    state,
    actions,
    mutations,
    getters
})
