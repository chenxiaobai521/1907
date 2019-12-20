<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Admin as admin_model;
use Illuminate\Validation\Rule; 
use Validator;

class Admin extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin_name=request()->admin_name;
        $where=[];
        if(!empty($admin_name)){
            $where[]=['admin_name','like',"%$admin_name%"];
        }
        $object=request()->all();
        $data=admin_model::where($where)->get();
        return view('admin.admin.index',['data'=>$data,'object'=>$object]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->except('_token');
        $validator=Validator::make($data,[
            'admin_name' => 'required|unique:admin|max:6|min:1',
            'pwd' => 'required|max:15|min:6|confirmed',
            'pwd_confirmation' => 'same:pwd',
        ],[
            'admin_name.required'=>'管理员名称必填',
            'admin_name.unique'=>'管理员名称已存在',
            'admin_name.max'=>'管理员名称不能大于6位',
            'admin_name.min'=>'管理员名称不能小于1位',
            'pwd.required'=>'管理员密码必填',
            'pwd.max'=>'管理员密码不能大于15位',
            'pwd.min'=>'管理员密码不能小于6位',
            'pwd.confirmed'=>'',
            'pwd_confirmation.same'=>'两次密码不一致',
        ]
        ); 
        if($validator->fails()){
            return redirect('admin/create')
                    ->withErrors($validator)
                    ->withInput();
        } 
        if($request->hasFile('admin_logo')){
            $data['admin_logo']=$this->upload('admin_logo');
        }
        $data['pwd']=md5($data['pwd']);
        unset($data['pwd_confirmation']);
        $res=admin_model::create($data);
        if($res){
            return redirect('admin');
        }else{
            return redirect('admin.create');
        }
    }

    /**
     *文件上传
    */
    public function upload($image){
        if(request()->file($image)->isValid()) {
            $photo=request()->file($image);                 
            $store_result=$photo->store('uploads'); 
            return $store_result;     
        } 
        exit('未获取到上传文件或上传过程出错'); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=admin_model::where('admin_id',$id)->first();
        return view('admin.admin.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data=$request->except('_token');
        $validator=Validator::make($data,[
            'admin_name' => [
                'required',
                Rule::unique('admin')->ignore($id,'admin_id'),
                'max:6',
                'min:1',
            ], 
            'pwd' => 'required|max:15|min:6',
        ],[
            'admin_name.required'=>'管理员名称必填',
            'admin_name.unique'=>'管理员名称已存在',
            'admin_name.max'=>'管理员名称不能大于6位',
            'admin_name.min'=>'管理员名称不能小于1位',
            'pwd.required'=>'管理员密码必填',
            'pwd.max'=>'管理员密码不能大于15位',
            'pwd.min'=>'管理员密码不能小于6位',
        ]
        ); 
        if($validator->fails()){
            return redirect('admin/create')
                    ->withErrors($validator)
                    ->withInput();
        } 
        if($request->hasFile('admin_logo')){
            $data['admin_logo']=$this->upload('admin_logo');
        }
        $data['pwd']=md5($data['pwd']);
        admin_model::where('admin_id',$id)->update($data);
        return redirect('admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res=admin_model::destroy($id);
        if($res){
            return redirect('admin');
        }
    }
}
