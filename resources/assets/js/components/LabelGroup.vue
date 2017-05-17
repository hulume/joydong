<template>
	<p>
		<span v-for="(label, index) in lists" class="label" :class="'bg-' + colors[index%colors.length]">
			{{label.label}}
		</span>
	</p>
</template>
<script>
	import { COLOR_SB } from '../config/config'
	export default {
		data () {
			return {
				colors: COLOR_SB
			}
		},
		props: {
			lists: null
		}
	}
</script>