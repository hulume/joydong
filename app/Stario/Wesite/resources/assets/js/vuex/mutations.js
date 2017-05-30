import { Indicator } from 'mint-ui'
import Vue from 'vue'

export const loading = state => {
	Indicator.open('数据加载...')
}

export const loaded = state => {
	Indicator.close()
}

export const CHECK = (state) => {
	state.authenticated = !!window.localStorage.getItem('token')
	if (state.authenticated) {
		window.axios.defaults.headers.common.Authorization = `Bearer ${window.localStorage.getItem('token')}`
	}
}

export const BIND = (state, token) => {
	state.authenticated = true
	window.localStorage.setItem('token', token)
	window.axios.defaults.headers.common.Authorization = `Bearer ${token}`
}

export const LOGOUT = (state) => {
	state.authenticated = false
	window.localStorage.removeItem('token')
	window.axios.defaults.headers.common.Authorization = ''
}
export const SET_SITE = (state, data) => {
  return (state.site = data)
}
