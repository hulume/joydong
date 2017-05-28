import { Indicator } from 'mint-ui'

export const loading = state => {
	Indicator.open('数据加载...')
}

export const loaded = state => {
	Indicator.close()
}
export const SET_SITE = (state, data) => {
  return (state.site = data)
}
