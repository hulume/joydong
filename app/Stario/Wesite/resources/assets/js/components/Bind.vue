<template>
  <div>
    <header> 
      绑定手机号码
    </header>
    <div class="container" style="padding-top: 20px; padding-bottom: 10px">
      <div class="form-inline">
        <span class="fa fa-mobile"></span>
          <input type="text" v-model="mobile"   placeholder="请输入手机号码">
      </div>
      <div class="form-inline">
        <span class="fa fa-commenting-o"></span>
          <input type="text" v-model="authcode"  placeholder="请输入验证码">
          <button class="button button-primary" @click.prevent="onSend" :disabled="countdown" style="float:right; margin-top: 5px;">发送验证码</button>
      </div>
        <button class="button button-primary btn-block" style="display:block; margin-top: 15px;" :disabled="!formValid" @click.prevent="onSubmit">确认绑定</button>
    </div>
  </div>
</template>
<script>
import { Header, Toast } from 'mint-ui'
import { isMobile } from '../utils'
export default {
  data () {
    return {
      mobile: '',
      authcode: '',
      send_disabled: false
    }
  },
  computed: {
    countdown () {
      if (!isMobile(this.mobile)) {
        return true
      } else if ( this.send_disabled ) {
        return true
      } else {
        return false
      }
    },
    formValid () {
      return isMobile(this.mobile) && this.authcode !== ''
    }
  },
  methods: {
    onSend () {
      this.send_disabled = true
      this.$store.commit('loading')
      axios.post('api/wesite/sms', {mobile: this.mobile})
      .then((response) => {
        this.$store.commit('loaded')
        Toast(response.data.data)
      })
      .catch((error) => {
        this.$store.commit('loaded')
        Toast(error.response.data.error)
      })
    },
    onSubmit () {
      this.$store.commit('loading')
      axios.post('api/wesite/bind', { mobile: this.mobile, authcode: this.authcode })
      .then((response) => {
        this.$store.commit('loaded')
        this.$store.dispatch('bind', response.data.data)
        this.$router.push({name: 'User'})
      })
      .catch((error) => {
        this.$store.commit('loaded')
        Toast(error.response.data.error)
      })
    }
  },
  components: {
    'mHeader': Header
  }
}
</script>
<style scoped>
  .form-inline {
    border-top: 1px solid #eee;
    box-shadow: 0 1px 3px #eee;
    position: relative;
    background-color: #fff;
    height: 50px;
    margin-top: 10px;
    padding: 0 15px;
    border: 0;
  }
  input, span {
    padding: 5px 0;
    margin-top: 5px;
    height: 30px;
    line-height: 30px;
  }
  input {
    text-indent: 30px;
    outline: 0;
    border: none;
    width: 50%;
    background-color: #fff;
    font-size: 14px;
  }
  span {
    position: absolute;
  }
</style>
