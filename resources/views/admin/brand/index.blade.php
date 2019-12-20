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
</head>
<body>
<h3 style="font-size:30px;color:red;margin-left:45%">品牌列表页</h3>
<h4><a href="{{url('brand/create')}}">品牌添加</a></h4>
<form action="{{url('brand')}}">
<input type="text" name="brand_name" value="{{$object['brand_name']??''}}" placeholder="请输入品牌名称">
<input type="text" name="brand_url" value="{{$object['brand_url']??''}}" placeholder="请输入品牌网址">
<input type="submit" value="搜索">
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>品牌ID</th>
			<th>品牌名称</th>
			<th>品牌网址</th>
            <th>品牌LOGO</th>
            <th>品牌介绍</th>
            <th>操作</th>
		</tr>
	</thead>
	<tbody>
    @if($data)
    @foreach($data as $v)
		<tr>
			<td>{{$v->brand_id}}</td>
			<td>{{$v->brand_name}}</td>
			<td>{{$v->brand_url}}</td>
			<td><img src="{{env('UPLOAD_URL')}}{{$v->brand_logo}}" style="width:50px"></td>
			<td>{{$v->brand_desc}}</td>
            <td><a href="{{url('brand/edit/'.$v->brand_id)}}" class="btn btn-info">编辑</a>&nbsp;<a href="{{url('brand/destroy/'.$v->brand_id)}}" class="btn btn-danger">删除</a></td>
		</tr>
    @endforeach
    @endif
	</tbody>
	<tr><td colspan="6">{{$data->appends($object)->links()}}</td></tr>
</table>
</body>
</html>