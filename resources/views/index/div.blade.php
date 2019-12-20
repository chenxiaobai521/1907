<div class="prolist show">
     @foreach($goodsInfo as $v)
     <dl>
          <dt><a href="{{url('proinfo/'.$v->goods_id)}}"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" width="100" height="100" /></a></dt>
          <dd>
               <h3><a href="{{url('proinfo/'.$v->goods_id)}}">{{$v->goods_name}}</a></h3>
               <div class="prolist-price"><strong>¥{{$v->goods_price}}</strong> <span>¥10000</span></div>
               <div class="prolist-yishou"><span>5.0折</span> <em>已售：35</em></div>
          </dd>
          <div class="clearfix"></div>
     </dl>
     @endforeach
</div><!--prolist/-->