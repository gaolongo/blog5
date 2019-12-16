<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    protected $table="summary";
    protected $primaryKey="sum_id";
    public $timestamps = false;
    protected $guarded=[];
}
