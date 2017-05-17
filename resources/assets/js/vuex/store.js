import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions.js';
import * as mutations from './mutations.js';

Vue.use(Vuex);

const state = {
    loading: false,
    userInfo: {
      profile: {},
      // unreadNotifications: {},
      // notifications: {},
      rolemission: {
        permissions: {},
        roles: {}
      }
    },
    weather: {}
};

const getters = {
    getUserInfo: state => state.userInfo,
    // getProfile: state => state.userInfo.profile,
    // getNotification: () => state.userInfo.notifications,
    getWeather: state => state.weather
    // getPermissions: state => state.userInfo.rolemission.permissions
}

export default new Vuex.Store({
    state,
    actions,
    mutations,
    getters
});
