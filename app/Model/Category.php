<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table="category";
    // protected $primaryKey='id';
    public $timestamps=false;
    // protected $guarded=[];
    public static function createTree($data,$pid=0,$level=0)
    {
        if(!$data || !is_array($data)){
            return;
        }
        static $arr=[];
        foreach($data as $k=>$v){
            if($v['cate_pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                self::createTree($data,$v['cate_id'],$level+1);
            }
        }
        return $arr;
    }
}
