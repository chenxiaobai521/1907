<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Goods as goods_model;
use App\Model\Brand as brand_model;
use App\Model\Category as cate_model;
use Illuminate\Validation\Rule; 
use Validator;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;

class Goods extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goods_name=request()->goods_name;
        $where=[];
        if(!empty($goods_name)){
            $where[]=['goods_name','like',"%$goods_name%"];
        }
        $object=request()->all();

        $page=request()->page;
        // $data=Cache::get('data|'.$goods_name.'|'.$page); 
        // echo 'data|'.$goods_name.'|'.$page;
        // Redis::del('data:'.$goods_name.':'.$page);
        $data=Redis::get('data:'.$goods_name.':'.$page);
        $data=unserialize($data);
        if(!$data){
            echo "aaa";
            $pageSize=config('app.pageSize');
            $data=goods_model::where($where)->paginate($pageSize);
            
            // Cache::put(['data|'.$goods_name.'|'.$page=>$data],10);
            Redis::set('data:'.$goods_name.':'.$page,serialize($data));
        }
        
        foreach($data as $k=>$v){
            $data[$k]['goods_imgs']=explode('|',$v['goods_imgs']);
        }
        $data1=brand_model::all();
        $data2=cate_model::all();
        return view('admin.goods.index',['data'=>$data,'object'=>$object,'data1'=>$data1,'data2'=>$data2]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data1=brand_model::all();
        $data=cate_model::all();
        $data=getCateInfo($data);
        return view('admin.goods.create',['data'=>$data,'data1'=>$data1]);
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
            'goods_name' => 'required|unique:goods|max:12|min:2',
            'goods_price' => 'required|numeric|min:0',
            'goods_score' => 'required|numeric|integer|min:0',
        ],[
            'goods_name.required'=>'商品名称必填',
            'goods_name.unique'=>'商品名称已存在',
            'goods_name.max'=>'商品名称不能大于12位',
            'goods_name.min'=>'商品名称不能小于2位',
            'goods_price.required'=>'商品价格必填',
            'goods_price.numeric'=>'商品价格必须是数字',
            'goods_price.min'=>'商品价格需为正整数',
            'goods_score.required'=>'商品积分必填',
            'goods_score.numeric'=>'商品积分必须是数字',
            'goods_score.integer'=>'商品积分需为整数',
            'goods_score.min'=>'商品积分需为正整数',
            'goods_num.required'=>'商品库存必填',
            'goods_num.numeric'=>'商品积分必须是数字',
            'goods_num.integer'=>'商品积分需为整数',
            'goods_num.min'=>'商品积分需为正整数',
        ]
        ); 
        if($validator->fails()){
            return redirect('goods/create')
                    ->withErrors($validator)
                    ->withInput();
        }
        //单文件上传
        if($request->hasFile('goods_img')){
            $data['goods_img']=$this->upload('goods_img');
        }
        //多文件上传
        if ($request->hasFile('goods_imgs')) {
           
            $imgs =$this->upload('goods_imgs');
            $data['goods_imgs'] = implode('|',$imgs);
         }
        $res=goods_model::create($data);
        if($res){
            return redirect('goods');
        }else{
            return redirect('goods.create');
        }
    }

    /**
     * 支持单、多文件上传
     * @param type $file
     * @return typed
     */
    public function upload($image){
        $imgs = request()->file($image);
        if(is_array($imgs)){
           //多文件上传 
            $result = [];
            foreach($imgs as $v){
                 //验证文件是否上传成功
                if ($v->isValid()){
                    //接收文件并上传
                    $result[] = $v->store('uploads');
                    //返回上传的文件路径 
                } 
            }
            return $result; 
        }else{
            //单文件上传
              //验证文件是否上传成功
            if ($imgs->isValid()){
                //接收文件并上传
                $path = request()->file($image)->store('uploads');
                //返回上传的文件路径
                return $path;
            } 
        }
        exit('文件上传出错！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=goods_model::where('goods_id',$id)->first();
        $data['goods_imgs']=explode('|',$data['goods_imgs']);
        $data1=brand_model::all();
        $data2=cate_model::all();
        $data2=getCateInfo($data2);
        return view('admin.goods.edit',['data'=>$data,'data1'=>$data1,'data2'=>$data2]);
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
            'goods_name' => [
                'required',
                Rule::unique('goods')->ignore($id,'goods_id'),
                'max:12',
                'min:2',
            ], 
            'goods_price' => 'required|numeric|min:0',
            'goods_score' => 'required|numeric|integer|min:0',
        ],[
            'goods_name.required'=>'商品名称必填',
            'goods_name.unique'=>'商品名称已存在',
            'goods_name.max'=>'商品名称不能大于12位',
            'goods_name.min'=>'商品名称不能小于2位',
            'goods_price.required'=>'商品价格必填',
            'goods_price.numeric'=>'商品价格必须是数字',
            'goods_price.min'=>'商品价格需为正整数',
            'goods_score.required'=>'商品积分必填',
            'goods_score.numeric'=>'商品积分必须是数字',
            'goods_score.integer'=>'商品积分需为整数',
            'goods_score.min'=>'商品积分需为正整数',
            'goods_num.required'=>'商品库存必填',
            'goods_num.numeric'=>'商品积分必须是数字',
            'goods_num.integer'=>'商品积分需为整数',
            'goods_num.min'=>'商品积分需为正整数',
        ]
        ); 
        if($validator->fails()){
            return redirect('goods/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
        }
        //单文件上传
        if($request->hasFile('goods_img')){
            $data['goods_img']=$this->upload('goods_img');
        }
        //多文件上传
        if ($request->hasFile('goods_imgs')) {
           
            $imgs =$this->upload('goods_imgs');
            $data['goods_imgs'] = implode('|',$imgs);
         }
        goods_model::where('goods_id',$id)->update($data);
        return redirect('goods');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function change(){
        $data=request()->except('_token');
        $res=goods_model::where('goods_id',$data['goods_id'])->update([$data['field']=>$data['value']]);
        if($res==1){
            return redirect('goods');
        }
    }
    public function wy()
    {
        $goods_name=request()->goods_name;
        $goods_id=request()->goods_id;
        $where=[
            ['goods_name','=',$goods_name],
            ['goods_id','<>',$goods_id]
        ];
        $res=goods_model::where($where)->first();
        if(!empty($res)){
            echo 1; 
        }else{
            echo 2; 
        }
    }
}                                                                                                          
