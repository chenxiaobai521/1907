<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //关联到模型的数据表    
    protected $table = 'address'; 
    //关联到模型的数据表的主键id  
    protected $primaryKey  = 'address_id'; 
    //不能被批量赋值的属性 
    protected $guarded = []; 
    //表明模型是否应该被打上时间戳 
    public $timestamps = false; 
}