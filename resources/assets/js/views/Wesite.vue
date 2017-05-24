<template>
	<div>
		<el-tabs v-model="activeTag">
			<el-button type="success" size="large" @click.native="showAddMenu" :disabled="menuRemain<1"><i class="fa fa-plus"></i> 添加菜单 (还可加{{menuRemain}}个)</el-button>
			<el-tab-pane label="底部导航菜单设置" name="main">
				<el-row :gutter="15">
					<el-col :span="12" v-for="(menu, index) in menu.main" :key="menu.id" style="margin-top:20px;">
						<el-card class="box-card">
							<div slot="header" class="clearfix">
								<span style="line-height: 28px;"><i :class="'fa fa-' + menu.icon"></i> {{menu.label}}</span>
								<el-button size="small" type="danger" @click.native="onDeleteMenu(index)"><i class="fa fa-trash"></i></el-button>
								<el-button size="small" type="primary" @click.native="showEditMenu(index)"><i class="fa fa-edit"></i></el-button>
								<!-- <el-button size="small" type="success" @click.native="showSelectMaterials"><i class="fa fa-wechat"></i></el-button> -->
							</div>
							<div v-if="typeof selectedMaterials[activeTag] === 'undefined'" class="el-upload-dragger">
								<i class="fa fa-plus"></i>
								<div class="el-upload__text" style="margin-top:20px">
									<a style="margin-right:20px;" @click="showSelectMaterials(index)"><i class="fa fa-wechat"></i> 添加图文素材</a>
									<a><i class="fa fa-link"></i> 添加跳转网页</a>
								</div>
							</div>
							<li v-for="(material, index) in selectedMaterials[activeTag]" :key="index" class="item">
								{{material.title}}
							</li>
						</el-card>
					</el-col>
				</el-row>
			</el-tab-pane>
			<el-tab-pane label="首页引导菜单设置" name="guide">微信图文素材关联</el-tab-pane>
			<el-tab-pane label="主题图设置" name="theme">主题图设置</el-tab-pane>
		</el-tabs>
		<!--新增界面-->
		<el-dialog title="新增" v-model="addMenuVisible" :close-on-click-modal="false">
			<el-form :model="addForm" label-width="120px" :rules="formRules" ref="addForm">
				<el-form-item label="菜单类型">
					{{currentMenuType}}
				</el-form-item>
				<el-form-item label="菜单名称" prop="label">
					<el-input v-model="addForm.label" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="排列顺序">
					<el-input-number v-model="addForm.order"></el-input-number>
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
		<el-dialog title="编辑" v-model="editMenuVisible" :close-on-click-modal="false">
			<el-form :model="editForm" label-width="80px" :rules="formRules" ref="editForm">
				<el-form-item label="菜单类型">
					{{currentMenuType}}
				</el-form-item>
				<el-form-item label="菜单名称" prop="label">
					<el-input v-model="editForm.label" auto-complete="off"></el-input>
				</el-form-item>
				<el-form-item label="排列顺序">
					<el-input-number v-model="editForm.order"></el-input-number>
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
		<el-dialog title="选取图文素材与菜单项关联" v-model="materialVisible" :close-on-click-modal="false" @close="initDialog">
		<!-- <li v-for="(m, index) in materials" :key="index" @click="onSelectMaterials(index)">
			{{m.title}} <span class="text-muted pull-right">{{m.digest}}</span>
		</li> -->
		<el-checkbox-group  v-model="selectedMaterials.id">
			<el-checkbox v-for="(m, index) in materials" :key="index" :label="materialsPaginate.start+index">{{m.title}}<span class="text-muted pull-right digest">{{m.digest}}</span></el-checkbox>
		</el-checkbox-group>
		<el-pagination layout="prev, pager, next" :total="materialsData.length" :page-size="15" :current-page.sync="materialsPaginate.page" @current-change="onChangeMaterialPage" style="float:right; margin-top: 20px; display: inline-block">
		</el-pagination>
		<el-button style="margin-top: 20px;" type="success" :disabled="selectedMaterials.length<1" @click.native="pushMaterials">关联所选项</el-button>
		</el-dialog>
	</div>
</template>
<script>
	import { getWeMenu, createWeMenu, getMaterial } from '../api/api'
	import { ICONS } from '../config/config'
	// import  Welink from '../components/WeLink'
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
				selectedMaterials: {
					id: [],
					data: []
				},
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
				activeMenuIndex: -1,
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
			materials () {
				let start = (this.materialsPaginate.page - 1) * this.materialsPaginate.size
				this.materialsPaginate.start = start
				return _.slice(this.materialsData, start, this.materialsPaginate.size * this.materialsPaginate.page)
			}
		},
		methods: {
			// 初始化值
			init () {
				this.addForm = { label: '', type: 1, order: 1, color: '#20a0ff', icon: 'child', route: '' }
				this.materialVisible = false
				this.addMenuVisible = false
				this.editMenuVisible = false
			},
			// modal中的图文在关闭modal后清空
			initDialog () {
				this.selectedMaterials.data = []
				this.selectedMaterials.id = []
			},
			// 读取菜单数据并调用初始方法
			loadMenu () {
				getWeMenu ()
				.then((response) => {
					this.menu = response.data.data
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
			showSelectMaterials (index) {
				this.materialVisible = true
				this.activeMenuIndex = index
			},
			// 监测图文素材当前页变化
			onChangeMaterialPage (page) {
				this.materialsPaginate.page = page
			},
			// 删除modal相关操作
			onDeleteMenu (index) {
				this.$confirm('确认要删除这个菜单吗？', '删除菜单', {
					confirmButtonText: '确定删除',
					cancelButtonText: '取消',
					type: 'error'
				}).then(() => {
					this.onDeletedMenu(this.menu[this.activeTag][index].id)
				}).catch(() => {
					this.$message.info('已取消删除')
				})
			},
			// 删除数据库中的菜单数据
			onDeletedMenu (id) {
				this.$store.commit('loading')
				axios.delete('wechat/menu/' + id)
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
				let result = []
				_.forEach(this.selectedMaterials.id, (value) => {
					result.push(this.materials[value])
				})
				// push to database
				// axios.post()
			},
			// 添加菜单写入数据库
			addSubmit () {
				this.loading = true
				createWeMenu(this.addForm)
				.then((response) => {
					this.loading = false
					this.$message.success('菜单添加成功')
					this.addMenuVisible = false
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
				axios.put('wechat/menu/' + this.editForm.id, this.editForm)
				.then((response) => {
					this.loading = false
					this.$message.success('修改成功')
					this.editMenuVisible = false
					this.editForm.isUrl = false
					this.loadMenu()
				})
				.catch((error) => {
					this.loading = false
					this.editMenuVisible = false
					this.editForm.isUrl = false
					this.$message.error('修改失败，请与管理员联系')
				})
			}
		},
		mounted () {
			this.loadMenu()
			this.loadMaterial()
		}
		// components: {
		// 	Welink
		// }
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
	.el-checkbox {
		border-bottom: dotted 1px #eee;
		padding: 10px;
		display: block;
		margin: 0!important;
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
</style>