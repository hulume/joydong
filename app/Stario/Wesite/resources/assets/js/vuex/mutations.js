import { Indicator } from 'mint-ui'

export const loading = state => {
	Indicator.open('数据加载...')
}

export const loaded = state => {
	Indicator.close()
}

export const CHECK = (state) => {
	state.authenticated = !!window.localStorage.getItem('token')
	if (state.authenticated) {
		axios.defaults.headers.common.Authorization = `Bearer ${localStorage.getItem('token')}`
	}
}

export const LOGIN = (state, token) => {
	state.authenticated = true
	localStorage.setItem('token', token)
	axios.defaults.headers.common.Authorization = `Bearer ${token}`
}

export const LOGOUT = (state) => {
	state.authenticated = false
	localStorage.removeItem('token')
	axios.defaults.headers.common.Authorization = ''
}
export const SET_SITE = (state, data) => {
  return (state.site = data)
}
