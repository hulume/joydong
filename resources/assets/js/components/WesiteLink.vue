<template>
	<div>
		<div class="row">
			<div class="col-sm-4">
				<div class="panel">
					<div class="panel-heading">
						<i class="fa fa-cubes"></i> 已选素材列表
					</div>
					<div class="panel-body">
						<p class="text-center" v-if="selected.length < 1">尚未选取</p>
						<draggable :list = "selected" :element="'ul'" :options="{animation: 200}" @start="drag=true" @end="drag=false">
							<li class="menu" v-for="(item, index) in selected" :key="item.media_id">
								<span style="padding-bottom: 10px;"><i class="fa fa-caret-right"></i> <a :href="item.url" target="_blank">{{item.title}}</a></span>
								<span class="btn-group pull-right">
									<a @click.prevent="removeItem(item, index)" class="text-muted"><i class="fa fa-trash"></i></a>
									<a href="javascript:void(0)" class="text-muted move" data-toggle="tooltip" data-placement="top" title="拖动排序"><i class="fa fa-arrows"></i></a>
								</span>
							</li>
						</draggable>
					</div>
				</div>
			</div>
			<div class="col-sm-8">
				<div class="panel">
					<div class="panel-heading">
						<i class="fa fa-wechat"></i> 公众号图文素材列表
					</div>
					<div class="panel-body">
						<p class="text-center" v-if="material.length < 1">素材库空了，您可以去<a target="_blank" href="http://mp.weixin.qq.com">微信公众号平台添加或更新</a></p>
						<ul>
							<template v-for="(item, parent_index) in material">
								<li v-for="(m, child_index) in item.content.news_item" @click.prevent="addToModel(m, parent_index, child_index)"><i class="fa fa-caret-left"></i> {{m.title}} <span class="text-muted pull-right">{{m.digest}}</span></li>
							</template>
						</ul>					
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-2 col-sm-offset-5">
				<button @click.prevent="onSave"  class="btn btn-lg btn-block btn-info" :disabled="selected.length<1">保存</button>
			</div>
		</div>
	</div>
</template>

<script>
	import Draggable from 'vuedraggable'
	export default {
		props: {
			list: Array,
			model: Array
		},
		data () {
			return {
				material: [],
				selected: this.list
			}
		},
		mounted () {
			this.loadMaterial()
		},
		methods: {
			loadMaterial () {
				this.$store.commit('loading')
				axios.get('/wechat/material')
				.then((response) => {
					this.material = response.data.item
					this.$store.commit('loaded')
				})
				.catch((error) => {
					toastr.error(error)
					this.$store.commit('loaded')
				})
			},
			addToModel (m, parent_index, child_index) {
				this.model.push(m)
				if (this.material[parent_index].content.news_item.length > 1) {
					this.material[parent_index].content.news_item.splice(child_index, 1)
				} else {
					this.material.splice(parent_index, 1)
				}
				// window.console.log(this.material[parent_index])
			},
			removeItem (item, index) {
				this.material.push({
					content: {
						news_item: [item]
					}
				})
				this.model.splice(index, 1)
			},
			onSave () {
				this.$store.commit('loading')
				let data = _.map(this.selected, _.partialRight(_.pick, ['title', 'digest', 'url', 'thumb_media_id']))
				axios.post('/wesite/link', data)
					.then((response) => {
						toastr.success(response.data)
						this.$store.commit('loaded')
					})
					.catch((error) => {
						toastr.error(error)
						this.$store.commit('loaded')
					})
			}
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
		/*border: solid 1px #f2f2f2;*/
	}
	.panel-body {
		padding: 15px 0;
	}
	.btn-group {
		visibility: hidden;
	}
	.btn-group i {
		margin: 0 5px;
	}
	ul {
		padding: 0;
	}
	li {
		border-bottom: dotted 1px #eee;
		padding: 10px;
		cursor: pointer;
		list-style-type: none;
	}
	li:hover {
		background-color: #f9f9f9;
	}
	li:hover > .btn-group, .submenu:hover > .btn-group {
		visibility: visible;
	}
</style>