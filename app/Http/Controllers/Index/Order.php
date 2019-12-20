<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Order as order_model;

class Order extends Controller
{
    //支付
    public function payDo($id){
        $config=config('alipay');
        require_once app_path('lib/alipay/wappay/service/AlipayTradeService.php');
        require_once app_path('lib/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        $user=session('user');
        $user_id=$user['user_id'];
        $where=[
            ['user_id','=',$user_id],
            ['order_id','=',$id]
        ];
        $data=order_model::where($where)->first();
        if (!empty($data->order_number)&& trim($data->order_number)!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填
            $out_trade_no = $data->order_number;

            //订单名称，必填
            $subject = $data->order_name;

            //付款金额，必填
            $total_amount = $data->order_amount;

            //商品描述，可空
            $body = '该商品贼好!';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

            return ;
        }
    }

    //同步跳转
    public function returnUrl(){
        $data=request()->get();
        require_once app_path('lib/alipay/wappay/service/AlipayTradeService.php');
        $config=config('alipay');
        $aop = new \AlipayTradeService($config);
        $res=$aop->check($data);
        if(empty($res)){
            echo '签名有误';exit;
        }
        //验证app_id
        if($data['app_id']!=$config['app_id']){
            echo '应用id不正确';exit;
        }
        //验证订单号
        $out_trade_no=$data['out_trade_no'];
        $where=[
            ['order_number','=',$out_trade_no]
        ];
        $object=order_model::where($where)->find();
        if($out_trade_no!=$object['order_number']){
            echo '订单号不匹配';exit;
        }
        //验证订单金额
        $total_amount=$data['total_amount'];
        if($total_amount!=$object['order_amount']){
            echo '订单金额有误';exit;
        }
    }
    //异步通知
    public function notifyUrl(){
        $data=request()->get();
        $str=var_export($data,true);
        $length=file_put_contents("notify.log",$str,FILE_APPEND);
        if($length>0){
            //验证签名
            require_once app_path('lib/alipay/wappay/service/AlipayTradeService.php');
            $config=config('alipay');
            $aop=new \AlipayTradeService($config);
            $res=$aop->check($data);
            if(empty($res)){
                file_put_contents("notify.log","签名有误\n",FILE_APPEND);exit;
            }
            //验证app_id
            if($data['app_id']!=$config['app_id']){
                file_put_contents("notify.log","应用id不正确\n",FILE_APPEND);exit;
            }
            //验证订单号
            $out_trade_no=$data['out_trade_no'];
            $where=[
                ['order_number','=',$out_trade_no]
            ];
            $object=order_model::where($where)->first();
            if($out_trade_no!=$object['order_number']){
                file_put_contents("notify.log","订单号不匹配\n",FILE_APPEND);exit;
            }
            //验证订单金额
            $total_amount=$data['total_amount'];
            if($total_amount!=$object['order_amount']){
                file_put_contents("notify.log","订单金额不正确\n",FILE_APPEND);exit;
            }
            //修改订单状态 支付状态 支付时间
            $orderWhere=[
                ['order_id','=',$orderInfo['order_id']]
            ];
            $res=order_model::where($orderWhere)->update(['pay_status'=>2,'order_status'=>3,'pay_time'=>time()]);
            if(empty($res)){
                file_put_contents("notify.log","支付失败\n",FILE_APPEND);exit;
            }
            file_put_contents("notify.log","支付成功\n",FILE_APPEND);
            echo "success";
        }
    }
}