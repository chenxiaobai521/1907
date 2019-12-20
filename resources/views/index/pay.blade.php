@extends('layouts.car')
@section('title', '结算')
@section('content')
<style>
.aaa{
  width:90px;
  height:30px;
  text-align:center;
  line-height:30px;
  color:#fff;
  background:#ff701e;
  border-radius:4px;           
}
</style>
<header>
  <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
  <div class="head-mid">
    <h1>购物车</h1>
  </div>
</header>
<div class="head-top">
  <img src="{{ asset('/static/index/images/head.jpg') }}" />
</div><!--head-top/-->
<div class="dingdanlist">
<table>
  @if(!$addressInfo)
  <tr>
    <td class="dingimg" width="75%" colspan="2"><a href="javascript:;" class="bbb" style="text-decoration:none ">新增收货地址</a></td>
    <td align="right"><a href="javascript:;" class="bbb"><img src="{{ asset('/static/index/images/jian-new.png') }}"/></a></td>
  </tr>
  @else
  <tr address_id="{{$addressInfo->address_id}}" class="now">
    <td class="p_td" colspan="2">收货人姓名:{{$addressInfo->name}}</td>
    <td class="p_td">电话:{{$addressInfo->tel}}</td>
  </tr>
  <tr>
    <td class="p_td" colspan="3">详细地址:{{$addressInfo->detail}}</td>
  </tr>
  @endif
  <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
  <tr>
    <td style="width:100px;float:left">支付方式</td>
    <td colspan="2">
      <ul class="pay">
        <li class="checked aaa" pay_type="1" style="margin-left:230px;float:left">支付宝支付</li>
        <li pay_type="2" style="margin-left:10px;float:left">微信支付</li>
        <li pay_type="3" style="margin-left:10px;float:left">银行卡支付</li>
      </ul>
    </td>
  </tr>
  <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
  <tr>
    <td class="dingimg" width="75%" colspan="3">商品清单</td>
  </tr>
  @foreach($goodsInfo as $v)
  <tr goods_id="{{$v->goods_id}}">
    <td class="dingimg" width="10%"><a href="{{url('proinfo/'.$v->goods_id)}}"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" /></a></td>
    <td width="50%">
      <h3><a href="{{url('proinfo/'.$v->goods_id)}}">{{$v->goods_name}}</a></h3>
      <time>下单时间：{{date("Y-m-d H:i:s",$v->add_time)}}</time>
    </td>
    <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
  </tr>
  @endforeach
  <tr>
  <th colspan="3"><strong class="orange">¥{{$v->goods_price*$v->buy_number}}</strong></th>
  </tr>
</table>
</div><!--dingdanlist/-->
</div><!--content/-->
<div class="height1"></div>
<div class="gwcpiao">
<table>
<tr>
  <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
  <td width="50%">总计：<strong class="orange">¥{{$count}}</strong></td>
  <td width="40%"><a href="javascript:;" class="jiesuan">提交订单</a></td>
</tr>
</table>
@endsection
<script src="/static/index/js/jquery.js"></script>
<script>
$(document).ready(function(){
  $(document).on('click','.pay>li',function(){
    $(this).addClass('checked').siblings('li').removeClass('checked');
    $(this).addClass('aaa').siblings('li').removeClass('aaa');
  });
  $(document).on('click','.jiesuan',function(){
    var address_id=$('.now').attr('address_id');
    var _tr=$("tr[goods_id]");
    var goods_id="";
    _tr.each(function (index) {
        goods_id+=$(this).attr('goods_id')+',';
    });
    goods_id=goods_id.substr(0,goods_id.length-1);
    var pay_type=$("li[class='checked aaa']").attr('pay_type');
    location.href="{{url('submitOrder')}}?goods_id="+goods_id+"&pay_type="+pay_type+"&address_id="+address_id;
  })
  $(document).on('click','.bbb',function(){
    location.href="{{url('address')}}";
  })
})
</script>