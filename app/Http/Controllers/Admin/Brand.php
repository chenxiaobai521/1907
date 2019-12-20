<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Brand as brand_model;
// use App\Http\Requests\StoreBrandPost;
use Illuminate\Validation\Rule; 
use Validator;
use DB;

class Brand extends Controller
{
    /**
     * Display a listing of the resource.
     *展示列表页
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageSize=config('app.pageSize');
        $brand_name=request()->brand_name; 
        $brand_url=request()->brand_url;
        $where=[];
        if(!empty($brand_name)){
            $where[]=['brand_name','like',"%$brand_name%"];
        }
        if(!empty($brand_url)){
            $where[]=['brand_url','like',"%$brand_url%"];
        }
        $object=request()->all();
        //监听sql
        // DB::connection()->enableQueryLog();
        $data=brand_model::where($where)->orderBy('brand_id','desc')->paginate($pageSize);
        // $logs = DB::getQueryLog();
        // dd($logs);
        return view('admin.brand.index',['data'=>$data,'object'=>$object]);
    }

    /**
     * Show the form for creating a new resource.
     *展示添加视图
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *执行添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    // public function store(StoreBrandPost $request)
    {
        $data=$request->except('_token');
        // $validatedData = $request->validate([
        //     'brand_name' => 'required|unique:brand|max:15|min:2',
        //     'brand_url' => 'required',
        // ],[
        //     'brand_name.required'=>'品牌名称必填',
        //     'brand_name.unique'=>'品牌名称已存在',
        //     'brand_name.max'=>'品牌名称不能大于15位',
        //     'brand_name.min'=>'品牌名称不能小于2位',
        //     'brand_url.required'=>'品牌网址必填',
        // ]);
        $validator=Validator::make($data,[
            'brand_name' => 'required|unique:brand|max:15|min:2',
            'brand_url' => 'required',
        ],[
            'brand_name.required'=>'品牌名称必填',
            'brand_name.unique'=>'品牌名称已存在',
            'brand_name.max'=>'品牌名称不能大于15位',
            'brand_name.min'=>'品牌名称不能小于2位',
            'brand_url.required'=>'品牌网址必填',
        ]
        ); 
        if($validator->fails()){
            return redirect('brand/create')
                    ->withErrors($validator)
                    ->withInput();
        } 
        if($request->hasFile('brand_logo')){
            $data['brand_logo']=$this->upload('brand_logo');
        }
        $res=brand_model::create($data);
        if($res){
            return redirect('brand');
        }else{
            return redirect('brand.create');
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
     *展示修改页
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=brand_model::where('brand_id',$id)->first();
        return view('admin.brand.edit',['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *执行修改
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    // public function update(StoreBrandPost $request, $id)
    {
        $data=$request->except('_token');
        
        // $validatedData = $request->validate([
        //     'brand_name' => 'required|unique:brand|max:15|min:2',
        //     'brand_url' => 'required',
        // ],[
        //     'brand_name.required'=>'品牌名称必填',
        //     'brand_name.unique'=>'品牌名称已存在',
        //     'brand_name.max'=>'品牌名称不能大于15位',
        //     'brand_name.min'=>'品牌名称不能小于2位',
        //     'brand_url.required'=>'品牌网址必填',
        // ]);
        $validator=Validator::make($data,[
            'brand_name' => [
                'required',
                Rule::unique('brand')->ignore($id,'brand_id'),
                'max:15',
                'min:2',
            ], 
            'brand_url' => 'required',
        ],[
            'brand_name.required'=>'品牌名称必填',
            'brand_name.unique'=>'品牌名称已存在',
            'brand_name.max'=>'品牌名称不能大于15位',
            'brand_name.min'=>'品牌名称不能小于2位',
            'brand_url.required'=>'品牌网址必填',
        ]
        ); 
        if($validator->fails()){
            return redirect('brand/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
        }
        if(request()->hasFile('brand_logo')){
            $data['brand_logo']=$this->upload('brand_logo');
        }
        brand_model::where('brand_id',$id)->update($data);
        return redirect('brand');
    }

    /**
     * Remove the specified resource from storage.
     *执行删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res=brand_model::destroy($id);
        if($res){
            return redirect('brand');
        }
    }
}
