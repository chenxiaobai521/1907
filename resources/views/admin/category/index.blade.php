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
<h3 style="font-size:30px;color:red;margin-left:45%">分类列表页</h3>
<h4><a href="{{url('category/create')}}">分类添加</a></h4>
<form action="{{url('category')}}">
<input type="text" name="cate_name" value="{{$object['cate_name']??''}}" placeholder="请输入分类名称">
<input type="submit" value="搜索">
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>分类ID</th>
			<th>分类名称</th>
			<th>是否显示</th>
            <th>是否在导航栏显示</th>
			<th>父级</th>
            <th>操作</th>
		</tr>
	</thead>
	<tbody>
    @if($data)
    @foreach($data as $v)
		<tr cate_id="{{$v->cate_id}}">
			<td>{{$v->cate_id}}</td>
			<td>{{$v->cate_name}}</td>
			<td>@if($v->asd==1)<span style="color:green" class="asd">√</span>@else<sapn style="color:red" class="asd">×</span>@endif</td>
			<td>@if($v->cate_show==1)<span style="color:green" class="cate_show">√</span>@else<sapn style="color:red" class="cate_show">×</span>@endif</td>
			<td>{{$v->parent_id}}</td>
            <td><a href="{{url('category/edit/'.$v->cate_id)}}" class="btn btn-info">编辑</a>&nbsp;<a href="{{url('category/destroy/'.$v->cate_id)}}" class="btn btn-danger">删除</a></td>
		</tr>
    @endforeach
    @endif
	</tbody>
</table>
</body>
</html>
<script>
$(document).ready(function(){
	$(document).on('click','.asd',function(){
		var _this=$(this);
		var asd=_this.text();
		if(asd=='√'){
			var asd="×";
			var value='2';
		}else{
			var asd="√";
			var value='1';
		}
		var cate_id=_this.parents('tr').attr('cate_id');
		$.ajaxSetup({
			headers:{         
				'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')     
			} 
		}); 
		$.ajax({
			type:'post',
			url:"category/changeAsd",
			data:{value:value,cate_id:cate_id},
		}).done(function(res){
			if(res){
				if(value=='1'){
					_this.html('<span style="color:green" class="asd">'+asd+'</span>');
				}else{
					_this.html('<span style="color:red" class="asd">'+asd+'</span>');
				}
			}
		});
	})
	$(document).on('click','.cate_show',function(){
		var _this=$(this);
		var cate_show=_this.text();
		if(cate_show=='√'){
			var cate_show="×";
			var value='2';
		}else{
			var cate_show="√";
			var value='1';
		}
		var cate_id=_this.parents('tr').attr('cate_id');
		$.ajaxSetup({
			headers:{         
				'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')     
			} 
		}); 
		$.ajax({
			type:'post',
			url:"category/changeCateShow",
			data:{value:value,cate_id:cate_id},
		}).done(function(res){
			if(res){
				if(value=='1'){
					_this.html('<span style="color:green" class="cate_show">'+cate_show+'</span>');
				}else{
					_this.html('<span style="color:red" class="cate_show">'+cate_show+'</span>');
				}
			}
		});
	})
})
</script>