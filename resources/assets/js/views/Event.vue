<template>
	<section>
		<!--列表-->
		<el-table :data="notifications" stripe @selection-change="selsChange" style="width: 100%;">
			<el-table-column type="selection" width="55">
			</el-table-column>
			<el-table-column prop="type" label="消息来源" width="120" sortable>
			</el-table-column>
			<!-- <el-table-column prop="data.title" label="消息标题"sortable>
			</el-table-column> -->

			<el-table-column label="消息标题">
				<template scope="scope">
					<template v-if="scope.row.read_at">{{scope.row.data.title}}</template>
					<template v-else>
						{{scope.row.data.title}}<el-badge value="未读" />
					</template>
				</template>
			</el-table-column>

			<el-table-column prop="created_at" label="发送时间" width="170"  sortable>
			</el-table-column>
			<el-table-column label="操作" width="150">
				<template scope="scope">
					<el-button size="small" @click="handleView(scope.$index, scope.row)">查看</el-button>
					<el-button type="danger" size="small" @click="delBtnClick(scope.row)">删除</el-button>
				</template>
			</el-table-column>
		</el-table>

		<!--工具条-->
		<el-col :span="24" class="toolbar">
			<el-button type="danger" @click.native="handleDel('batch')" :disabled="this.sels.length===0">批量删除</el-button>
			<el-button type="primary" @click.native="handleClear" :disabled="this.notifications.length===0">全部清空</el-button>
			<el-pagination layout="sizes, total, prev, pager, next" @size-change="handleSizeChange" @current-change="handleCurrentChange" :page-sizes="[15, 50, 100]" :page-size="page.per_page" :total="page.total" style="float:right;">
			</el-pagination>
		</el-col>

		<!--详情界面-->
		<el-dialog title="查看消息" v-model="viewDetailVisible" :close-on-click-modal="false">
			<div class="box-body no-padding">
				<div class="mailbox-read-info">
					<h3>{{viewDetailData.data.title}}</h3>
					<h5>来自:{{viewDetailData.type}}
						<span class="mailbox-read-time pull-right">{{viewDetailData.created_at}}</span></h5>
					</div>
					<div class="mailbox-read-message">
						<p>{{viewDetailData.data.content}}</p>
					</div>
				</div>
				<div slot="footer" class="dialog-footer">
					<el-button @click.native="viewDetailVisible = false">返回</el-button>
					<el-button type="danger" @click="delBtnClick(scope.$index, scope.row)" :loading="loading">删除</el-button>
				</div>
			</el-dialog>
		</section>
	</template>

	<script>
		import util from '../utils/utils'
		import { getNotification, markNotification, deleteNotification, clearNotification } from '../api/api'
		export default {
			data () {
				return {
				loading: false, //全局可以使用vuex，局部如增删改可以用在button上
				notifications: [],
				page: {
					total: 0,
					current_page: 1,
					per_page: 15
				},
				sels: [],//列表选中列
				viewDetailVisible: false,//详情界面是否显示
				viewDetailData: {
					data: {}
				}
			}
		},
		methods: {
			handleCurrentChange (val) {
				this.page.current_page = val
				this.getNotifications()
			},
			handleSizeChange (val) {
				this.page.per_page = val
				this.getNotifications()
			},
			delBtnClick (row) {
				this.handleDel(row.id)
			},
			// 删除处理，不传入ids则为this.sels(即批量)
			handleDel (ids) {
				ids = (ids === 'batch') ? this.sels : ids
				this.$confirm('确认删除选中记录吗？', '提示', {
					type: 'error'
				}).then(() => {
					let para = { ids: ids }
					this.$store.commit('loading')
					deleteNotification(para)
					.then((res) => {
						this.$store.commit('loaded')
						this.$message({
							message: '删除成功',
							type: 'success'
						})
						this.getNotifications()
					})
					.catch((error) => {
						this.$store.commit('loaded')
						this.$message({
							message: error.response.data,
							type: 'error'
						})
					})
				})
			},
			handleClear () {
				this.$confirm('确定清空所有的消息吗？', '提示', {
					type: 'error'
				}).then(() => {
					this.$store.commit('loading')
					clearNotification()
					.then((response) => {
						this.$store.commit('loaded')
						this.$message({
							message: response.data.data,
							type: 'success'
						})
						this.getNotifications()
					})
					.catch((error) => {
						this.$store.commit('loaded')
						this.$message({
							message: error.response.data.error,
							type: 'error'
						})
					})
				})
				
			},
			//显示界面
			handleView (index, row) {
				this.viewDetailVisible = true
				this.viewDetailData = Object.assign({}, row)
				markNotification({ids: row.id})
				.then((response) => {
					this.notifications[index].read_at = true
				})
				.catch((error) => {
					this.$message({
						message: '未能将消息设为已读\n' + error.response,
						type: 'error'
					})
				})
			},
			selsChange (sels) {
				this.sels = _.map(sels, 'id')
			},
			
			//获取列表
			getNotifications () {
				this.$store.commit('loading')
				getNotification({per_page: this.page.per_page, page: this.page.current_page}).then((response) => {
					this.notifications = response.data.data
					this.page.total = response.data.total
					this.page.current_page = response.current_page
					this.$store.commit('loaded')
				})
			}
		},
		mounted() {
			this.getNotifications()
		}
	}

</script>