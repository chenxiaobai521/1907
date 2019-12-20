@extends('layouts.car')
@section('title', '购物车')
@section('content')
<meta name="csrf-token" content="{{csrf_token()}}">
<header>
     <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
     <div class="head-mid">
          <h1>购物车</h1>
     </div>
</header>
<div class="head-top">
     <img src="{{ asset('/static/index/images/head.jpg') }}" />
</div><!--head-top/-->
<table class="shoucangtab">
     <tr>
          <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
          <td width="25%" align="center" style="background:#fff url(/static/index/images/xian.jpg) left center no-repeat;">
               <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
          </td>
     </tr>
</table>
<div class="dingdanlist">
<table>
     <tr>
          <td width="100%" colspan="5">
               <a href="javascript:;"><input type="checkbox" id="allBox"/>全选</a>
          </td>
     </tr>
     @foreach($cartInfo as $v)
     <tr>
          <td width="4%"><input type="checkbox" class="box" goods_id="{{$v->goods_id}}"/></td>
          <td class="dingimg"><a href="{{url('proinfo/'.$v->goods_id)}}"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" width="100px"/></a></td>
          <td width="50%">
               <h3><a href="{{url('proinfo/'.$v->goods_id)}}">{{$v->goods_name}}</a></h3>
               <time>下单时间：{{date("Y-m-d H:i:s",$v->add_time)}}</time>
          </td>
          <td width="26%">
               <input type="hidden" value="{{$v->goods_num}}" id="goods_num">
               <button class="decrease" id="less" style="width:27px">-</button>
               <input type="text" value="{{$v->buy_number}}" id="buy_number" style="width:50px;border-style:solid;border-color:#ccc" class="spinnerExample value passive" />
               <button class="increase" id="add" style="width:27px">+</button>
          </td>
          <th><strong class="orange" id="count">¥{{$v->goods_price*$v->buy_number}}</strong></th>
     </tr>
     @endforeach
     <tr><td colspan="5"><a href="javascript:;" id="del">删除</a></td></tr>
</table>
</div><!--dingdanlist/-->
<div class="height1"></div>
<div class="gwcpiao">
<table>
     <tr>
          <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
          <td width="50%">总计：<strong class="orange" id="money">¥0</strong></td>
          <td width="40%"><a href="javascript:;" class="jiesuan">去结算</a></td>
     </tr>
</table>
@endsection
<script src="/static/index/js/jquery.js"></script>
<script>
$(document).ready(function(){
    //点击加号
    $(document).on('click','#add',function(){
        var _this=$(this);
        var buy_number=parseInt($("#buy_number").val());
        var goods_id=_this.parents('tr').attr('goods_id');
        var goods_num=parseInt($("#goods_num").val());
        if(buy_number>=goods_num){
            $("#buy_number").val(goods_num);
        }else{
            buy_number=buy_number+1;
            $("#buy_number").val(buy_number);
        }
        //点击选中复选框
        // _this.parents("tr").find(".box").prop('checked',true);
        //更改购买数量
        changeNum(goods_id,buy_number);
        //获取小计
        getTotal(goods_id,_this);
        //获取总价
        getCount();
    });

    //点击减号
    $(document).on('click','#less',function(){
        var _this=$(this);
        var buy_number=parseInt(_this.next('input').val());
        var goods_id=_this.parents('tr').attr('goods_id');
        if(buy_number<=1){
            $("#buy_number").val(1);
        }else{
            buy_number=buy_number-1;
            $("#buy_number").val(buy_number);
        }
        //更改购买数量
        changeNum(goods_id,buy_number);
        //点击选中复选框
        // _this.parents("tr").find(".box").prop('checked',true);
        //获取小计
        getTotal(goods_id,_this);
        //获取总价
        getCount();
    });

    //失去焦点
    $(document).on('blur','#buy_number',function(){
        var _this=$(this);
        var buy_number=_this.val();
        var goods_id=_this.parents('tr').attr('goods_id');
        var goods_num=parseInt($("#goods_num").val());
        var reg=/^\d+$/;
        if(!reg.test(buy_number)||buy_number<=0){
            _this.val(1);
            var buy_number=1;
        }else if(parseInt(buy_number)>=goods_num){
            _this.val(goods_num);
        }else{
            buy_number=parseInt(buy_number);
            _this.val(buy_number);
        }
        //更改购买数量
        changeNum(goods_id,buy_number);
        //点击选中复选框
        // _this.parents("tr").find(".box").prop('checked',true);
        //获取小计
        getTotal(goods_id,_this);
        //获取总价
        getCount();
    })

    //点击复选框
    $(document).on('click','.box',function () {
        var status=$(this).prop('checked');
        //重新获取总价
        getCount();
    })

    //点击全选
    $(document).on('click','#allBox',function () {
        var status=$(this).prop('checked');
        $('.box').prop('checked',status);
        //重新获取总价
        getCount();
    });

    //点击删除
    $(document).on('click','#del',function(){
        var _box=$(".box:checked");//获取选中复选框的商品id
        var goods_id="";//定义一个空字符串
        _box.each(function(index){
            goods_id+=$(this).parents("tr").attr('goods_id')+',';//循环每一个复选框
        })
        goods_id=goods_id.substr(0,goods_id.length-1);
        if(goods_id==''){
            alert('请选择要删除的商品');
        }
        $.ajax({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{url('del')}}",
            type:'post',
            data:{goods_id:goods_id},
            dataType:'json',
        }).done(function(res){
            if(res.code==1){
                _box.each(function(index){
                    _box.parents('tr').remove();
                })
                $("#money").text("￥"+0);
            }else{
                alert(res.font);
            }
        })
    });

    //点击结算
    $(document).on('click','.jiesuan',function(){
        var _box=$(".box:checked");
        if(_box.length>0){
            var goods_id="";
            _box.each(function(index){
                goods_id+=$(this).attr("goods_id")+',';
            })
            goods_id=goods_id.substr(0,goods_id.length-1);
            location.href="{{url('pay')}}?goods_id="+goods_id;
        }else{
            alert("请至少选中一件商品");
        }
    });

    //更改购买数量
    function changeNum(goods_id,buy_number){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{url('changeNum')}}",
            type:'post',
            data:{buy_number:buy_number,goods_id:goods_id},
            async:false,
        }).done(function(res){
            if(res.code==2){
                alert(res.font);
            }
        })
    }

    //获取小计
    function getTotal(goods_id,_this){
        $.ajax({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{url('getTotal')}}",
            type:'post',
            data:{goods_id:goods_id},
            async:false
        }).done(function(res){
            _this.parent('td').next('th').text('￥'+res);
        })
    }

    //获取总价
    function getCount(){
        var _box=$(".box:checked");
        var goods_id="";
        _box.each(function(index){
            goods_id+=$(this).attr('goods_id')+',';
        })
        goods_id=goods_id.substr(0,goods_id.length-1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{url('getCount')}}",
            method:'post',
            data:{goods_id:goods_id},
        }).done(function(res){
            $("#money").text("￥"+res);
        });
    }
})
</script>