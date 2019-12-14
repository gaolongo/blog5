<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{
    protected $table = 'goodsattr';
	protected $primaryKey = 'g_id';
	public $timestamps = false;
	protected $guarded = [];
}
