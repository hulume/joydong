<template>
	<!-- <el-row> -->
	<el-form ref="form" :model="form" :rules="rules" :inline="true" label-width="100px">
		<template  v-show="showResult">
		<fieldset>
			<legend><i class="fa fa-address-book-o"></i> 基本资料</legend>

			<el-form-item label="姓名:" prop="name">
				<el-input v-model="form.name"></el-input>
			</el-form-item>

			<el-form-item label="手机:" prop="mobile">
				<el-input v-model="form.mobile"></el-input>
			</el-form-item>

			<el-form-item label="身份证号码:" prop="identify">
				<el-input v-model="form.identify" style="width: 218px"></el-input>
			</el-form-item>

			<el-form-item label="年龄:">
				<el-input v-model="form.age" disabled></el-input>
			</el-form-item>

			<el-form-item label="所在行政村:" prop="address">
				<el-select v-model="form.address" filterable placeholder="输入拼音简写如jd" :filter-method="filterCountry">
				  <el-option
				    v-for="item in filteredOptions"
				    :key="item.name"
				    :label="item.name"
				    :value="item.name">
				  </el-option>
				</el-select>
			</el-form-item>
		</fieldset>

		<fieldset>
			<legend>关联体检数据</legend>
			<el-form-item label="化验单名称:" prop="selectedUnit">
			    <el-input v-model="form.archive" placeholder="如1705021" style="margin-right: 10px; width: 270px; vertical-align: top;"></el-input>
			    <el-button @click.prevent="onFindArchive" type="success"><i class="fa fa-search"></i> 查找化验结果</el-button>
			  </el-form-item>
			  <div class="alert alert-danger" v-if="archives.length < 1">
			  	没有找到此记录
			  </div>
			  <div class="alert alert-info" v-show="showResult" v-else>
			  	代号'{{archives.name}}'的检查结果：
				<span v-for="(item, index) in archives.result" class="label" :class="'bg-' + colors[index%colors.length]">
					{{item.item_name}}: {{item.result}} 
					<template v-if="item.normal==='5'">偏低</template>
					<template v-else-if="item.normal==='1'">偏高</template>
					<template v-else>正常</template>
				</span>
			  </div>
		</fieldset>	

	<el-form-item>
		<el-button type="primary" @click="onSubmit">立即创建</el-button>
		<el-button>取消</el-button>
	</el-form-item>
	</template>
</el-form>
<!-- </el-row> -->
</template>
<script>
	import { createUser } from '../../api/api'
	import { formatDate } from '../../utils/utils'
	import { COUNTRY } from '../../config/config'
	import { COLOR_SB } from '../../config/config'
	export default {
		data() {
			return {
				colors: COLOR_SB,
				form: {},
				options: COUNTRY,
				filteredOptions: [],
				showResult: false,
				isBindPatient: false,
				archives: {},
				rules: {
					name: [{ required: true, message: '请输入姓名', trigger: 'blur'}],
					age: [{ required: true, message: '请填写年龄' , trigger: 'blur'}],
					address: [{required: true, message: '请选择所在行政村', trigger: 'change'}]
				}
			}
		},
		methods: {
			formInit () {
				this.form = Object.assign({}, {name: '', mobile: '', identify: '', address: '', age: null, archive: ''})
				this.archives = Object.assign({}, {result: []})
			},
			filterCountry (query) {
				this.filteredOptions = this.options.filter(option => {
					return option.py.indexOf(query) > -1
				})
			},
			onFindArchive () {
				this.$store.commit('loading')
				axios.get('archive/' + this.form.archive)
				.then((response) => {
					if (response.data.length < 1) {
						this.$store.commit('loaded')
						this.formInit()
					}
					let resource = response.data[0]
					this.archives = resource
					if (resource.patient_id !== null ) {
						this.getPatient(resource.patient_id)
					}
					this.showResult = true
					this.$store.commit('loaded')
				})
				.catch((error) => {
					this.$store.commit('loaded')
					this.$message.error('读取数据出现错误，错误码为：' + error.response.status)
				})
			},
			getPatient (id) {
				axios.get('patient/' + id + '/edit')
				.then((response) => {
					this.form = Object.assign({}, response.data.data)
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
							password: this.form.password,
							status: parseInt(this.form.status)
						}
						let unit = this.selectedUnit
						let permissions = this.checkedPermission

						this.$store.commit('loading')

						createUser({ profile: profile, baseInfo:baseInfo, unit: unit, permissions: permissions })
						.then((response) => {

							this.$store.commit('loaded')

							this.$confirm('已成功添加用户，是否继续添加？', '提示', {
								confirmButtonText: '确定',
								cancelButtonText: '返回',
								type: 'success'
							}).then(() => {
								window.location.reload()
							}).catch(() => {
								this.$router.push({ path: '/home/users' })    
							})
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
				
			}
		},
		mounted () {
			this.formInit()
		}
	}
</script>