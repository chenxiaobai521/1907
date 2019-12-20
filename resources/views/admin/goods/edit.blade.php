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
<form class="form-horizontal" role="form" action="{{url('goods/update/'.$data->goods_id)}}" method="post" enctype="multipart/form-data">
@csrf
	<div class="form-group">
		<label for="firstname" class="col-sm-2 control-label">商品名称</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="goods_name" id="goods_name" value="{{$data->goods_name}}" placeholder="请输入名字">
		    <b style="color:red">{{$errors->first('goods_name')}}</b>
		</div>
	</div>
	<div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">商品价格</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="goods_price" id="goods_price" value="{{$data->goods_price}}" splaceholder="请输入商品价格">
			<b style="color:red">{{$errors->first('goods_price')}}</b>
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">商品积分</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="goods_score" id="goods_score" value="{{$data->goods_score}}" placeholder="请输入商品积分">
			<b style="color:red">{{$errors->first('goods_score')}}</b>
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">商品LOGO</label>
		<div class="col-sm-10">
            <img src="{{env('UPLOAD_URL')}}{{$data->goods_img}}" style="width:50px">
			<input type="file" class="form-control" name="goods_img">
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">商品相册</label>
		<div class="col-sm-10">
            @foreach($data['goods_imgs'] as $v)
                <img src="{{env('UPLOAD_URL')}}{{$v}}" style="width:50px">
            @endforeach
			<input type="file" class="form-control" name="goods_imgs[]">
            <input type="file" class="form-control" name="goods_imgs[]">
            <input type="file" class="form-control" name="goods_imgs[]">
		</div>
	</div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">商品库存</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="goods_num" id="goods_num" value="{{$data->goods_num}}" placeholder="请输入商品库存">
			<b style="color:red">{{$errors->first('goods_num')}}</b>
		</div>
	</div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">是否新品</label>
        <div class="col-sm-10">
            @if($data->is_new==1)
                <input type="radio" name="is_new" id="is_new" checked value="1">是
                <input type="radio" name="is_new" id="is_new" value="2">否
            @elseif($data->is_new==2)
                <input type="radio" name="is_new" id="is_new" value="1">是
                <input type="radio" name="is_new" id="is_new" checked value="2">否
            @endif
            <b style="color:red">{{$errors->first('is_new')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">是否精品</label>
        <div class="col-sm-10">
            @if($data->is_best==1)
                <input type="radio" name="is_best" id="is_best" checked value="1">是
                <input type="radio" name="is_best" id="is_best" value="2">否
            @elseif($data->is_best==2)
                <input type="radio" name="is_best" id="is_best" value="1">是
                <input type="radio" name="is_best" id="is_best" checked value="2">否
            @endif
            <b style="color:red">{{$errors->first('is_best')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">是否热卖</label>
        <div class="col-sm-10">
            @if($data->is_host==1)
                <input type="radio" name="is_host" id="is_host" checked value="1">是
                <input type="radio" name="is_host" id="is_host" value="2">否
            @elseif($data->is_host==2)
                <input type="radio" name="is_host" id="is_host" value="1">是
                <input type="radio" name="is_host" id="is_host" checked value="2">否
            @endif
            <b style="color:red">{{$errors->first('is_host')}}</b>
        </div>
    </div>
	<div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">商品品牌</label>
        <div class="col-sm-10">
            <select name="brand_id" id="brand_id">
                <option value="0">--请选择--</option>
                @foreach($data1 as $v)
                    <option value="{{$v->brand_id}}" @if($v->brand_id==$data['brand_id']) selected @endif>{{$v->brand_name}}</option>
                @endforeach
            </select>
            <b style="color:red">{{$errors->first('brand_id')}}</b>
        </div>
    </div>
    <div class="form-group">
        <label for="firstname" class="col-sm-2 control-label">商品分类</label>
        <div class="col-sm-10">
            <select name="cate_id" id="cate_id">
                <option value="0">--请选择--</option>
                @foreach($data2 as $v)
                    <option value="{{$v->cate_id}}" @if($v->cate_id==$data['cate_id']) selected @endif>{{str_repeat('|-',$v['level']-1)}}{{$v->cate_name}}</option>
                @endforeach
            </select>
            <b style="color:red">{{$errors->first('cate_id')}}</b>
        </div>
    </div>
    <div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">商品介绍</label>
		<div class="col-sm-10">
			<textarea class="form-control" name="goods_desc" placeholder="请输入商品介绍">{{$data->goods_desc}}</textarea>
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
<script>
$('#goods_name').blur(function(){
	$('#goods_name').next().text('');
	var goods_name=$("#goods_name").val();
    var goods_id={{$data->goods_id}};
	var reg=/^[\u4e00-\u9fa5\w]+$/;
	if(!reg.test(goods_name)){
		$('#goods_name').next().text('商品名称需由中文字母数字下划线组成');
	}else{
		$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')     
		} 
		}); 
		$.ajax({
			type:'post',
			url:"/goods/wy",
			data:{goods_name:goods_name,goods_id:goods_id},
		}).done(function(res){
			if(res==1){
				$('#goods_name').next().text('商品名称已存在');
			}else{
				$('#goods_name').next().text('');
			}
		});
	}
})
$('.btn').click(function(){
	var a=checkPrice();
	var b=checkScore();
	var c=checkNum();
    var d=checkBrand();
    var e=checkCate();
    var f=checkNew();
    var g=checkBest();
    var h=checkHost();

	var falg=false;
	$('#goods_name').next().text('');
	var goods_name=$("#goods_name").val();
    var goods_id={{$data->goods_id}};
	var reg=/^[\u4e00-\u9fa5\w]+$/;
	if(!reg.test(goods_name)){
		$('#goods_name').next().text('商品名称需由中文字母数字下划线组成');
		falg=false;
	}else{
		$.ajaxSetup({
		headers:{
			'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')     
		} 
		}); 
		$.ajax({
			type:'post',
			url:"/goods/wy",
			data:{goods_name:goods_name,goods_id:goods_id},
			async:false,
		}).done(function(res){
			if(res==1){
				$('#goods_name').next().text('商品名称已存在1');
				falg=false;
			}else{
				$('#goods_name').next().text('');
				falg=true;
			}
		});
	}
	if(a&&b&&c&&d&&e&&f&&g&&h&&falg){
		$(".form-horizontal").submit();
	}else{
		return false;
	}
})
function checkPrice(){
	var goods_price=$("#goods_price").val();
    var reg=/^[1-9]{1,}|0$/;
	if(!reg.test(goods_price)){
        $('#goods_price').next().text('商品价格需为正数');
		return false;
	}else{
        $('#goods_price').next().text('');
		return true;
	}
}
function checkScore(){
	var goods_score=$("#goods_score").val();
    var reg=/^\d{1,}$/;
	if(!reg.test(goods_score)){
        $('#goods_score').next().text('商品积分需为正整数');
		return false;
	}else{
        $('#goods_score').next().text('');
		return true;
	}
}
function checkNum(){
	var goods_num=$("#goods_num").val();
    var reg=/^\d{1,}$/;
	if(!reg.test(goods_num)){
        $('#goods_num').next().text('商品库存需为正整数');
		return false;
	}else{
        $('#goods_num').next().text('');
		return true;
	}
}
function checkBrand(){
	var brand_id=$("#brand_id").val();
	if(brand_id==''){
		return false;
	}else{
		return true;
	}
}
function checkCate(){
	var cate_id=$("#cate_id").val();
	if(cate_id==''){
		return false;
	}else{
		return true;
	}
}
function checkNew(){
	var is_new=$("#is_new:checked").length;
	if(is_new<1){
		return false;
	}else{
		return true;
	}
}
function checkBest(){
	var is_best=$("#is_best:checked").length;
	if(is_best<1){
		return false;
	}else{
		return true;
	}
}
function checkHost(){
	var is_host=$("#is_host:checked").length;
	if(is_host<1){
		return false;
	}else{
		return true;
	}
}
</script>