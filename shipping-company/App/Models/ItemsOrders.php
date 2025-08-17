<?php

namespace App\Models;

use App\Models\BaseModel;

class ItemsOrders extends BaseModel
{
    public function item(){
        return $this->belongsTo(items::class,'item_id');
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
