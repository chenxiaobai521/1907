<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User as user_model;

class Login extends Controller
{
    public function login(){
        return view('index.login');
    }
    public function loginDo(){
        $data=request()->except('_token');
        if(!empty($data)){
            $validator = \Validator::make($data, [
                'email' => 'required',
                'pwd' => 'required',
            ], [
                'email.required' => '请输入邮箱或手机号',

                'pwd.required' => '请输入密码'
            ]);
            if ($validator->fails()) {
                return redirect('login')
                    ->withErrors($validator)
                    ->withInput();
            }
            $where=[
                ['email','=',$data['email']],
            ];
            $user=user_model::where($where)->first();
            if($user){
                if(decrypt($user['pwd'])==$data['pwd']){
                    session(['user'=>$user]);
                    return redirect('/');
                }else{
                    return redirect('login');
                }
            }
        }
    }
}
