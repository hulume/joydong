import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions.js'
import * as mutations from './mutations.js'
Vue.use(Vuex)
const state = {
  loading: false,
  site: {
  	main: [],
  	guide: [],
  	theme: []
  }
}
const getters = {
	home: (state) => Object.assign({}, state.site.main[0])
}
export default new Vuex.Store({
  state,
  actions,
  getters,
  mutations
})
