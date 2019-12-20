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
<form class="form-horizontal" role="form" action="{{url('brand/update/'.$data->brand_id)}}" method="post" enctype="multipart/form-data">
@csrf
	<div class="form-group">
		<label for="firstname" class="col-sm-2 control-label">品牌名称</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="brand_name" value="{{$data->brand_name}}" placeholder="请输入名字">
			<b style="color:red">{{$errors->first('brand_name')}}</b>
		</div>
	</div>
	<div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">品牌URL</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="brand_url" value="{{$data->brand_url}}" placeholder="请输入品牌URL">
			<b style="color:red">{{$errors->first('brand_url')}}</b>
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">品牌LOGO</label>
		<div class="col-sm-10">
			<img src="{{env('UPLOAD_URL')}}{{$data->brand_logo}}" style="width:50px">
			<input type="file" class="form-control" name="brand_logo">
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">品牌介绍</label>
		<div class="col-sm-10">
			<textarea class="form-control" name="brand_desc" placeholder="请输入品牌详情">{{$data->brand_desc}}</textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">修改</button>
		</div>
	</div>
</form>
</body>
</html>