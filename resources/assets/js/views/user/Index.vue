<template>
	<section>
		<!--工具条-->
		<el-col :span="24" class="toolbar" style="padding-bottom: 0px;">
			<el-form :inline="true" :model="filters">
				<el-form-item>
					<el-input v-model="filters.label" placeholder="输入检索内容"></el-input>
				</el-form-item>
				<el-form-item>
					<el-button type="info" v-on:click="getUserList"><i class="fa fa-search"></i> 查询</el-button>
				</el-form-item>
				<el-form-item>
					<el-button type="success" @click="handleAdd"><i class="fa fa-plus"></i> 新增</el-button>
				</el-form-item>
			</el-form>
		</el-col>
		<el-table :data="users" stripe @selection-change="selsChange" style="100%">
			<el-table-column type="selection" width="45" :selectable="checkSelectable"></el-table-column>
			<el-table-column prop="name" label="姓名" width="98" sortable></el-table-column>
			<el-table-column prop="email" label="Email"></el-table-column>
			<el-table-column prop="mobile" label="手机号码" width="130"></el-table-column>
			<el-table-column prop="unit" label="部门" width="100"></el-table-column>
			<el-table-column prop="status" width="95" label="状态" sortable>
				<template scope="scope">
					<template v-if="scope.row.status===1"><el-tag type="success">正常</el-tag></template>
					<template v-else>
						<el-tag type="danger">冻结</el-tag>
					</template>
				</template>
			</el-table-column>
			<el-table-column prop="last_login" label="上次登录" width="170"></el-table-column>
			<el-table-column label="操作" width="140">
				<template scope="scope">
					<el-button size="small" @click="handleView(scope.row)">查看</el-button>
					<el-button type="danger" size="small" @click="delBtnClick(scope.row)" :disabled="scope.row.id === myId">
						删除
					</el-button>
				</template>
			</el-table-column>
		</el-table>	
		<!--工具条-->
		<el-col :span="24" class="toolbar">
			<el-button type="danger" @click.native="handleDel('batch')" :disabled="this.sels.length===0">批量删除</el-button>
			<el-pagination layout="sizes, total, prev, pager, next" @size-change="handleSizeChange" @current-change="handleCurrentChange" :page-sizes="[15, 50, 100]" :page-size="page.per_page" :total="page.total" style="float:right;">
			</el-pagination>
		</el-col>
	</section>
</template>
<script>
	import { getUserList, deleteUser } from '../../api/api'
	export default {
		data () {
			return {
				users: [],
				filters: {
					label: ''
				},
				page: {
					total: 0,
					current_page: 1,
					per_page: 15
				},
				loading: false, //全局可以使用vuex，局部如增删改可以用在button上
				sels: [],
				viewDetailVisible: false, //详情界面是否显示
				viewDetaiData: {
					data: {}
				}
			}
		},
		// watch: {
		// 	'$route': 'getUserList'
		// },
		methods: {
			getUserList () {
				let params
				if (this.filters.label === '') {
					params = { per_page: this.page.per_page, page: this.page.current_page }
				} else {
					params = { per_page: this.page.per_page, page: this.page.current_page, filter: this.filters.label }
				}
				this.$store.commit('loading')
				getUserList(params)
				.then((response) => {
					this.users = response.data.data
					this.page.total = response.data.meta.pagination.total
					this.page.current_page = response.data.meta.pagination.current_page
					this.page.per_page = response.data.meta.pagination.per_page
					this.$store.commit('loaded')
				})
			},
			selsChange (val) {
				this.sels = _.map(val, 'id')
			},
			checkSelectable (row) {
				return row.id !== this.myId
			},
			handleView (row) {
				this.$router.push({ path:'/home/users/'+ row.id + '/edit' })
			},
			handleAdd () {
				this.$router.push({ path:'/home/users/create'})
			},
			delBtnClick (row) {
				this.handleDel(row.id)
			},
			// 删除处理，不传入ids则为this.sels(即批量)
			handleDel (ids) {
				ids = (ids === 'batch') ? this.sels : ids
				window.console.log(ids)
				if (ids === this.myId) {
					this.$alert('您不能在这里对自己的账号进行操作')
				}
				this.$confirm('确认删除选中记录吗？', '提示', {
					type: 'error'
				}).then(() => {
					let para = { ids: ids }
					this.$store.commit('loading')
					deleteUser(para)
					.then((res) => {
						this.$store.commit('loaded')
						this.$message({
							message: '删除成功',
							type: 'success'
						})
						this.getUserList()
					})
					.catch((error) => {
						this.$store.commit('loaded')
						this.$message({
							message: '操作失败，错误代码：' + error.response.status,
							type: 'error'
						})
					})
				})
			},
			handleSizeChange (val) {
				this.page.per_page = val
				this.getUserList()
			},
			handleCurrentChange (val) {
				this.page.current_page = val
				this.getUserList()
			}
		},
		computed: {
			myId () {
				return parseInt(window.Wemesh.id)
			}
		},
		mounted () {
			this.getUserList()
		}
	}
</script>