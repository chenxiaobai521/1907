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
<h3 style="font-size:30px;color:red;margin-left:45%">后台登录页</h3>
<form class="form-horizontal" role="form" style="margin-left:170px;margin-top:100px" method="post" action="{{url('login/loginDo')}}">
@csrf
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" placeholder="请输入用户名" style="width:500px">
            <b style="color:red">{{$errors->first('name')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password" placeholder="请输入密码" style="width:500px">
            <b style="color:red">{{$errors->first('password')}}</b>
        </div>
    </div>
    <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10" style="margin-left:350px">
			<button type="submit" class="btn btn-default" style="width:100px">登录</button>
		</div>
	</div>
</form>
</body>
</html>