<template>
	<el-row>
		<el-form ref="form" :model="form" :rules="rules" :inline="true" label-width="100px">
			<!-- <el-col :span="24"> -->
				<fieldset>
					<legend>管理人员资料</legend>
					<el-form-item label="姓名:" prop="name">
						<el-input v-model="form.name"></el-input>
					</el-form-item>

					<el-form-item label="手机:" prop="mobile">
						<el-input v-model="form.mobile"></el-input>
					</el-form-item>

					<el-form-item label="邮箱:" prop="email">
						<el-input v-model="form.email" style="width: 218px"></el-input>
					</el-form-item>

					<el-form-item label="出生日期:">
						<el-date-picker type="date" placeholder="选择日期" v-model="form.profile.birthday"></el-date-picker>
					</el-form-item>

					<el-form-item label="昵称:">
						<el-input v-model="form.profile.nickname"></el-input>
					</el-form-item>

					<el-form-item label="QQ:">
						<el-input v-model="form.qq"></el-input>
					</el-form-item>

					<el-form-item label="性别:" prop="sex">
						<el-radio-group v-model="form.profile.sex">
							<el-radio label="男"></el-radio>
							<el-radio label="女"></el-radio>
						</el-radio-group>
					</el-form-item>

					<el-form-item label="用户状态:" prop="status">
						<el-radio-group v-model="form.status">
							<el-radio label="1">正常</el-radio>
							<el-radio label="0">冻结</el-radio>
						</el-radio-group>
					</el-form-item>
					
					<el-row>
						<el-col :span="24">
							<el-form-item label="修改密码">
								<el-switch v-model="showPassword"></el-switch>
							</el-form-item>
							<el-form-item v-if="showPassword">
								<el-input v-model="form.password"></el-input>
							</el-form-item>
						</el-col>
					</el-row>

				</fieldset>
			<!-- </el-col> -->
			
			<!-- <el-col :span="24"> -->
				<fieldset>
					<legend>角色权限</legend>
					<el-form-item label="所在部门:" prop="selectedUnit">
						<el-select v-model="form.selectedUnit" placeholder="请选择所在部门">
							<el-option  v-for="unit in form.units" :label="unit.name" :value="unit.id" :key="unit.id"></el-option>
						</el-select>
					</el-form-item>

					<el-form-item label="用户权限:">
						<el-checkbox-group v-model="form.checkedPermission">
							<el-checkbox v-for="permission in form.permissions" :label="permission.name" :key="permission.id" :disabled="permission.name==='general'">
								{{permission.label}}
							</el-checkbox>
						</el-checkbox-group>
					</el-form-item>
				</fieldset>	
			<!-- </el-col> -->

			<el-form-item>
				<el-button type="primary" @click="onSubmit">立即创建</el-button>
				<el-button>取消</el-button>
			</el-form-item>
		</el-form>
	</el-row>
</template>
<script>
	import { updateUser } from '../../api/api'
	import { formatDate } from '../../utils/utils'
	export default {
		data() {
			return {
				showPassword: false,
				form: {
					name: '',
					mobile: '',
					email: '',
					status: '',
					password: '',
					profile: {
						birthday: '',
						nickname: '',
						sex: '女'
					},
					selectedUnit: '',
					units: {},
					permissions: {},
					checkedPermission: []
				},
				rules: {
					name: [{ required: true, message: '请输入姓名', trigger: 'blur'}],
					mobile: [{ required: true, message: '请输入手机号码', trigger: 'blur'}],
					email: [{ required: true, message: '请输入正确的邮箱地址', trigger: 'blur', type:'email'}],
					password: [{ required: true, message: '请输入初始密码', trigger: 'blur'}]
				}
			}
		},
		methods: {
			loadData () {
				this.$store.commit('loading')
				let getUser = () => {
					return axios.get('user/' + this.$route.params.id + '/edit')
				}
				let getUnits = () => {
					return axios.get('unit')
				}
				let getPermissions = () => {
					return axios.get('permission')
				}

				axios.all([getUser(), getUnits(), getPermissions()])
				.then(axios.spread((user, units, permissions) => {
					this.form = this.formatData(user.data.data)
					this.form.checkedPermission = user.data.data.rolemission.permissions
					this.form.selectedUnit = user.data.data.unit
					this.form.units = units.data.data
					this.form.permissions = permissions.data
					this.$store.commit('loaded')
				}))
				.catch((error) => {
					let data = error.response.data
					this.$message.error('读取失败，错误代码:' + error.response.status)
					// toastr.error(data[Object.keys(data)[0]])
				})
			},
			onSubmit () {
				this.$refs.form.validate((valid) => {
					if (valid) {
						let profile = {
							nickname: this.form.profile.nickname,
							birthday: formatDate(this.form.profile.birthday),
							qq: this.form.profile.qq,
							sex: this.form.profile.sex
						}
						let baseInfo = {
							name: this.form.name,
							mobile: this.form.mobile,
							email: this.form.email,
							status: parseInt(this.form.status)
						}
						if (this.showPassword) {
							baseInfo.password = this.form.password
						}
						let unit = this.form.selectedUnit
						let permissions = this.form.checkedPermission

						this.$store.commit('loading')

						axios.put('user/' + this.$route.params.id, { profile: profile, baseInfo:baseInfo, unit: unit, permissions: permissions })
						.then((response) => {

							this.$store.commit('loaded')
							this.$message.success('修改成功')
							this.$router.push({ path: '/home/users' })
							
						})
						.catch((error) => {

							this.$store.commit('loaded')

							this.$alert(error.response.data, '操作错误')
						})
					} else {
						this.$message.error('请完善必填项')
						return false
					}
				})
			},
			formatData (user) {
				return {
					name: user.name,
					mobile: user.mobile,
					email: user.email,
					status: user.status.toString(),
					profile: {
						birthday: new Date (user.profile.birthday),
						nickname: user.profile.nickname,
						qq: user.profile.qq,
						sex: user.profile.sex
					}
				}
			},
		},
		mounted () {
			this.loadData()
		}
	}
</script>