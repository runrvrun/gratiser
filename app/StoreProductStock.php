<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StoreProductStock extends Model
{
    public function likes(){
        return $this->belongsToMany('App\User', 'user_store_product_stocks_likes', 'store_product_stock_id', 'user_id');
    }    
}
