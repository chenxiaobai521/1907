<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //关联到模型的数据表    
    protected $table = 'category'; 
    //关联到模型的数据表的主键id  
    protected $primaryKey  = 'cate_id'; 
    //不能被批量赋值的属性 
    protected $guarded = []; 
    //表明模型是否应该被打上时间戳 
    public $timestamps = false;
}