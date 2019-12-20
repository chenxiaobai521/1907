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
<form class="form-horizontal" role="form" action="{{url('admin/store')}}" method="post" enctype="multipart/form-data">
@csrf
	<div class="form-group">
		<label for="firstname" class="col-sm-2 control-label">管理员名称</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="admin_name" placeholder="请输入管理员名称">
		    <b style="color:red">{{$errors->first('admin_name')}}</b>
		</div>
	</div>
    <div class="form-group">
		<label for="firstname" class="col-sm-2 control-label">密码</label>
		<div class="col-sm-10">
			<input type="password" class="form-control" name="pwd" placeholder="请输入管理员密码">
		    <b style="color:red">{{$errors->first('pwd')}}</b>
		</div>
	</div>
	<div class="form-group">
		<label for="firstname" class="col-sm-2 control-label">确认密码</label>
		<div class="col-sm-10">
			<input type="password" class="form-control" name="pwd_confirmation" placeholder="请再次输入管理员密码">
		    <b style="color:red">{{$errors->first('pwd_confirmation')}}</b>
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">管理员LOGO</label>
		<div class="col-sm-10">
			<input type="file" class="form-control" name="admin_logo">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">添加</button>
		</div>
	</div>
</form>
</body>
</html>