import { Message } from 'element-ui'

export const loading = ({ commit }) => commit('loading')
export const loaded = ({ commit }) => commit('loaded')

export const LOAD_USER_INFO = ({ commit }) => window.axios.get('home').then((response) => {
	commit('SET_USER_INFO', response.data.data)
	commit('loaded')
}).catch((error) => {
	Message.error(error)
	commit('loaded')
})
export const LOAD_WEATHER = ({ commit }) => window.axios.get('weather').then((response) => {
	commit('SET_WEATHER', response.data.result)
}).catch((error) => {
	Message.error('无法连接天气服务器:' + error.response.status)
})

export const EDIT_PROFILE = ({ commit }, data) => window.axios.post('home', data).then((response) => {
	commit('SET_USER_INFO', response.data.data)
	Message.success('个人资料更新成功')
	commit('loaded')
}).catch((error) => {
	Message.error('更新失败:' + error.response.status)
	commit('loaded')
})
