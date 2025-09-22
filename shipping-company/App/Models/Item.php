<?php

namespace App\Models;

use App\Models\BaseModel;

class Item extends BaseModel
{

    protected $fillable=['name','price'];

    public function itemsOrders(){
        return $this->hasMany(ItemsOrders::class,'item_id');
    }
}
