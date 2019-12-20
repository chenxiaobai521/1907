@extends('layouts.car')
@section('title', '结算')
@section('content')
<header>
     <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
     <div class="head-mid">
          <h1>支付</h1>
     </div>
</header>
<div class="susstext">订单提交成功</div>
<div class="sussimg">&nbsp;</div>
<div class="dingdanlist">
<table>
     <tr>
          <td width="50%">
               <h3>订单号：{{$data->order_number}}</h3>
               <time>创建日期：{{date("Y-m-d H:i:s",$data->create_time)}}<br/>
                    失效日期：2015-9-12</time>
               <strong class="orange">¥{{$data->order_amount}}</strong>
          </td>
          <td align="right"><span class="orange">等待支付</span></td>
     </tr>
</table>
</div><!--dingdanlist/-->
<div class="succTi orange">请您尽快完成付款，否则订单将被取消</div>
</div><!--content/-->
<div class="height1"></div>
<div class="gwcpiao">
<table>
     <tr>
          <td width="50%"><a href="{{url('prolist')}}" class="jiesuan" style="background:#5ea626;">继续购物</a></td>
          <td width="50%"><a href="{{url('payDo/'.$data->order_id)}}" class="jiesuan">立即支付</a></td>
     </tr>
</table>
</div><!--gwcpiao/-->
@endsection