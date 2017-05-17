<template>
  <div>
    <count-down v-on:event="getAuthCode" :color="color"><slot>获取短信验证码</slot></count-down>
  </div>
</template>
<script>
  import countDown from './CountDown.vue'
  export default {
    props: {
      data: {
        type: Object,
        require: true
      },
      color: {
        default: 'info'
      }
    },
    methods: {
      getAuthCode: function () {
        if (window.location.pathname === '/reset') {
          axios.post('getsms?findpass', this.data)
            .then((response) => {
              swal('操作成功', response.data.data, 'success')
            })
            .catch((error) => {
              let data = error.response.data
              swal('操作失败', data[Object.keys(data) [0]], 'error')
            })
        } else {
            axios.post('getsms', this.data)
              .then((response) => {
                this.$message({
                  message: '短信验证码已发送，请注意查收'
                })
              })
              .catch((error) => {
                let data = error.response.data
                this.$alert(data, '出错了！')
              })
        }
      }
    },
    components: {
      countDown
    }
  }
</script>