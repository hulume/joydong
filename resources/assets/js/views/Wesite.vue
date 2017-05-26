<template>
	<div>
		<el-tabs v-model="activeTag">
			<el-button v-show="activeTag!=='theme'" type="success" size="large" @click.native="showAddMenu" :disabled="menuRemain<1"><i class="fa fa-plus"></i> 添加菜单 (还可加{{menuRemain}}个)</el-button>
			<!-- 底部导航开始 -->
			<el-tab-pane label="底部导航菜单设置" name="main">
				<el-row :gutter="15">
					<el-col :span="12" v-for="(menu, index) in menu[activeTag]" :key="menu.id" style="margin-top:20px;">
						<el-card class="box-card">
							<div slot="header" class="clearfix">
								<span style="line-height: 28px;"><i :class="'fa fa-' + menu.icon"></i> {{menu.label}}</span>
								<el-button size="small" type="danger" @click.native="onDeleteMenu(menu.id)"><i class="fa fa-trash"></i></el-button>
								<el-button size="small" type="primary" @click.native="showEditMenu(index)"><i class="fa fa-edit"></i></el-button>
								<el-button v-show="menu['link']!==null && menu['is_url']===0" size="small" type="success" @click.native="editLink(index)"><i class="fa fa-wechat"></i></el-button>
							</div>
							<div v-if="menu['link']===null" class="el-upload-dragger">
								<i class="fa fa-plus"></i>
								<div class="el-upload__text" style="margin-top:20px">
									<a style="margin-right:20px;" @click="showSelectMaterials(menu.id)"><i class="fa fa-wechat"></i> 指定图文素材</a>
									<a @click="onSetUrl(menu.id)"><i class="fa fa-link"></i> 指定跳转网页</a>
								</div>
							</div>
							<div v-else>
								<template v-if="menu['is_url']===0">
									<li  v-for="item in menu['link']">
										<a :href="item.url" target="_blank">{{item.title}}</a>
									</li>
								</template>
								<template v-else>
									<li>
										<a  :href="menu.link" target="_blank">{{menu.link}} </a><a class="text-muted" @click="onSetUrl(menu.id)"><i class="fa fa-edit"></i></a>
									</li>
								</template>
							</div>
						</el-card>
					</el-col>
				</el-row>
			</el-tab-pane>
			<!-- 引导菜单开始 -->
			<el-tab-pane label="首页引导菜单设置" name="guide">
				<el-row :gutter="15">
					<el-col :span="12" v-for="(menu, index) in menu[activeTag]" :key="menu.id" style="margin-top:20px;">
						<el-card class="box-card">
							<div slot="header" class="clearfix">
								<span style="line-height: 28px;"><i :class="'fa fa-' + menu.icon"></i> {{menu.label}}</span>
								<el-button size="small" type="danger" @click.native="onDeleteMenu(menu.id)"><i class="fa fa-trash"></i></el-button>
								<el-button size="small" type="primary" @click.native="showEditMenu(index)"><i class="fa fa-edit"></i></el-button>
								<el-button v-show="menu['link']!==null && menu['is_url']===0" size="small" type="success" @click.native="editLink(index)"><i class="fa fa-wechat"></i></el-button>
							</div>
							<div v-if="menu['link']===null" class="el-upload-dragger">
								<i class="fa fa-plus"></i>
								<div class="el-upload__text" style="margin-top:20px">
									<a style="margin-right:20px;" @click="showSelectMaterials(menu.id)"><i class="fa fa-wechat"></i> 指定图文素材</a>
									<a @click="onSetUrl(menu.id)"><i class="fa fa-link"></i> 指定跳转网页</a>
								</div>
							</div>
							<div v-else>
								<template v-if="menu['is_url']===0">
									<li  v-for="item in menu['link']">
										<a :href="item.url" target="_blank">{{item.title}}</a>
									</li>
								</template>
								<template v-else>
									<li>
										<a  :href="menu.link" target="_blank">{{menu.link}} </a><a class="text-muted" @click="onSetUrl(menu.id)"><i class="fa fa-edit"></i></a>
									</li>
								</template>
							</div>
						</el-card>
					</el-col>
				</el-row>
			</el-tab-pane>
			<!-- 主题图开始 -->
			<el-tab-pane label="主题图设置" name="theme">
			<upload v-show="menuRemain > 0" apiUrl="wesite/upload" :source.sync="themePath" type="button" @update="onUpload">还能上传{{menuRemain}}张</upload>
				<el-row :gutter="15">
					<el-col :span="8" v-for="(m, index) in menu.theme" :key="index">
						<el-card class="box-card">
						<div slot="header" class="clearfix">
							<a :href="m.link" target="_blank" v-if="m['link']!==null" class="btn btn-primary">查看链接 <i class="fa fa-search-plus"></i> </a>
							<span v-else class="text-muted">尚未指定跳转目标</span>
							<el-button size="small" type="danger" @click.native="onDeleteMenu(m.id)"><i class="fa fa-trash"></i></el-button>
						</div>
						<upload apiUrl="wesite/upload" :source.sync="m.theme_img" @update="onChangeTheme(m.id)"></upload>
							<p style="margin-top:20px; text-align: center">
								<a style="margin-right:20px;" @click="showSelectMaterials(m.id)"><i class="fa fa-wechat"></i> 指定图文素材</a>
								<a @click="onSetUrl(m.id)"><i class="fa fa-link"></i> 指定跳转网页</a>
							</p>
						</el-card>
					</el-col>
				</el-row>
			</el-tab-pane>
		</el-tabs>
		<!--新增界面-->
		<el-dialog title="新增菜单" v-model="addMenuVisible" :close-on-click-modal="false">
			<el-form :model="addForm" label-width="120px" :rules="formRules" ref="addForm">
				<el-form-item label="菜单类型">
					{{currentMenuType}}
				</el-form-item>
				<el-form-item label="菜单名称" prop="label">
					<el-input v-model="addForm.label" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="排列顺序">
					<el-input-number v-model="addForm.order" :min="0" :max="100"></el-input-number>
				</el-form-item>
				<el-form-item label="背景色" v-show="activeTag==='guide'">
					<el-color-picker v-model="addForm.color"></el-color-picker>
				</el-form-item>
				<el-form-item label="图标">
					<el-radio-group v-model="addForm.icon">
						<el-radio-button v-for="(icon, index) in icons" :key="index"  :label="icon"><i :class="'fa fa-'+icon"></i></el-radio-button>
					</el-radio-group>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button @click.native="addMenuVisible = false">取消</el-button>
				<el-button type="primary" @click.native="addSubmit" :loading="loading">提交</el-button>
			</div>
		</el-dialog>
		<!--编辑界面-->
		<el-dialog title="编辑" v-model="editMenuVisible" :close-on-click-modal="false" >
			<el-form :model="editForm" label-width="80px" :rules="formRules" ref="editForm">
				<el-form-item label="菜单类型">
					{{currentMenuType}}
				</el-form-item>
				<el-form-item label="菜单名称" prop="label">
					<el-input v-model="editForm.label" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="排列顺序">
					<el-input-number v-model="editForm.order" :min="0" :max="100"></el-input-number>
				</el-form-item>
				<el-form-item label="背景色" v-show="activeTag==='guide'">
					<el-color-picker v-model="editForm.color"></el-color-picker>
				</el-form-item>
				<el-form-item label="图标">
					<el-radio-group v-model="editForm.icon">
						<el-radio-button v-for="(icon, index) in icons" :key="index"  :label="icon"><i :class="'fa fa-'+icon"></i></el-radio-button>
					</el-radio-group>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer">
				<el-button type="primary" @click.native="editSubmit" :loading="loading">提交</el-button>
			</div>
		</el-dialog>
		<!-- 图文消息库界面 -->
		<el-dialog title="选取图文素材(排列顺序为您选取的先后顺序)" v-model="materialVisible" :close-on-click-modal="false" @close="initDialog">
			<el-checkbox-group  v-model="selectedMaterialIds">
				<el-checkbox v-for="m in formaterials" :key="m.id" :label="m.id">{{m.title}}<span class="text-muted pull-right digest">{{m.digest}}</span></el-checkbox>
			</el-checkbox-group>
			<el-pagination layout="prev, pager, next" :total="materialsData.length" :page-size="15" :current-page.sync="materialsPaginate.page" @current-change="onChangeMaterialPage" style="float:right; margin-top: 20px; display: inline-block">
			</el-pagination>
			<el-button style="margin-top: 20px;" :loading="loading" type="success" :disabled="selectedMaterialIds.length<1" @click.native="pushMaterials">关联所选项</el-button>
		</el-dialog>
	</div>
</template>
<script>
	import { getWeMenu, createWeMenu, getMaterial } from '../api/api'
	import { ICONS } from '../config/config'
	import Upload from '../components/UploadImg'
	export default {
		data () {
			return {
				// 全部的菜单
				menu: {
					main: [],
					guide: [],
					theme: []
				}, 
				// 已选的图文
				selectedMaterialIds: [],
				// 约束菜单数量
				menuLimit: {
					main: 4,
					guide: 4,
					theme: 3
				},
				// 原始图文素材数据
				materialsData: [],
				// 图文素材分页
				materialsPaginate: {
					size: 12,
					page: 1,
					start: 0
				},
				// 当前所在TAB
				activeTag: 'main',
				// 初始菜单排序
				activeMenuId: -1,
				materialVisible: false,
				addMenuVisible: false,
				editMenuVisible: false,
				loading: false,
				icons: ICONS,
				//编辑菜单界面数据
				editForm: {
					label: '',
					type: 1,
					order: 1,
					color: '',
					icon: '',
					url: ''
				},
				themePath: '',
				//新增菜单界面数据
				addForm: {},
				formRules: {
					label: [
					{ required: true, message: '请输入菜单名称', trigger: 'blur' }
					]
				}
			}
		},
		computed: {
			// 当前菜单的类型
			currentMenuType () {
				if (this.activeTag === 'main') {
					this.addForm.type = 1
					return '底部导航菜单'
				} else if (this.activeTag === 'guide') {
					this.addForm.type = 2
					return '首页引导菜单'
				} else {
					this.addForm.type = 3
					return '主题图'
				}
			},
			// 约束菜单可添加数量
			menuRemain () {
				return this.menuLimit[this.activeTag] - this.menu[this.activeTag].length
			},
			// 格式化图文素材为分页形式数据
			formaterials () {
				let start = (this.materialsPaginate.page - 1) * this.materialsPaginate.size
				this.materialsPaginate.start = start
				return _.slice(this.materialsData, start, this.materialsPaginate.size * this.materialsPaginate.page)
			}
		},
		methods: {
			// 初始化值
			init () {
				this.addForm = { label: '', type: 1, order: 1, color: '#20a0ff', icon: 'child', route: '' }
				this.editForm = { label: '', type: 1, order: 1, color: '#20a0ff', icon: 'child', route: '' }
				this.materialVisible = false
				this.addMenuVisible = false
				this.editMenuVisible = false
				this.loading = false
			},
			// modal中的图文在关闭modal后清空
			initDialog () {
				this.selectedMaterialIds = []
			},
			// 读取菜单数据并调用初始方法
			loadMenu () {
				this.$store.commit('loading')
				getWeMenu ()
				.then((response) => {
					this.menu = response.data.data
					this.$store.commit('loaded')
					this.init()
				})
			},
			// 读取图文素材
			loadMaterial () {
				getMaterial ()
				.then((response) => {
					this.materialsData = response.data
				})
			},
			// 添加菜单modal显示状态
			showAddMenu () {
				this.addMenuVisible = true
			},
			// 编辑菜单modal显示状态
			showEditMenu (index) {
				this.editForm = this.menu[this.activeTag][index]
				this.editMenuVisible = true
			},
			// 选择图文素材显示状态
			showSelectMaterials (id) {
				this.materialVisible = true
				this.activeMenuId = id
			},
			// 监测图文素材当前页变化
			onChangeMaterialPage (page) {
				this.materialsPaginate.page = page
			},
			// 删除modal相关操作
			onDeleteMenu (id) {
				this.$confirm('确认要删除这个菜单吗？', '删除菜单', {
					confirmButtonText: '确定删除',
					cancelButtonText: '取消',
					type: 'error'
				}).then(() => {
					this.onDeletedMenu(id)
				}).catch(() => {
					this.$message.info('已取消删除')
				})
			},
			// 删除数据库中的菜单数据
			onDeletedMenu (id) {
				this.$store.commit('loading')
				axios.delete('wesite/menu/' + id)
				.then((response) => {
					this.$store.commit('loaded')
					this.$message.success('删除成功')
					this.loadMenu()
				})
				.catch((error) => {
					this.$message.error('未能删除菜单，请与管理员联系')
				})
			},
			// 选择素材后写入数据库操作
			pushMaterials () {
				// 如果是主题图，则只把第一个选择的素材url放入菜单中
				if (this.activeTag === 'theme') {
					let link = (_.find(this.materialsData, ['id', this.selectedMaterialIds[0]]).url)
					this.loading = true
					axios.put('wesite/menu/' + this.activeMenuId + '?do=link', {link: link})
						.then((response) => {
							this.$message.success(response.data.data)
							this.loadMenu()
						})
				} else {
					let result = []
					_.forEach(this.selectedMaterialIds, (value) => {
						result.push(_.find(this.materialsData, ['id', value]))
					})
					let wx_menu_id = this.activeMenuId
					this.loading = true
					// 只更新link
					axios.put('wesite/menu/' + wx_menu_id + '?do=link', {link: result})
					.then((response) => {
						this.$message.success(response.data.data)
						this.loadMenu()
					})
				}
			},
			// 添加菜单写入数据库
			addSubmit () {
				this.loading = true
				createWeMenu(this.addForm)
				.then((response) => {
					this.$message.success('菜单添加成功')
					this.loadMenu()
				})
				.catch((error) => {
					this.loading = false
					this.addMenuVisible = false
					this.addForm.isUrl = false
					this.$message.error('添加失败，请与管理员联系')
				})
			},
			// 编辑菜单数据库操作
			editSubmit () {
				this.loading = true
				axios.put('wesite/menu/' + this.editForm.id, this.editForm)
				.then((response) => {
					this.$message.success('修改成功')
					this.loadMenu()
				})
				.catch((error) => {
					this.loading = false
					this.editMenuVisible = false
					this.editForm.isUrl = false
					this.$message.error('修改失败，请与管理员联系')
				})
			},
			// 编辑所选内容
			editLink (index) {
				let menu = this.menu[this.activeTag][index]
				this.activeMenuId = menu.id
				this.selectedMaterialIds = _.map(menu.link, 'id')
				this.materialVisible = true
			},
			// 显示提交URL对话框
			onSetUrl (id) {
				let currentUrl = _.find(this.menu[this.activeTag], ['id', id]).link
				this.$prompt('请输入有效的网址(须带如http://)', '指定跳转网页', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					inputPattern: /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/,
					inputErrorMessage: '网页地址格式不正确',
					inputValue: currentUrl
				}).then(( {value} ) => {
					axios.put('wesite/menu/' + id + '?do=url', {url: value})
						.then((response) => {
							this.$message.success(response.data.data)
							this.loadMenu()
						})
				}).catch (() => {
					window.console.log('add url canceled')
				})
			},
			onUpload (id) {
				let data = {
					label: '主题图',
					theme_img: this.menu,
					type: this.addForm.type
				}
				createWeMenu(data)
				.then((response) => {
					this.$message.success('菜单添加成功')
					this.loadMenu()
				})
				.catch((error) => {
					this.loading = false
					this.addMenuVisible = false
					this.$message.error('添加失败，请与管理员联系')
				})
			},
			onChangeTheme (id) {
				let data = {
					theme_img: _.find(this.menu['theme'], ['id', id])['theme_img']
				}
				axios.put('wesite/menu/' + id, data)
					.then((response) => {
						this.$message.success(response.data.data)
						this.loadMenu()
					})
			}
		},
		mounted () {
			this.loadMenu()
			this.loadMaterial()
		},
		components: {
			Upload
		}
	}
</script>
<style scoped>
	.el-radio-button {
		margin: 2px;
		border-left: 1px solid #bfcbd9;
	}
	.el-radio-button:first-child {
		border-left: none;
	}
	.el-alert {
		border-radius: 0;
	}
	.el-button--small {
		float: right;
		/*margin: 0 2px 0 0;*/
		margin-left: 10px;
	}
	.el-checkbox, li {
		border-bottom: dotted 1px #eee;
		padding: 10px;
		display: block;
		margin: 0!important;
	}
	li {
		list-style-type: none;
	}
	.el-checkbox .digest {
		width: 270px;
		overflow: hidden;
		text-align: right;
		text-overflow: ellipsis; 
	}
	.el-upload-dragger {
		width: auto;
		padding: 10px;
		cursor: auto;
	}
	.el-upload-dragger .fa-plus {
		font-size: 67px;
		color: #97a8be;
		margin: 40px 0 16px;
		line-height: 50px;
	}
	.box-card {
		margin-top: 15px;
	}
</style>