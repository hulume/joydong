<template>
  <div id="app">
    <div class="wrapper">
      <router-view></router-view>
    </div>
    <foot></foot>
  </div>
</template>

<script>
import Foot from './components/Footer'
export default {
  mounted () {
    this.$store.commit('loading')
    this.$store.dispatch('LOAD_DATA')
  },
  components: {
    Foot
  }
}
</script>
