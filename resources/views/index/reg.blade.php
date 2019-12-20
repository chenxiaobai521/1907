@extends('layouts.layout')
@section('title', '注册')
@section('content')
<meta name="csrf-token" content="{{csrf_token()}}">
<header>
     <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
     <div class="head-mid">
          <h1>会员注册</h1>
     </div>
</header>
<div class="head-top">
     <img src="{{ asset('/static/index/images/head.jpg') }}" />
</div><!--head-top/-->
<form action="{{url('regDo')}}" method="post" class="reg-login">
     @csrf
     <h3>已经有账号了？点此<a class="orange" href="{{url('login')}}">登陆</a></h3>
     <div class="lrBox">
     <div class="lrList"><input type="text" name="email" placeholder="输入手机号码或者邮箱号" /></div>
     <div class="lrList2">
          <input type="text" name="code" placeholder="输入短信验证码" /> 
          <a id="checkCode" style="line-height:45px">获取验证码</a>
     </div>
     <div class="lrList"><input type="text" name="pwd" placeholder="设置新密码（6-18位数字或字母）" /></div>
     <div class="lrList"><input type="text" name="pwd2" placeholder="再次输入密码" /></div>
     </div><!--lrBox/-->
     <div class="lrSub">
          <input type="submit" value="立即注册" />
     </div>
</form><!--reg-login/-->
@endsection
<script src="/static/index/js/jquery.js"></script>
<script>
$(document).ready(function(){
     $(document).on('click','#checkCode',function(){
          var email=$("div[class='lrList']").children().val();
          // console.log(email);
          var reg=/^\w+@\w+\.com$/;
          if(email==''){
               alert('邮箱不能为空');
               return false;
          }else if(!reg.test(email)){
               alert('邮箱格式有误');
               return false;
          }
          $.ajax({
               headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:"{{url('send')}}",
               method:'post',
               data:{email:email},
               dataType:'json'
          }).done(function(res){
               if(res.code==1){
                    alert(res.font);
               }else{
                    alert(res.font);
               }
          });
          return false;
     })
})
</script>