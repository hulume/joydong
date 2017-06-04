@extends('icenter::layouts.mini')
@section('content')
<!-- TAB START -->
<el-row class="login-tab">
  <el-col :span="12"  class="active"><a href="login">用户登录</a></el-col>
  <el-col :span="12"><a href="register">注册新用户</a></el-col>
</el-row>
<!-- FORM START -->
<form method="POST" role="form" action="{{ url('/login') }}">
  {{ csrf_field() }}
  <div class="el-input el-input-group el-input-group--prepend"><div class="el-input-group__prepend"><i class="fa fa-mobile" style="font-size: 20px;width:14px"></i></div><input autocomplete="off" placeholder="输入手机号" type="text" rows="2" name="mobile" value="{{ old('mobile') }}" class="el-input__inner"></div>
@if ($errors->has('mobile'))
    <p class="text-danger">{{ $errors->first('mobile') }}</p>
    @endif
    <div style="margin-top: 20px;" class="el-input el-input-group el-input-group--prepend"><div class="el-input-group__prepend"><i class="fa fa-key"></i></div><input autocomplete="off" placeholder="输入您的密码" type="password" rows="2" name="password" class="el-input__inner"></div>
    <el-row style="margin: 20px 0;">
      <el-col :span="12" class="star-check">
        <input type="checkbox"  id="rememberMe" name="remember">
      <label for="rememberMe">
      自动登陆？ <i class="fa fa-unlock-alt"></i>
      </label>
      </el-col>
      <el-col :span="12" style="text-align: right;"><a href="reset">找回密码？</a></el-col>
    </el-row>

<button  class="btn btn-primary btn-block" style="margin-bottom: 20px; font-size: 16px"><i class="fa fa-sign-in"></i> 登录系统</button>
</form>
@endsection
