<template>
  <el-form :model="user" :rules="rules" ref="user" label-width="100px">
    <el-form-item label="手机号码" prop="mobile">
      <el-input v-model="user.mobile" placeholder="输入手机号"></el-input>
    </el-form-item>
    <el-row :gutter="10">
      <el-col :span="19">
        <el-form-item label="验证短信" prop="authCode">
          <el-input v-model="user.authCode" placeholder="输入您收到的验证码"></el-input>
        </el-form-item>
      </el-col>
      <el-col :span="5">
        <auth-code :data="{mobile:this.user.mobile}">获取验证码</auth-code>
      </el-col>
    </el-row>
    <el-form-item label="真实姓名" prop="name">
      <el-input v-model="user.name" placeholder="输入您的真实姓名"></el-input>
    </el-form-item>
    <el-form-item label="输入密码" prop="password">
      <el-input v-model="user.password" placeholder="输入不少于6位的密码"></el-input>
    </el-form-item>
    <el-form-item label="密码确认" prop="repassword">
      <el-input v-model="user.repassword" placeholder="再次输入您的密码"></el-input>
    </el-form-item>
    <button @click.prevent="onSubmit"  class="btn btn-primary btn-block" style="margin-bottom: 20px; font-size: 16px"><i class="fa fa-sign-in"></i> 完成注册</button>
  </el-form>

</template>
<script>
  import AuthCode from './AuthCode.vue'
  export default {
    data () {
      var validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'))
        } else {
          if (this.user.repassword !== '') {
            this.$refs.user.validateField('repassword')
          }
          callback()
        }
      }
      var validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.user.password) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
      }
      return {
        user: {
          mobile: '',
          authCode: '',
          password: '',
          repassword: '',
          name: ''
        },
        rules: {
          mobile: { required: true, message: '请输入手机号码', trigger: 'blur' },
          authCode: { required: true, message: '请输入短信验证码', trigger: 'blur' },
          name: { required: true, message: '请输入您的真实姓名', trigger: 'blur' },
          password: { required: true, validator: validatePass, trigger: 'blur'},
          repassword: { required: true, validator: validatePass2, trigger: 'blur'}
        }
      }
    },
    methods: {
      onSubmit: function () {
        const user = {
          mobile: this.user.mobile,
          password: this.user.password,
          authCode: this.user.authCode,
          name: this.user.name
        }
        axios.post('signup', this.user)
        .then((response) => {
          this.$alert('您已经成功注册，现在可以登录了', '注册成功', {
            confirmButtonText: '继续操作',
            callback: action => {
              window.location.href = '/login'
            }
          })
        })
        .catch((error) => {
          this.$alert(error.response.data, '注册失败', {
            confirmButtonText: '继续操作',
            callback: action => {
              window.location.href = '/login'
            }
          })
        })
      }
    },
    components: {
      AuthCode
    }
  }
</script>