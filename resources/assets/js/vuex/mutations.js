export const loading = state => {
	return state.loading = true
}

export const loaded = state => {
	return state.loading = false
}

// export const userInfo = ( state, data ) => {
// 	let userInfo = data
// 	userInfo.profile.birthday = new Date(userInfo.profile.birthday)
// 	return state.userInfo = userInfo
// }
export const SET_USER_INFO = ( state, data ) => {
	let userInfo = data
	userInfo.profile.birthday = new Date(userInfo.profile.birthday)
	return state.userInfo = userInfo
}
export const UPDATE_PROFILE = ( state, data ) => {
	return state.userInfo = data
}

export const SET_WEATHER = ( state, data ) => {
	return state.weather = data
}

// export const notifications = ( state, data ) => {
// 	return state.userInfo.notifications = data 
// } 