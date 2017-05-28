<template>
  <div>
    <header>我的档案</header>
    <div class="theme">
      <div class="ucenter">
        <div class="icon-box">
          <img src="https://img.guahao.com/img/character/unknow-l.png" alt="未知">
          <p>Partoo</p>
        </div>
      </div>
    </div>
    <section>
      <cell title="2017年1月3日 门诊" is-link :to="{ name: 'Login' }"></cell>
      <cell title="2017年1月6日 门诊" is-link :to="{ name: 'Home' }"></cell>
      <cell title="2017年1月8日 门诊" is-link :to="{ name: 'Login' }"></cell>
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
