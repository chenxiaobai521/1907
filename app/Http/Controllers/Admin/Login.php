<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Model\Admin as admin_model;
use Illuminate\Support\Facades\Auth;

class Login extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loginDo()
    {
        $data=request()->except('_token');
        // $where=[
        //     ['admin_name','=',$data['admin_name']],
        //     ['pwd','=',$data['pwd']]
        // ];
        // $user=admin_model::where($where)->first();
        // if($user){
        //     session(['user'=>$user]); 
        //     request()->session()->save();
        //     return redirect('brand');
        // }else{
        //     return redirect('login');
        // }
        if (Auth::attempt($data)) {
            // 认证通过...
            return redirect()->intended('brand');
        }else{
            return redirect()->intended('login');
        }
    }
}
