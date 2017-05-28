<template>
  <div>
    <header> 
      快速登录/注册
    </header>
    <div class="container" style="padding-top: 20px; padding-bottom: 10px">
      <div class="form-inline">
        <span class="fa fa-mobile"></span>
          <input type="text"  name="mobile" placeholder="请输入手机号码">
      </div>
      <div class="form-inline">
        <span class="fa fa-commenting-o"></span>
          <input type="text"  name="mobile" placeholder="请输入验证码">
          <a class="button button-primary" style="float:right; margin-top: 5px;">发送验证码</a>
      </div>
        <a class="button button-primary" style="display:block; margin-top: 15px;">确认绑定</a>
    </div>
  </div>
</template>
<script>
import { Header } from 'mint-ui'
export default {
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
