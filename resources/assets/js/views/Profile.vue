<template>
	<el-row :gutter="15">
		<el-col :span="8">
			<user-card :data="userInfo"></user-card>
		</el-col>
		<el-col :span="16">
			<el-tabs v-model="active" type="card" @tab-click="onTabChange">
				<el-tab-pane label="修改资料" name="edit">
					
					<el-form ref="form" :model="userInfo" :rules="rules" label-width="80px" @submit.prevent="onSubmit" style="margin:20px;width:60%;min-width:600px;">
						<el-form-item label="姓名">
							<el-input v-model="userInfo.name" placeholder="真实姓名" :disabled="true"></el-input>
						</el-form-item>

						<el-form-item label="昵称">
							<el-input v-model="profile.nickname" placeholder="可以选填昵称"></el-input>
						</el-form-item>

						<el-form-item label="性别">
							<el-radio-group v-model="profile.sex">
								<el-radio label="男"></el-radio>
								<el-radio label="女"></el-radio>
							</el-radio-group>
						</el-form-item>

						<el-form-item label="出生日期">
								<el-date-picker type="date" placeholder="选择出生日期" v-model="profile.birthday" style="width: 100%;"></el-date-picker>
						</el-form-item>

						<el-form-item label="电子邮件">
							<el-input v-model="userInfo.email" placeholder="需要完善邮箱地址"></el-input>
						</el-form-item>

						<el-form-item label="QQ">
							<el-input v-model="profile.qq" placeholder="可以选填QQ号码"></el-input>
						</el-form-item>

						<el-form-item>
							<el-button type="primary" @click.native="onSubmit">提交修改</el-button>
						</el-form-item>
					</el-form>

				</el-tab-pane>
				<el-tab-pane label="安全设置" name="security">安全设置</el-tab-pane>
				<el-tab-pane label="近期事件" name="timeline">近期事件</el-tab-pane>
			</el-tabs>
		</el-col>
	</el-row>
</template>
<script>
	// import xhr from '../xhr'
	// import LabelGroup from '../components/LabelGroup'
	// import Timeline from '../components/Timeline'
	// import DatePicker from '../components/Datepicker'
	// import Avatar from '../components/AvatarEditor'
	import UserCard from '../components/UserCard'
	// import { isEmail, isDate, isMobile, required } from '../validator'
	// import Authcode from '../components/AuthCode'
	export default {
		components: {
			UserCard
		},
		data () {
			return {
				active: 'edit',
				rules: {
					name: [
						{
							required: true, message: '请输入您的真实姓名', trigger: 'blur'
						}
					],
					birthday: [
						{
							required: true, message: '请填写您的出生日期', trigger: 'blur'
						}
					],
					email: [
						{
							required: true, message: '请填写您的电子邮箱地址', trigger: 'blur'
						},
						{
							type: 'email', message: '您输入的电子邮箱地址不正确', trigger: 'blur'
						}
					]
				}
			}
		},
		computed: {
			userInfo: {
				get () {
					return this.$store.getters.getUserInfo
				},
				set (value) {
					this.$store.commit('userInfo', value)
				}
			},
			profile: {
				get () {
					return this.$store.getters.getProfile
				},
				set (value) {
					this.$store.commit('profile', value)
				}
			}
		},
		methods: {
			onTabChange (tab) {
				window.console.log(tab.name)
			},
			onSubmit () {
				// this.$store.commit('loading')
				let baseInfo = {
					email: this.userInfo.email
				}
				let profile = {
					nickname: this.profile.nickname,
					birthday: this.profile.birthday,
					qq: this.profile.qq,
					sex: this.profile.sex
				}
				window.console.log(profile)
			},
			onChangePass () {
				this.$store.commit('loading')
				axios.post('home/changePassword', { password: this.password })
				.then((response) => {
					toastr.success(response.data.data)
					window.location.href = '/logout'
				})
				.catch((error) => {
					this.$store.commit('loaded')
					let data = error.response.data
					swal('操作出错', data[Object.keys(data)[0]], 'error')
				})
			},
			onChangeMobile() {
				this.$store.commit('loading')
				axios.post('home/changeMobile', { mobile: this.mobile })
				.then((response) => {
					toastr.success(response.data.data)
					window.location.href = '/logout'
				})
				.catch((error) => {
					this.$store.commit('loaded')
					let data = error.response.data
					swal('操作出错', data[Object.keys(data)[0]], 'error')
				})
			}
		}
	}
</script>