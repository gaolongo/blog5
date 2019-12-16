<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $table = 'advert';
	protected $primaryKey = 'ad_id';
	public $timestamps = false;
	protected $guarded = [];
}
