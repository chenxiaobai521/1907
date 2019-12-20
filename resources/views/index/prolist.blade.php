@extends('layouts.layout')
@section('title', '全部商品')
@section('content')
<meta name="csrf-token" content="{{csrf_token()}}">
<header>
     <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
     <div class="head-mid">
          <form action="#" method="get" class="prosearch"><input type="text" /></form>
     </div>
</header>
<ul class="pro-select">
     <li class="now pro-selCur" field="is_new"><a href="javascript:;">新品</a></li>
     <li class="now" field="is_best"><a href="javascript:;">精品</a></li>
     <li class="now" field="is_host"><a href="javascript:;">热卖</a></li>
</ul><!--pro-select/-->
<div class="prolist">
     @foreach($goodsInfo as $v)
     <dl goods_id="{{$v->goods_id}}">
          <dt><a href="{{url('proinfo/'.$v->goods_id)}}" class="proinfo"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" width="100" height="100" /></a></dt>
          <dd>
               <h3><a href="{{url('proinfo/'.$v->goods_id)}}" class="proinfo">{{$v->goods_name}}</a></h3>
               <div class="prolist-price"><strong>¥{{$v->goods_price}}</strong> <span>¥10000</span></div>
               <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
          </dd>
          <div class="clearfix"></div>
     </dl>
     @endforeach
</div><!--prolist/-->
@endsection
<script src="/static/index/js/jquery.js"></script>
<script>
$(document).ready(function(){
     $(document).on('click',".now",function(){
          var _this=$(this);
          _this.addClass('pro-selCur').siblings().removeClass('pro-selCur');
          goodsInfo();
     })
     function goodsInfo(){
          var field=$('.now.pro-selCur').attr('field');
          $.ajax({
               headers:{
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:"{{url('goodsInfo')}}",
               method:'post',
               data:{field:field},
          }).done(function(res){
               $(".prolist").html(res);
          });
     }
})
</script>