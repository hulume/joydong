<template>
	<div :class="'small-box bg-'+color">
		<div class="inner">
			<h3>{{title}}</h3>
			<p>{{digest}}</p>
		</div>
		<div class="icon">
			<i :class="'fa fa-'+icon"></i>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			title: String,
			digest: String,
			icon: String,
			color: String
		}
	}
</script>