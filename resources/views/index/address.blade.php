@extends('layouts.layout')
@section('title', '收货地址')
@section('content')
<meta name="csrf-token" content="{{csrf_token()}}">
<header>
  <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
  <div class="head-mid">
    <h1>收货地址</h1>
  </div>
</header>
<div class="head-top"><img src="{{ asset('/static/index/images/head.jpg') }}" /></div>
<form class="reg-login">
  <div class="lrBox">
    <div class="lrList"><input type="text" name="name" placeholder="收货人" /></div>
    <div class="lrList"><input type="text" name="detail" placeholder="详细地址" /></div>
    <div class="lrList">
      <select name="province" class="area">
        <option value="0" selected="selected">--请选择--</option>
        @foreach($provinceInfo as $v)
        <option value="{{$v->id}}">{{$v->name}}</option>
        @endforeach
      </select>
      <select name="city" class="area">
        <option value="" selected="selected">--请选择--</option>
      </select>
      <select name="area" class="area">
        <option value="" selected="selected">--请选择--</option>
      </select>
    </div>
    <div class="lrList"><input type="text" name="tel" placeholder="手机" /></div>
    <div class="lrList2" style="margin-top:30px;"><input type="checkbox" value="1" name="is_default" style="width:20px;height:20px">设为默认</div>
  </div><!--lrBox/-->
  <div class="lrSub">
    <input type="button" value="保存" class="add_b"/>
  </div>
</form><!--reg-login/-->
@endsection
<script src="/static/index/js/jquery.js"></script>
<script>
$(document).ready(function(){
  $(document).on('change','.area',function(){
    var _this=$(this);
    _this.nextAll('select').html("<option value=''>--请选择--</option>");
    var id=_this.val();
    $.ajax({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"{{url('getArea')}}",
        type:'post',
        data:{id:id},
        dataType:'json',
    }).done(function(res){
        var _option="<option value=''>--请选择--</option>";
        for(var i in res){
          _option+="<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
        }
        _this.next('select').html(_option);
    })
  })
  $(document).on('click','.add_b',function(){
    var name=$("input[name='name']").val();
    var detail=$("input[name='detail']").val();
    var province=$("select[name='province']").val();
    var city=$("select[name='city']").val();
    var area=$("select[name='area']").val();
    var tel=$("input[name='tel']").val();
    var is_default=$("input:checked").val();
    $.ajax({
        headers:{
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:"{{url('save')}}",
        type:'post',
        data:{name:name,detail:detail,province:province,city:city,area:area,tel:tel,is_default:is_default},
        dataType:'json',
    }).done(function(res){
      if(res.code==1){
        alert(res.font);
        location.href="{{url('addAddress')}}";
      }else{
        alert(res.font);
      }
    })
  })
})
</script>