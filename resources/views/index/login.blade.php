@extends('layouts.layout')
@section('title', '登录')
@section('content')
<header>
     <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
     <div class="head-mid">
     <h1>登录</h1>
     </div>
</header>
<div class="head-top">
     <img src="{{ asset('/static/index/images/head.jpg') }}" />
</div><!--head-top/-->
<form action="{{url('loginDo')}}" method="post" class="reg-login">
@csrf
     <h3>还没有三级分销账号？点此<a class="orange" href="{{url('reg')}}">注册</a></h3>
     <div class="lrBox">
     <div class="lrList">
          <input type="text" name="email" id="email" placeholder="输入手机号码或者邮箱号" />
          <b style="color:red">{{$errors->first('email')}}</b>
     </div>
     <div class="lrList">
          <input type="text" name="pwd" id="pwd" placeholder="输入密码" />
          <b style="color:red">{{$errors->first('pwd')}}</b>
     </div>
     </div><!--lrBox/-->
     <div class="lrSub">
          <input type="submit" value="立即登录" />
     </div>
</form><!--reg-login/-->
@endsection
<script src="/static/index/js/jquery.js"></script>
<script>
$(document).ready(function(){
     $(document).on('click','.lrSub',function(){
          var a=checkEmail();
          var b=checkPwd();
          if(a&&b){
               $(".reg-login").submit();
          }else{
               return false;
          }
     })
     function checkEmail(){
          var email=$('#email').val();
          if(email==''){
               $('#email').next().text('请输入邮箱或手机号');
               return false;
          }else{
               $('#email').next().text('');
               return true;
          }
     }
     function checkPwd(){
          var pwd=$('#pwd').val();
          if(pwd==''){
               $('#pwd').next().text('请输入邮箱或手机号');
               return false;
          }else{
               $('#pwd').next().text('');
               return true;
          }
     }
})
</script>