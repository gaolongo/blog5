<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $table="stat";
    protected $primaryKey="st_id";
    public $timestamps = false;
    protected $guarded=[];
}
