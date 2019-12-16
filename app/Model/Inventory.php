<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table="inventory";
    protected $primaryKey="in_id";
    public $timestamps = false;
    protected $guarded=[];
}
