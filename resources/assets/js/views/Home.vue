<template>
	<div>
		<el-row :gutter="15">
			<el-col :span="6">
				<widget :title="statistics.users+' 人'" digest="管理人员" icon="user" color="info"></widget>
			</el-col>
			<el-col :span="6">
				<widget :title="statistics.units+' 个'" digest="部门总数" icon="users" color="success"></widget>
			</el-col>
			<el-col :span="6">
				<widget :title="statistics.pops+' 人'" digest="流动人口数量" icon="street-view" color="warning"></widget>
			</el-col>
			<el-col :span="6">
				<widget :title="statistics.aged+' 人'" digest="老年人口数量" icon="blind" color="danger"></widget>
			</el-col>
		</el-row>
	</div>
</template>
<script>
	import { getStastics } from '../api/api'
	import Widget from '../components/Widget'
	export default {
		data () {
			return {
				statistics: {
					users: '0',
					units: '0',
					pops: '0',
					aged: '0'
				}
			}
		},
		created () {
			this.fetchData()
		},
		methods: {
			fetchData () {
				this.$store.commit('loading')
				getStastics().then((response) => {
					this.statistics = response.data
					this.$store.commit('loaded')
				})
			}
		},
		components: {
			Widget
		}
	}
</script>
