
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
export const getNotification = params => { return axios.get('home/notification', { params: params }) }
export const markNotification = params => { return axios.post('home/notification/mark', params ) }
export const getUserList = params => { return axios.get('user/list', { params: params }) }

export const getUserListPage = params => { return axios.get('user/listpage', { params: params }) }

export const removeUser = params => { return axios.get('user/remove', { params: params }) }

export const batchRemoveUser = params => { return axios.get('user/batchremove', { params: params }) }

export const editUser = params => { return axios.get('user/edit', { params: params }) }

export const addUser = params => { return axios.get('user/add', { params: params }) }