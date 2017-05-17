<template>
<el-button type="success" id="getAuthcode" ref="button" @click.native="getAuthcode"><slot>获取短信验证码</slot></el-button>
</template>
<script>
  import {countdown} from '../utils/utils'
  export default {
    props: {
      duration: {
        default: 60
      },
      color: {
        default: 'info'
      }
    },
    methods: {
      getAuthcode () {
        countdown(document.getElementById('getAuthcode'), this.duration)
        this.$emit('event')
      }
    }
  }
</script>