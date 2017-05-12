<template>
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#menu" data-toggle="tab" aria-expanded="true">公众号菜单</a>
					</li>
					<li>
						<a href="#site" data-toggle="tab" aria-expanded="true">微网站设置</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="menu">
						<div class="row">
							<div class="col-sm-4">
								<div class="panel">
									<div class="panel-heading">
										<span><i class="fa fa-list-ul"></i> 菜单配置</span>
										<template v-if="menusFulled"><span class="pull-right text-muted">一级菜单已满3个</span></template>
										<a v-else class="pull-right text-muted" @click.prevent="addMenu"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="添加一级菜单"></i></a>
									</div>
									<div class="panel-body">
										<p class="text-center" v-if="menu.length < 1">尚未配置</p>
											<draggable :list= "menu" :element="'ul'" :options="{animation: 200}" @start="drag=true" @end="onEnd">
											<li class="menu" v-for="(item, index_parent) in menu" :class="{'item-active': item.name === selected.name}" @click.stop.prevent="choose(item.name, index_parent)" :key="item.name">
												<span style="padding-bottom: 10px;"><i class="fa fa-plus-square"></i> {{item.name}}</span>
												<span class="btn-group pull-right">
													<a v-if=" typeof item.sub_button === 'undefined' || item.sub_button.list.length < 5" @click.prevent="addSubmenu" class="text-muted" data-toggle="tooltip" data-placement="top" title="添加子菜单"><i class="fa fa-plus"></i></a>
													<template v-else><span class="pull-right text-muted">子菜单已满5个</span></template>
													<a @click.prevent="editMenu" class="text-muted" data-toggle="tooltip" data-placement="top" title="修改菜单"><i class="fa fa-edit"></i></a>
													<a @click.prevent="removeMenu(index_parent)" class="text-muted" data-toggle="tooltip" data-placement="top" title="删除菜单"><i class="fa fa-trash"></i></a>
													<a href="javascript:void(0)" class="text-muted move" data-toggle="tooltip" data-placement="top" title="拖动排序"><i class="fa fa-arrows"></i></a>
												</span>
												<draggable v-if="item.sub_button" :list="item.sub_button.list" :element="'ul'" :options="{animation: 200}" @start="drag=true" @end="drag=false">
													<li class="submenu" v-for="el in item.sub_button.list" :class="{'item-active': el.name === selected.name}" @click.stop.prevent="choose(el.name, index_parent)" :key="el.name">
														<span><i class="fa fa-caret-right"></i> {{el.name}}</span>
														<span class="btn-group pull-right">
															<a @click.prevent="editMenu" class="text-muted" data-toggle="tooltip" data-placement="top" title="修改菜单"><i class="fa fa-edit"></i></a>
															<a @click.prevent="removeSubMenu(index)" class="text-muted" data-toggle="tooltip" data-placement="top" title="删除菜单"><i class="fa fa-trash"></i></a>
															<a  href="javascript:void(0)" class="text-muted move" data-toggle="tooltip" data-placement="top" title="拖动排序"><i class="fa fa-arrows"></i></a>
														</span>
													</li>
												</draggable>
											</li>
											</draggable>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="site">
						SITE
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import Draggable from 'vuedraggable'
	export default {
		data () {
			return {
				menu: [],
				selected: {
					name: '',
					index: 0
				}
			}
		},
		computed: {
			menusFulled () {
				return this.menu.length > 2
			}
			// menu: {
			// 	get () {
			// 		return this.$store.state.wxMenu
			// 	},
			// 	set (data) {
			// 		this.$store.commit('wxMenu', data)
			// 	}
			// }
		},
		methods: {
			addMenu () {
				swal({
					title: '填写菜单名称',
					text: '一级菜单名称不多于5个汉字或16个字母',
					type: 'input',
					showCancelButton: true,
					inputPlaceholder: '输入菜单名称',
					confirmButtonText: '添加',
					cancelButtonText: '取消'
				}, (input) => {
					if (input === false) {
						return false
					}
					if (input === '') {
						swal.showInputError('请输入菜单名称')
						return false
					}
					this.menu.push({
						name: input
					})

				})
			},
			removeMenu (index) {
				swal({
					title: '确定要删除一级菜单吗？',
					text: '删除后将无法恢复，请谨慎操作',
					type: 'warning',
					showCancelButton: true,
					cancelButtonText: '取消',
					confirmButtonText: '确定删除'
				}, () => {
					this.menu.splice(index, 1)
				})
			},
			addSubmenu () {
				swal({
					title: '填写子菜单名称',
					text: '子菜单名称不多于13个汉字或40个字母',
					type: 'input',
					showCancelButton: true,
					inputPlaceholder: '输入子菜单名称',
					confirmButtonText: '添加',
					cancelButtonText: '取消'
				}, (input) => {
					if (input === false) {
						return false
					}
					if (input === '') {
						swal.showInputError('请输入菜单名称')
						return false
					}
					// this.menu.submenu.push({
					// 	name: input
					// })
				})
			},
			choose (item, index) {
				if(!_.has(_.find(this.menu, ['name', item]), 'submenu'))
				{
					this.selected.name = item
					this.selected.index = index
				}
			},
			onUpdate () {
				window.console.log('aa')
			},
			onEnd (e) {
				// this.selected.index = e.oldIndex
				window.console.log('before:' + e.oldIndex)
				window.console.log('after:' + e.newIndex)
				this.selected.old = e.oldIndex
			},
			loadMenu () {
				axios.get('wechat/menu')
				.then((response) => {
					// this.$store.commit('wxMenu', response.data.button)
					this.menu = response.data.button
				})
			}
		},
		mounted () {
			this.loadMenu()
		},
		components: {
			Draggable
		}
	}
</script>
<style scoped>
	.panel-heading{
		background-color: #f5f5f5;
	}
	.panel {
		color: #555;
	}
	.panel-body {
		padding: 15px 0;
	}
	ul {
		padding: 0;
	}
	.menu {
		border-bottom: dotted 1px #eee;
		padding: 10px;
	}
	.menu, .submenu {
		cursor: pointer;
		list-style-type: none;
	}
	.submenu {
		padding: 10px 10px 10px 20px;
		margin-top: 1px;
	}
	.menu:hover {
		background-color: #f9f9f9;
	}
	.menu:hover > .btn-group, .submenu:hover > .btn-group {
		visibility: visible;
	}
	.btn-group {
		visibility: hidden;
	}
	.btn-group i {
		margin: 0 5px;
	}
	.item-active, .submenu:hover {
		color: #fff !important;
		background-color: #3c8dbc !important;
	}
	.submenu:hover i {
		color: #fff !important;
	}
	.item-active .text-muted {
		color: #fff !important;
	}
</style>
