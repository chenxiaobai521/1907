<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods as goods_model;
use App\Model\Category as cate_model;
use App\Model\Cart as cart_model;
use App\Model\Address as address_model;
use App\Model\Order as order_model;
use App\Model\OrderAddress as orderAddress_model;
use App\Model\OrderGoods as orderGoods_model;
use App\Model\Area as area_model;
use DB;

class Index extends Controller
{
    public function user(){
        return view('index.user');
    }

    //首页
    public function index(){
        $goodsInfo=goods_model::get();
        $cateInfo=cate_model::where('parent_id',0)->get();
        return view('index.index',['goodsInfo'=>$goodsInfo,'cateInfo'=>$cateInfo]);
    }

    //全部商品
    public function prolist(){
        $goodsInfo=goods_model::where('is_new',1)->get();
        return view('index.prolist',['goodsInfo'=>$goodsInfo]);
    }
    public function goodsInfo(){
        $field=request()->field;
        $where[]=[$field,'=',1];
        $goodsInfo=goods_model::where($where)->get();
        return view('index.div',['goodsInfo'=>$goodsInfo]);
    }

    //商品详情页
    public function proinfo($id){
        $goodsInfo=goods_model::find($id);
        $goodsInfo['goods_imgs']=explode('|',$goodsInfo['goods_imgs']);
        return view('index.proinfo',['goodsInfo'=>$goodsInfo]);
    }

    //加入购物车
    public function addCart(){
        $data=request()->except('_token');
        $data['add_time']=time();
        $user=session('user');
        $user_id=$user['user_id'];
        $data['user_id']=$user_id;
        $where=[
            ['user_id','=',$user_id],
            ['goods_id','=',$data['goods_id']],
            ['is_del','=',1]
        ];
        $count=cart_model::where($where)->first();
        if(!empty($count)){
            //检查库存
            $result=$this->checkGoodsNum($data['goods_id'],$data['buy_number'],$count['buy_number']);
            if(empty($result)){
                echo json_encode(['font'=>'此商品已超过库存','code'=>2]);exit;
            }
            //累加
            $res=cart_model::where($where)->update(['buy_number'=>$count['buy_number']+$data['buy_number'],'add_time'=>time()]);
        }else{
            //检查库存
            $result=$this->checkGoodsNum($data['goods_id'],$data['buy_number']);
            if(empty($result)){
                echo json_encode(['font'=>'此商品已超过库存','code'=>2]);exit;
            }
            //添加
            $res=cart_model::create($data);
        }
        if($res){
            echo json_encode(['font'=>'添加成功','code'=>'1']);
        }else{
            echo json_encode(['font'=>'请重新添加','code'=>'2']);
        }
    }

    //检测库存
    public function checkGoodsNum($goods_id,$buy_number,$already_num=0){
        $goods_num=goods_model::where('goods_id',$goods_id)->value('goods_num');
        if(($buy_number+$already_num)<=$goods_num){
            return true;
        }else{
            return false;
        }
    }

    //购物车列表
    public function car(){
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        $count=cart_model::where($where)->count();
        $cartInfo=cart_model::join("goods", "goods.goods_id", "=", "cart.goods_id")
                    ->where($where)
                    ->orderBy('add_time', 'desc')
                    ->get();
        return view('index.car',['cartInfo'=>$cartInfo,'count'=>$count]);
    }

    //修改购买数量
    public function changeNum(){
        $data=request()->except('_token');
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['goods_id','=',$data['goods_id']],
            ['is_del','=',1]
        ];
        $res=cart_model::where($where)->update(['buy_number'=>$data['buy_number']]);
        if($res){
            echo json_encode(['font'=>'','code'=>'1']);
        }else{
            echo json_encode(['font'=>'','code'=>'2']);
        }
    }

    //获取小计
    public function getTotal(){
        $goods_id=request()->goods_id;
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['cart.goods_id','=',$goods_id],
            ['is_del','=',1]
        ];
        $data=cart_model::join('goods','cart.goods_id','=','goods.goods_id')
                    ->select('buy_number','goods_price')
                    ->where($where)
                    ->first();
       return $data['goods_price']*$data['buy_number'];
    }

    //获取总价
    public function getCount(){
        $goods_id=request()->goods_id;
        $goods_id=explode(',', $goods_id);
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        $data=cart_model::join('goods','cart.goods_id','=','goods.goods_id')
                ->whereIn('goods.goods_id',$goods_id)
                ->where($where)
                ->get();
        $money=0;
        foreach($data as $v){
            $money+=$v['goods_price']*$v['buy_number'];
        }
        return $money;
    }

    //单删或批删
    public function del(){
        $goods_id=request()->goods_id;
        $goods_id=explode(',',$goods_id);
        if(empty($goods_id)){
            echo "请选择要删除的商品";
        }
        $user=session('user');
        $user_id=$user['user_id'];
        $res=cart_model::where('user_id','=',$user_id)->whereIn('goods_id',$goods_id)->update(['is_del'=>2]);
        if($res){
            echo json_encode(['font'=>'','code'=>'1']);
        }else{
            echo json_encode(['font'=>'删除失败,请重试','code'=>'2']);
        }
    }

    //结算
    public function pay(){
        $goods_id=request()->goods_id;
        $goods_id=explode(',', $goods_id);
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['is_del','=',1]
        ];
        $goodsInfo=cart_model::join('goods','cart.goods_id','=','goods.goods_id')
                    ->whereIn('cart.goods_id', $goods_id)
                    ->where($where)
                    ->get();
        $count=0;
        foreach($goodsInfo as $v){
            $count+=$v['buy_number']*$v['goods_price'];
        }
        $addressWhere=[
            ['user_id','=',$user_id],
            ['is_default','=',1],
            ['is_del','=',1]
        ];
        $addressInfo=address_model::where($addressWhere)->first();
        return view('index.pay',['goodsInfo'=>$goodsInfo,'count'=>$count,'addressInfo'=>$addressInfo]);
    }

    //提交订单
    public function submitOrder(){
        $data=request()->except('_token');
        DB::beginTransaction();
        try{
            if(empty($data['goods_id'])){
                throw new \Exception('请勿非法操作');
            }
            $goods_id=explode(',',$data['goods_id']);
            $user=session('user');
            $user_id=$user['user_id'];
            $where=[
                ['user_id','=',$user_id],
                ['is_del','=',1]
            ];
            $goodsInfo=cart_model::select('goods.goods_id','goods_name','goods_img','goods_price','goods_score','buy_number')
                        ->join('goods','goods.goods_id','=','cart.goods_id')
                        ->whereIn('goods.goods_id',$goods_id)
                        ->where($where)
                        ->get();
            if(empty($goodsInfo[0])){
                throw new \Exception('请勿非法操作');
            }
            $order_amount=0;
            $order_score=0;
            foreach($goodsInfo as $v){
                $order_amount+=$v['goods_price']*$v['buy_number'];
                $order_score+=$v['goods_score']*$v['buy_number'];
            }
            $orderInfo['order_number']=time().rand(10000,99999);
            $orderInfo['user_id']=$user_id;
            $orderInfo['order_amount']=$order_amount;
            $orderInfo['order_score']=$order_score;
            $orderInfo['pay_type']=$data['pay_type'];
            $orderInfo['create_time']=time();
            $order_id=order_model::insertGetId($orderInfo);
            if(empty($order_id)){
                throw new \Exception('订单表数据添加失败');
            }
            foreach($goodsInfo as $k=>$v){
                $goodsInfo[$k]['user_id']=$user_id;
                $goodsInfo[$k]['order_id']=$order_id;
                $goodsInfo[$k]['create_time']=time();
            }
            $goodsInfo=$goodsInfo->toArray();
            $res=orderGoods_model::insert($goodsInfo);
            if(empty($res)){
                throw new \Exception('订单商品表数据添加失败');
            }
            $AddressWhere=[
                ['is_del','=',1],
                ['address_id','=',$data['address_id']]
            ];
            $addressInfo=address_model::where($AddressWhere)->select('name','province','city','area','tel','detail','user_id')->first();
            $addressInfo=$addressInfo->toArray();
            $addressInfo['order_id']=$order_id;
            $res1=orderAddress_model::create($addressInfo);
            if(empty($res1)){
                throw new \Exception('订单地址表数据添加失败');
            }
            $res2=cart_model::whereIn('goods_id',$goods_id)->where($where)->update(['is_del'=>2]);
            if(empty($res)){
                throw new \Exception('清除购物车数据失败');
            }
            $goods_num=goods_model::whereIn('goods_id',$goods_id)->value('goods_num');
            $buy_number=cart_model::whereIn('goods_id',$goods_id)->value('buy_number');
            $res3=goods_model::whereIn('goods_id',$goods_id)->update(['goods_num'=>$goods_num-$buy_number]);
            if(empty($res3)){
                throw new \Exception('修改商品库存失败');
            }
            DB::commit();
            echo "<script>location.href='".url('success')."?order_id=$order_id';</script>";
        }catch(\Exception $e){
            DB::rollBack();//回滚
            return $e->getMessage();
        }
    }

    //提交订单成功
    public function success(){
        $order_id=request()->order_id;
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['order_id','=',$order_id]
        ];
        $data=order_model::where($where)->first();
        if(empty($data['order_id'])){
            echo '该订单不存在';die;
        }
        return view('index.success',['data'=>$data]);
    }

    //收货地址
    public function address(){
        $provinceInfo=$this->getAreaInfo(0);
        return view('index.address',['provinceInfo'=>$provinceInfo]);
    }
    //获取地区信息
    public function getAreaInfo($pid){
        $where=[
            ['pid','=',$pid]
        ];
        return area_model::where($where)->get();
    }
    public function getArea(){
        $id=request()->id;
        if(!empty($id)){
            $info=$this->getAreaInfo($id);
            echo json_encode($info);
        }
    }

    //添加收货地址
    public function save(){
        $data=request()->all();
        $user=session('user');
        $user_id=$user['user_id'];
        $data['user_id']=$user_id;
        if(!empty($data['is_default'])){
            $where=[
                ['user_id','=',$user_id],
                ['is_del','=',1]
            ];
            address_model::where($where)->update(['is_default'=>2]);  
        }
        $res=address_model::create($data);
        if($res){
            echo json_encode(['font'=>'添加成功','code'=>1]);
        }else{
            echo json_encode(['font'=>'添加失败','code'=>2]);
        }
    }

    //收货地址列表
    public function addAddress(){
        $data=address_model::get();
        return view('index.addAddress',['data'=>$data]);
    }
}