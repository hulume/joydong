@extends('icenter::layouts.mini')

@section('content')
<!-- TAB START -->
<!-- TAB START -->
<el-row class="login-tab">
  <el-col :span="12"><a href="login">用户登录</a></el-col>
  <el-col :span="12"   class="active"><a href="register">注册新用户</a></el-col>
</el-row>
<form action="{{url('/register')}}">
    {{csrf_field()}}
    <register></register>
</form>
@endsection
