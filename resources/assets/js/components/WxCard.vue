<template>
	<!-- 信息卡片开始 -->
	<el-row :gutter="15">
		<el-col :span="12">
		<div class="box box-primary">
			<div class="box-body">
				<e-charts :options="cumulate" auto-resize></e-charts>
			</div>
		</div>
		</el-col>
		<el-col :span="12">
			<div class="box box-primary">
			<div class="box-body">
				<e-charts :options="summary" auto-resize></e-charts>
			</div>
			</div>
		</el-col>
	</el-row>
	<!-- 信息卡片END -->

</template>
<script>
	import ECharts from '../components/Chart/ECharts.vue'
	import 'echarts/lib/chart/line'
	import 'echarts/lib/component/title'
	import 'echarts/lib/component/tooltip'
	export default {
		data () {
			return {
				cumulate: {
					title: {
						text: '微信近期关注用户数量'
					},
					xAxis:  {
						type: 'category',
						boundaryGap: false,
						data: []
					},

					yAxis: {
						type: 'value',
						axisLabel: {
							formatter: '{value} 人'
						}
					},
					tooltip: {
						trigger: 'axis',
						axisPointer: {
							lineStyle: {
								color: '#ccc'
							}
						}
					},
					series: [
					{
						coodinateSystem: 'line',
						name:'关注人数',
						type:'line',
						// itemStyle: {
						// 	normal: {
						// 		color: '#00c0ef',
						// 		lineStyle: {
						// 			color: '#00c0ef',
						// 			width: 3
						// 		}
						// 	}
						// },
						smooth: true,
						// animation: false,
						data:[]
					}
					]
				},
				summary: {
					title: {
						text: '微信近期图文分享次数'
					},
					xAxis:  {
						type: 'category',
						boundaryGap: false,
						data: []
					},

					yAxis: {
						type: 'value',
						axisLabel: {
							formatter: '{value} 次'
						}
					},
					tooltip: {
						trigger: 'axis',
						axisPointer: {
							lineStyle: {
								color: '#ccc'
							}
						}
					},
					series: [
					{
						coodinateSystem: 'line',
						name:'分享次数',
						type:'line',
						itemStyle: {
							normal: {
								color: '#00c0ef',
								lineStyle: {
									color: '#00c0ef',
									width: 3
								}
							}
						},
						// smooth: true,
						animation: false,
						data:[]
					}
					]
				}
			}
		},
		mounted () {
			this.$store.commit('loading')
			function getCumulate() {
				return axios.get('wechat/summary?type=cumulate')
			}
			function getSummary() {
				return axios.get('wechat/summary')
			}
			axios.all([getCumulate(), getSummary()])
				.then(axios.spread((cumulate, summary) => {
					this.cumulate.series[0].data = _.map(cumulate.data.list, 'cumulate_user')
					this.cumulate.xAxis.data = _.map(cumulate.data.list, 'ref_date')
					this.summary.series[0].data = _.map(summary.data.list, 'share_count')
					this.summary.xAxis.data = _.map(summary.data.list, 'ref_date')
					this.$store.commit('loaded')
				}))
			// axios.get('wechat/summary')
			// .then((response) => {
			// 	this.$store.commit('loaded')
			// 	this.cumulate.series[0].data = _.map(response.data.list, 'cumulate_user')
			// 	this.cumulate.xAxis.data = _.map(response.data.list, 'ref_date')
			// })
		},
		components: {
			ECharts
		}
	}
</script>
<style>
	.echarts {
		height: 300px !important;
		width: 100% !important;
	}
</style>