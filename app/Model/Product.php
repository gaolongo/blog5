<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
	protected $primaryKey = 'pro_id';
	public $timestamps = false;
	protected $guarded = [];
}
