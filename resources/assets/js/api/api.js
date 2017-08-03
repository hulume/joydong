
export const getWeather = () => { return axios.get('weather') }
export const getMe = () => { return axios.get('home') }
export const editMe = params => { return axios.post('home', params) }
export const changePassword = params => { return axios.post('home/changePassword', params)}
export const changeMobile = params => { return axios.post('home/changeMobile', params)}
export const getStastics = () => { return axios.get('home/statistics') }
export const getWechatSummary = () => { 
	function getCumulate() {
		return axios.get('wesite/summary?type=cumulate')
	}
	function getSummary() {
		return axios.get('wesite/summary')
	}
	return axios.all([getCumulate(), getSummary()])
}
// 消息事件
export const getNotification = params => { return axios.get('home/notification', { params: params }) }
export const markNotification = params => { return axios.post('home/notification/mark', params ) }
export const deleteNotification = params => { return axios.post('home/notification/delete', params ) }
export const clearNotification = () => { return axios.get('home/notification/clear') }

// 内部管理
export const getUserList = params => { return axios.get('user', { params: params }) }
export const deleteUser = params => { return axios.post('user/delete', params) }
export const createUser = params => { return axios.post('user', params) }

export const getResidentList = params => { return axios.get('patient', { params: params }) }

// 微网站
export const getWeMenu = () => { return axios.get('wesite/menu')}
export const createWeMenu = params => { return axios.post('wesite/menu', params)}
// export const getMaterial = params => { return axios.get('wesite/material', { params: params })}
export const getMaterial = params => { return axios.get('wesite/material', { params: params })}

