export const loading = ({ commit }) => commit('loading')
export const loaded = ({ commit }) => commit('loaded')

export const LOAD_DATA = ({ commit }) => window.axios.get('http://jiaodonghospital.dev/api/wesite').then((response) => {
  commit('SET_SITE', response.data.data)
  commit('loaded')
}).catch(() => {
  window.alert('读取数据出错')
})
