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
						<welink :list="partOne" :model="partOne"></welink>
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
	import welink from '../components/WesiteLink'
	export default {
		data () {
			return {
				partOne: [],
				partTwo: [],
				partThree: []
			}
		},
		components: {
			welink
		}
	}
</script>
<style scoped>
</style>
