<template>
	<el-row :gutter="15">
		<el-col :span="8">
			<user-card :data="userInfo"></user-card>
		</el-col>
		<el-col :span="16">
			<el-tabs v-model="active" type="card">
				<el-tab-pane label="修改资料" name="edit">
					<avatar :imgPath="userInfo.profile.avatar" :apiUrl="'home/avatar'"></avatar>	
					<el-form :model="userInfo" :rules="profileRules" ref="userInfo" label-width="80px" @submit.prevent="onSubmit" style="margin:20px;">
						<el-form-item label="姓名" prop="name">
							<el-input v-model="userInfo.name" placeholder="真实姓名" :disabled="true"></el-input>
						</el-form-item>

						<el-form-item label="昵称">
							<el-input v-model="userInfo.profile.nickname" placeholder="可以选填昵称"></el-input>
						</el-form-item>

						<el-form-item label="性别" prop="sex">
							<el-radio-group v-model="userInfo.profile.sex">
								<el-radio label="男"></el-radio>
								<el-radio label="女"></el-radio>
							</el-radio-group>
						</el-form-item>

						<el-form-item label="出生日期" prop="profile.birthday">
							<el-date-picker type="date" placeholder="选择出生日期" v-model="userInfo.profile.birthday" style="width: 100%;"></el-date-picker>
						</el-form-item>

						<el-form-item label="电子邮件" prop="email">
							<el-input v-model="userInfo.email" placeholder="需要完善邮箱地址"></el-input>
						</el-form-item>

						<el-form-item label="QQ">
							<el-input v-model="userInfo.profile.qq" placeholder="可以选填QQ号码"></el-input>
						</el-form-item>

						<el-form-item>
							<el-button type="primary" @click.native="onSubmit(userInfo)">提交修改</el-button>
						</el-form-item>
					</el-form>

				</el-tab-pane>
				<el-tab-pane label="安全设置" name="security">
					<el-alert title="修改密码或手机号码后会退出当前系统并需重新登录" type="error" show-icon></el-alert>
					<el-row :gutter="15">
						<el-col :span="12">
							<div class="panel" style="margin-top: 15px;">
								<div class="panel-heading  bg-info"><i class="fa fa-key"></i> 修改密码</div>
								<div class="panel-body">
									<el-form :model="password" :rules="passwordRules" ref="password" @submit.prevent="onChangePass">
										<el-form-item label="当前密码" prop="oldpass">
											<el-input placeholder="输入当前密码" type="password" v-model="password.oldpass"></el-input>
										</el-form-item>
										<el-form-item label="新的密码" prop="newpass">
											<el-input placeholder="输入新的密码" type="password" v-model="password.newpass"></el-input>
										</el-form-item>
										<el-form-item label="确认密码" prop="newpass2">
											<el-input placeholder="再次输入新的密码" type="password" v-model="password.newpass2"></el-input>
										</el-form-item>
										<el-form-item>
											<el-button type="info" @click.native="onChangePass(password)">提交修改</el-button>
										</el-form-item>
									</el-form>
								</div>
							</div>
						</el-col>
						<el-col :span="12">
							<div class="panel" style="margin-top: 15px;">
								<div class="panel-heading  bg-primary"><i class="fa fa-mobile"></i> 更换手机</div>
								<div class="panel-body">
									<el-form :model="mobile" :rules="mobileRules" ref="mobile">
										<el-form-item label="当前手机号码">
											<el-input placeholder="输入当前手机号码" disabled v-model="userInfo.mobile"></el-input>
										</el-form-item>
										<el-form-item label="新的手机号码" prop="newmobile">
											<el-input placeholder="输入新的手机号码" v-model="mobile.newmobile"></el-input>
										</el-form-item>
										<el-row :gutter="15">
											<el-col :span="13">
												<el-form-item label="短信验证码" prop="authcode">
													<el-input placeholder="输入短信验证码" v-model="mobile.authcode"></el-input>
												</el-form-item>
											</el-col>
											<el-col :span="6">
												<authcode :data="{mobile:this.mobile.newmobile}" style="margin-top: 36px;"></authcode>
											</el-col>
										</el-row>
										<el-form-item>
											<el-button type="primary" @click.native="onChangeMobile(mobile)">提交修改</el-button>
										</el-form-item>
									</el-form>
								</div>
							</div>
						</el-col>
					</el-row>
				</el-tab-pane>
				<el-tab-pane label="近期事件" name="timeline">
					<timeline></timeline>
				</el-tab-pane>
			</el-tabs>
		</el-col>
	</el-row>
</template>
<script>
	import { changePassword, changeMobile } from '../api/api'
	import Timeline from '../components/Timeline'
	import Avatar from '../components/AvatarEditor'
	import UserCard from '../components/UserCard'
	import Authcode from '../components/AuthCode'
	export default {
		components: {
			UserCard, Authcode, Avatar, Timeline
		},
		data () {
			var validatePass = (rule, value, callback) => {
				if (value === '') {
					callback(new Error('请输入密码'))
				} else {
					if (this.passwordRules.newpass2 !== '') {
						this.$refs.password.validateField('newpass2')
					}
					callback()
				}
			}
			var validatePass2 = (rule, value, callback) => {
				if (value === '') {
					callback(new Error('请再次输入密码'));
				} else if (value !== this.password.newpass) {
					callback(new Error('两次输入密码不一致!'));
				} else {
					callback();
				}
			}
			return {
				active: 'edit',
				password: {
					oldpass: '',
					newpass: '',
					newpass2: ''
				},
				mobile: {
					authcode: '',
					newmobile: '',
					oldmobile: ''
				},
				profileRules: {
					name: [{required: true, message: '请输入您的真实姓名', trigger: 'blur'}],
					'profile.birthday': [{type: 'date', required: true, message: '请输入正确的出生日期', trigger: 'blur'}],
					email: [{type: 'email', required: true, message: '请填写正确的电子邮箱地址', trigger: 'blur'}]
				},
				mobileRules: {
					newmobile: [{required: true, message: '输入正确的手机号码', trigger: 'blur'}],
					authcode: [{required: true, message: '输入正确的验证码', trigger: 'blur'}],
				},
				passwordRules: {
					oldpass: [{required: true, message: '输入当前密码', trigger: 'blur'}],
					newpass: [{ validator: validatePass, trigger: 'blur', required: true }],
					newpass2: [{ validator: validatePass2, trigger: 'blur', required: true }]
				}
			}
		},
		computed: {
			userInfo: {
				get () {
					return this.$store.getters.userInfo
				},
				set (value) {
					this.$store.commit('userInfo', value)
				}
			}
		},
		methods: {
			onSubmit (form) {
				this.$store.commit('loading')
				let baseInfo = {
					email: this.userInfo.email
				}
				let profile = {
					nickname: this.userInfo.profile.nickname,
					birthday: this.userInfo.profile.birthday,
					qq: this.userInfo.profile.qq,
					sex: this.userInfo.profile.sex
				}
				this.$refs['userInfo'].validate((valid) => {
					if (valid) {
						this.$store.dispatch('EDIT_PROFILE', {profile: profile, baseInfo: baseInfo})
					} else {
						this.$message({
							message: '您有选项未填或格式不对',
							type: 'error'
						})
						this.$store.commit('loaded')
					}
				})
			},
			onChangePass () {
				this.$store.commit('loading')
				changePassword({ password: {oldpass: this.password.oldpass, newpass: this.password.newpass, newpass_confirmation: this.password.newpass2} })
				.then((response) => {
					this.$message({
						message: response.data.data,
						type: 'success'
					})
					window.location.href = '/logout'
				})
				.catch((error) => {
					this.$store.commit('loaded')
					let data = error.response.data
					this.$alert(data, '出错了！')
				})
			},
			onChangeMobile() {
				this.$store.commit('loading')
				changeMobile({ mobile: this.mobile }).then((response) => {
					this.$message({
						message: response.data.data,
						type: 'success'
					})
					window.location.href = '/logout'
				})
				.catch((error) => {
					this.$store.commit('loaded')
					this.$alert( error.response.data, '出错了！')
				})
			}
		}
	}
</script>