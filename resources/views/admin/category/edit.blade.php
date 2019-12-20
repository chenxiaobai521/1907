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
<form class="form-horizontal" role="form" method="post" action="{{url('category/update/'.$arr->cate_id)}}">
@csrf
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">分类名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="cate_name" value="{{$arr->cate_name}}" placeholder="请输入分类名称" style="width:500px">
            <b style="color:red">{{$errors->first('cate_name')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">是否显示</label>
        <div class="col-sm-10">
        @if($arr->asd==1)
            <input type="radio" name="asd" checked value="1">是
            <input type="radio" name="asd" value="2">否
        @elseif($arr->asd==2)
            <input type="radio" name="asd" value="1">是
            <input type="radio" name="asd" checked value="2">否
        @endif
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">是否在状态栏显示</label>
        <div class="col-sm-10">
        @if($arr->cate_show==1)
            <input type="radio" name="cate_show" checked value="1">是
            <input type="radio" name="cate_show" value="2">否
        @elseif($arr->cate_show==2)
            <input type="radio" name="cate_show" value="1">是
            <input type="radio" name="cate_show" checked value="2">否
        @endif
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">父类</label>
        <div class="col-sm-10">
            <select name="parent_id">
                <option value="0">--请选择--</option>
                @foreach($data as $v)
                    <option value="{{$v->cate_id}}" @if($v->cate_id==$arr['parent_id']) selected @endif>{{str_repeat('|-',$v['level']-1)}}{{$v->cate_name}}</option>
                @endforeach
            </select>
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