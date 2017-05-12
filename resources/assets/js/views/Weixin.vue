<template>
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#first" data-toggle="tab" aria-expanded="true">医疗服务</a>
					</li>
					
					<li>
						<a href="#second" data-toggle="tab" aria-expanded="true">医院介绍</a>
					</li>
					<li>
						<a href="#third" data-toggle="tab" aria-expanded="true">子栏目</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="first">
						<a @click.prevent="addMenu"  class="btn btn-primary">
							<i class="fa fa-plus"></i> 选取图文素材
						</a>
					</div>
					<div class="tab-pane" id="second">
						SITE
					</div>
					<div class="tab-pane" id="third">
						SITE
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				menu: {
					title: '',
					icon: '',
					color: ''
				}
			}
		}
	}
</script>
<style scoped>
</style>
