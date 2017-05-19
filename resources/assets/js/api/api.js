
export const getWeather = () => { return axios.get('weather') }
export const getMe = () => { return axios.get('home') }
export const editMe = params => { return axios.post('home', params) }
export const changePassword = params => { return axios.post('home/changePassword', params)}
export const changeMobile = params => { return axios.post('home/changeMobile', params)}
export const getStastics = () => { return axios.get('home/statistics') }
export const getWechatSummary = () => { 
	function getCumulate() {
		return axios.get('wechat/summary?type=cumulate')
	}
	function getSummary() {
		return axios.get('wechat/summary')
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
// export const showUser = id => { return axios.get('user'), { params: { id: id }}}
export const deleteUser = params => { return axios.post('user/delete', params) }
export const createUser = params => { return axios.post('user', params) }
// export const updateUser = params => { return axios.put('user/update', params) }

export const removeUser = params => { return axios.get('user/remove', { params: params }) }

export const batchRemoveUser = params => { return axios.get('user/batchremove', { params: params }) }

export const editUser = params => { return axios.get('user/edit', { params: params }) }

export const addUser = params => { return axios.get('user/add', { params: params }) }