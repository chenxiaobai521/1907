<?php

/**
 * 公用的方法  返回json数据，进行信息的提示
 * @param $status 状态
 * @param string $message 提示信息
 * @param array $data 返回数据
 */
function getCateInfo($data,$parent_id=0,$level=1){
        static $info=[];
        foreach($data as $k=>$v){
            if($v['parent_id']==$parent_id){
                $v['level']=$level;
                $info[]=$v;
                getCateInfo($data,$v['cate_id'],$v['level']+1);
            }
        }    
        return $info;
}
