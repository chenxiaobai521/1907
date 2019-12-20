<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/static/admin/css/bootstrap.min.css">  
	<script src="/static/admin/jquery.js"></script>
	<script src="/static/admin/js/bootstrap.min.js"></script>
	<meta name="csrf-token" content="{{csrf_token()}}">
</head>
<body>
<h3 style="font-size:30px;color:red;margin-left:45%">商品列表页</h3>
<h4><a href="{{url('goods/create')}}">商品添加</a></h4>
<form action="{{url('goods')}}">
<input type="text" name="goods_name" value="{{$object['goods_name']??''}}" placeholder="请输入商品名称">
<input type="submit" value="搜索">
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>商品ID</th>
			<th>商品名称</th>
			<th>商品价格</th>
			<th>商品积分</th>
            <th>商品LOGO</th>
			<th>商品相册</th>
            <th>商品库存</th>
			<th>是否新品</th>
			<th>是否精品</th>
			<th>是否热卖</th>
			<th>商品品牌</th>
			<th>商品分类</th>
            <th>操作</th>
		</tr>
	</thead>
	<tbody>
    @if($data)
    @foreach($data as $v)
		<tr goods_id="{{$v->goods_id}}">
			<td>{{$v->goods_id}}</td>
			<td>{{$v->goods_name}}</td>
			<td>{{$v->goods_price}}</td>
			<td>{{$v->goods_num}}</td>
			<td><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" style="width:50px"></td>
			<td>
			@foreach($v['goods_imgs'] as $vv)
				<img src="{{env('UPLOAD_URL')}}{{$vv}}" width="50px" height="50px">
			@endforeach
			</td>
			<td>{{$v->goods_num}}</td>
			<td field="is_new">@if($v->is_new==1)<span style="color:green" class="aaa">√</span>@else<sapn style="color:red" class="aaa">×</span>@endif</td>
			<td field="is_best">@if($v->is_best==1)<span style="color:green" class="aaa">√</span>@else<sapn style="color:red" class="aaa">×</span>@endif</td>
			<td field="is_host">@if($v->is_host==1)<span style="color:green" class="aaa">√</span>@else<sapn style="color:red" class="aaa">×</span>@endif</td>
			<td>{{$v->brand_id}}</td>
			<td>{{$v->cate_id}}</td>
            <td><a href="{{url('goods/edit/'.$v->goods_id)}}" class="btn btn-info">编辑</a>&nbsp;<a href="{{url('goods/destroy/'.$v->goods_id)}}" class="btn btn-danger">删除</a></td>
		</tr>
    @endforeach
    @endif
	</tbody>
	<tr><td colspan="13">{{$data->appends($object)->links()}}</td></tr>
</table>
</body>
</html>
<script>
$(document).ready(function(){
	$(document).on('click','.aaa',function(){
		var _this=$(this);
		var bbb=_this.text();
		if(bbb=='√'){
			var bbb="×";
			var value='2';
		}else{
			var bbb="√";
			var value='1';
		}
		var goods_id=_this.parents('tr').attr('goods_id');
		var field=_this.parent('td').attr('field');
		$.ajaxSetup({
			headers:{
				'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')     
			} 
		}); 
		$.ajax({
			type:'post',
			url:"goods/change",
			data:{value:value,goods_id:goods_id,field:field},
		}).done(function(res){
			if(res){
				if(value=='1'){
					_this.html('<span style="color:green" class="aaa">'+bbb+'</span>');
				}else{
					_this.html('<span style="color:red" class="aaa">'+bbb+'</span>');
				}
			}
		});
	})
})
</script>