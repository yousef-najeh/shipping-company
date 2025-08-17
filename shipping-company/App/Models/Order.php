<?php

namespace App\Models;

use App\Models\BaseModel;

class Order extends BaseModel
{
    
    public function itemOrder(){
        return $this->hasMany(ItemsOrders::class,'order_id');
    }

    public function shipment(){
        return $this->hasOne(Shipment::class,'shipment_id');
    }

    public function vendor(){
        return $this->hasOne(VendorShop::class,'vendor_id');
    }
    
}
