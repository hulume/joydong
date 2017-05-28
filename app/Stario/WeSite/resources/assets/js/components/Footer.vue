<template>
	<!-- <div>
    <tabbar v-model="selected" style="position:fixed">
    <template v-for="(item, index) in mainBar">
      <tab-item v-if="item.is_url===0" @click.native="goTo('/page/'+item.id)" :id="item.id" :key="index">
        <i slot="icon" :class="'tab-icon fa fa-'+item.icon" aria-hidden="true"></i> {{item.label}}
      </tab-item>
      <tab-item v-else>
        <a :href="item.link">
          <div class="mint-tab-item-icon"><i aria-hidden="true" :class="'tab-icon fa fa-'+item.icon"></i></div>
          <div class="mint-tab-item-label"> {{item.label}}</div>
        </a>
      </tab-item>
    </template>
    </tabbar>
  </div> -->
  <div class="mint-tabbar" style="position: fixed;">
  <template v-for="(item, index) in mainBar">
  <a class="mint-tab-item" v-if="index===0" :class="{'is-selected': currentName==='Home'}" @click="goTo('/')" :key="index">
    <div class="mint-tab-item-icon"><i aria-hidden="true" :class="'tab-icon fa fa-'+item.icon"></i></div> 
    <div class="mint-tab-item-label">{{item.label}}</div>
    </a>
    <a class="mint-tab-item" v-else-if="item.is_url===0" :class="{'is-selected': currentId===item.id}" @click="goTo('/page/'+item.id)" :key="index">
      <div class="mint-tab-item-icon"><i aria-hidden="true" :class="'tab-icon fa fa-'+item.icon"></i></div> 
      <div class="mint-tab-item-label">{{item.label}}</div>
      </a>
      <a v-else class="mint-tab-item" :key="index"  :href="item.link">
        <div class="mint-tab-item-icon"><i aria-hidden="true" :class="'tab-icon fa fa-'+item.icon"></i></div> 
        <div class="mint-tab-item-label">{{item.label}}</div>
        </a>
  </template>
    </div>
  </template>
  <script>
    export default {
      // data () {
      //   return {
      //     active: 0
      //   }
      // },
      methods: {
        goTo: function (path) {
          this.$router.replace(path)
        }
      },
      computed: {
        mainBar () {
          return this.$store.state.site.main
        },
        currentId () {
          return parseInt(this.$route.params.id)
        },
        currentName () {
          return this.$route.name
        }
      }
    }
  </script>
  <style>
    .tab-icon {
      font-size: 24px;
    }
    a:link, a:visited, a:focus, a:hover, a:active {
      color: inherit;
    }
    /*.mint-tab-item-label a {
      display: inline-block;
      width: 100%;
      height: 100%;
    }
    .mint-tab-item-label a i {
      width: 24px;
      height: 24px;
      margin: 0 auto 5px;
    }*/
  </style>
