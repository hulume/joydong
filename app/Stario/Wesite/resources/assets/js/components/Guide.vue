<template>
	<div class="home">
		<div :class="'theme bg-'+random" >
			<p><i :class="'fa fa-'+list.icon"></i> {{list.label}}</p>
		</div>
		<div class="container">
			<cell v-for="(item, index) in list.link" :key="index"
			:title="item.title"
			is-link
			:to = "item.url"
			:label="item.digest">
			<img :src="item.thumb_url">
		</cell>
	</div>
</div>
</template>

<script>
	import { Cell } from 'mint-ui'
	export default {
		data () {
			return {
				random: 1
			}
		},
		computed: {
			list () {
				let id = parseInt(this.$route.params.id)
				let menu = this.$store.state.site.guide
				let temp = menu.filter(function (v) {
					return v.id === id
				})[0]
				let result = Object.assign({}, temp)
				return result
      }
    },
    components: {
    	Cell
    },
    watch: {
    	'$route' () {
    		this.random = Math.floor(Math.random() * 6) + 1
    	}
    }
  }
</script>
<style>
	.theme p {
		font-size: 2em;
		text-shadow: 1px 1px 20px rgba(255, 255, 255, .7);
	}
</style>
