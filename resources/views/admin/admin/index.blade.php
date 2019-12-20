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
<h3 style="font-size:30px;color:red;margin-left:45%">管理员列表页</h3>
<h4><a href="{{url('admin/create')}}">管理员添加</a></h4>
<form action="{{url('admin')}}">
<input type="text" name="admin_name" value="{{$object['admin_name']??''}}" placeholder="请输入管理员名称">
<input type="submit" value="搜索">
</form>
<table class="table table-hover">
	<thead>
		<tr>
			<th>管理员ID</th>
			<th>管理员名称</th>
            <th>管理员LOGO</th>
            <th>操作</th>
		</tr>
	</thead>
	<tbody>
    @if($data)
    @foreach($data as $v)
		<tr>
			<td>{{$v->admin_id}}</td>
			<td>{{$v->admin_name}}</td>
			<td><img src="{{env('UPLOAD_URL')}}{{$v->admin_logo}}" style="width:50px"></td>
            <td><a href="{{url('admin/edit/'.$v->admin_id)}}" class="btn btn-info">编辑</a>&nbsp;<a href="{{url('admin/destroy/'.$v->admin_id)}}" class="btn btn-danger">删除</a></td>
		</tr>
    @endforeach
    @endif
	</tbody>
</table>
</body>
</html>