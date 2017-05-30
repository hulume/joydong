<template>
  <div>
    <header>我的档案</header>
    <div class="theme bg-2" >
      <h1 style="margin-top: 25px;"><i class="fa fa-user-circle"></i></h1>
      <h3></h3>
    </div>
    <section>
      <cell title="等待数据整合" is-link :to="{ name: 'Home' }"></cell>
    </section>
  </div>
</template>
<script>
  import { Cell } from 'mint-ui'
  export default {
    components: {
      Cell
    }
  }
</script>
