export const loading = state => {
    return state.loading = true
}

export const loaded = state => {
    return state.loading = false
}

export const userInfo = ( state, data ) => {
	return state.userInfo = data
}

export const profile = ( state, data ) => {
	return state.profile = data 
}

export const weather = ( state, data ) => {
	return state.weather = data
}

// export const notifications = ( state, data ) => {
// 	return state.userInfo.notifications = data 
// } 