<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Redeem extends Model
{
    protected $fillable = ['user_id','device_id','merchant_id','store_id','product_id'];    
}
