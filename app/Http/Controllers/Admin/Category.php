<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category as cate_model;
use Illuminate\Validation\Rule; 
use Validator;

class Category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cate_name=request()->cate_name;
        $where=[];
        if(!empty($cate_name)){
            $where[]=['cate_name','like',"%$cate_name%"];
        }
        $object=request()->all();
        $data=cate_model::where($where)->get();
        return view('admin.category.index',['data'=>$data,'object'=>$object]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=cate_model::all();
        $data=getCateInfo($data);
        return view('admin.category.create',['data'=>$data]);
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
            'cate_name' => 'required|unique:category|max:15|min:2',
        ],[
            'cate_name.required'=>'分类名称必填',
            'cate_name.unique'=>'分类名称已存在',
            'cate_name.max'=>'分类名称不能大于15位',
            'cate_name.min'=>'分类名称不能小于2位',
        ]
        ); 
        if($validator->fails()){
            return redirect('category/create')
                    ->withErrors($validator)
                    ->withInput();
        } 
        $res=cate_model::create($data);
        if($res){
            return redirect('category');
        }else{
            return redirect('category/create');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=cate_model::all();
        $data=getCateInfo($data);
        $arr=cate_model::where('cate_id',$id)->first();
        return view('admin.category.edit',['data'=>$data,'arr'=>$arr]);
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
            'cate_name' => [
                'required',
                Rule::unique('category')->ignore($id,'cate_id'),
                'max:15',
                'min:2',
            ], 
        ],[
            'cate_name.required'=>'分类名称必填',
            'cate_name.unique'=>'分类名称已存在',
            'cate_name.max'=>'分类名称不能大于15位',
            'cate_name.min'=>'分类名称不能小于2位',
        ]
        ); 
        if($validator->fails()){
            return redirect('brand/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
        }
        cate_model::where('cate_id',$id)->update($data);
        return redirect('category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data=cate_model::where('parent_id',$id)->count();
        if($data>0){
            echo "<script>alert('该分类下有子分类');location.href='/category';</script>";
        }else{
            $res=cate_model::destroy($id);
            if($res){
                return redirect('category');
            }
        }
    }
    public function changeAsd(){
        $data=request()->except('_token');
        $res=cate_model::where('cate_id',$data['cate_id'])->update(['asd'=>$data['value']]);
        if($res==1){
            return redirect('category');
        }
    }
    public function changeCateShow(){
        $data=request()->except('_token');
        $res=cate_model::where('cate_id',$data['cate_id'])->update(['cate_show'=>$data['value']]);
        if($res==1){
            return redirect('category');
        }
    }
}
