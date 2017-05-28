<template>
  <div class="home">
    <swipe :speed="880">
            <swipe-item v-for="(item, index) in site.theme" :key="index" class="slide"><a :href="item.link"><img :src="item.theme_img" alt="主题图片"></a></swipe-item>
    </swipe>
    <div class="container">
      <div class="icon-group" v-for="item in site.guide">
        <a class="icon-box" @click="goTo('/guide/'+item.id)">
          <i :class="'fa circle-icon fa-'+item.icon" :style="'background-color:'+item.color"></i>
          <p>{{item.label}}</p>
        </a>
      </div>
    </div>
    <div class="container">
      <cell v-for="(item, index) in home.link" :key="index"
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
  import { Swipe, SwipeItem, Cell } from 'mint-ui'
  export default {
    computed: {
      site () {
        return this.$store.state.site
      },
      home () {
        return this.$store.getters.home
      }
    },
    methods: {
      goTo: function (path) {
        this.$router.replace(path)
      }
    },
    components: {
      Swipe, SwipeItem, Cell
    }
  }
</script>
<style>
  .icon-group {
    width: 25%;
    float: left;
    margin-bottom:20px; 
  }
  .icon-group p {
    margin-bottom: 0;
  }
  .mint-swipe-item img {
    width: 100%;
    height: 100%;
  }
  .mint-swipe {
    height: 180px !important;
    color: #fff;
    font-size: 30px;
    text-align: center;
    margin-bottom: 20px;
  }
  .icon-group {
    text-align: center;
  }
  .circle-icon {
    font-size: 2em !important;
    width: 2em;
    text-align: center;
    line-height: 2em !important;
    color: #fff;
    border-radius: 1em;
  }
  .bg-red {
    background-color: #ff7e2e;
  }
  .bg-yellow {
    background-color: #ffa900;
  }
  .bg-green {
    background-color: #74c200;
  }
  .bg-blue {
    background-color: #1ca3fe;
  }

</style>
