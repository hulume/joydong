import { MessageBox } from 'mint-ui'

export const loading = ({ commit }) => commit('loading')
export const loaded = ({ commit }) => commit('loaded')

export const check = ({ commit }) => commit('CHECK')
export const bind = ({ commit }, payload) => commit('BIND', payload)
export const logout = ({ commit }) => commit('LOGOUT')

export const fetchData = ({ commit }) => window.axios.get('api/wesite').then((response) => {
  commit('SET_SITE', response.data.data)
  commit('loaded')
}).catch(() => {
  MessageBox('提示', '读取数据出错')
})
