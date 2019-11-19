<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    public function pcategories()
    {
        return $this->HasMany('App\ProductPcategory');
    }
}
