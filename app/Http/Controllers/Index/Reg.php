<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User as user_model;

class Reg extends Controller
{
	public function reg(){
        return view('index.reg');
    }
    public function send(){
        $email=request()->email;
        $reg='/^\w+@\w+\.com$/';
        if(empty($email)){
            echo json_encode(['font'=>'邮箱格式必填','code'=>'2']);die;
        }else if(!preg_match($reg,$email)){
            echo json_encode(['font'=>'邮箱格式格式有误','code'=>'2']);die;
        }else{
            $res=user_model::where('email',$email)->first();
            if($res){
                echo json_encode(['font'=>'该邮箱已注册','code'=>'2']);die;
            }
        }
        //随机生成验证码
		$code=rand(100000,999999);
		//发邮件
		$massage="尊敬的用户,您的验证码为".$code.",五分钟内输入有效!";
		$res=$this->sendEmail($email,$massage);
		if(!$res){
			$emailInfo=['email'=>$email,'code'=>$code,'send_time'=>time()];
            session(['emailInfo'=>$emailInfo]);
            echo json_encode(['font'=>'发送成功','code'=>1]);
		}else{
			echo json_encode(['font'=>'发送失败','code'=>2]);die;
		}
    }
    public function sendEmail($email,$massage){
        \Mail::raw($massage ,function($message)use($email){
            //设置主题
            $message->subject("欢迎菜市场");
            //设置接收方
            $message->to($email);
        });
    }
    //注册邮箱验证
	public function regDo(){
        $data=request()->except('_token');
        // dd($data);
		$emailInfo=session('emailInfo');
		$reg='/^\w+@\w+\.com$/';
		if(empty($data['email'])){
			echo '邮箱必填';die;
		}else if(!preg_match($reg,$data['email'])){
			echo '邮箱格式有误';die;
		}else if($emailInfo['email']!=$data['email']){
            echo '发送验证码邮箱与注册邮箱不一致';die;
        }else{
			$count=user_model::where('email',$data['email'])->count();
			if($count>0){			
				echo '邮箱已被注册';die;
			}
		}
		//验证验证码
		 if(empty($data['code'])){
			echo '验证码必填';die;
		 }else if($emailInfo['code']!=$data['code']){
		 	echo '验证码有误';die;
		 }else if((time()-$emailInfo['send_time'])>300){
		 	echo '验证码已失效,五分钟内输入有效';die;
		 }
		//验证密码  数字字母6-12
		$reg='/^[0-9a-z]{6,12}$/';
		if(empty($data['pwd'])){
			echo '密码必填';die;
		}else if(!preg_match($reg,$data['pwd'])){//验证确认密码s
			echo '密码由数字字母下划线组成6-12位,但不能以下划线开头';die;
		}else if($data['pwd']!=$data['pwd2']){
			echo '确认密码和密码不一致';die;
		}
		
		$data['pwd']=encrypt($data['pwd']);
        $data['time']=time();
        unset($data['pwd2']);
		$result=user_model::create($data);
		if($result){
			return redirect('login');
		}else{
			echo "注册失败";
		}
	}
}
