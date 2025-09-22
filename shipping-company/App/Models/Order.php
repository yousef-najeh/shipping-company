<?php

namespace App\Models;

use App\Models\BaseModel;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Order extends BaseModel
{
        use HasSpatial;
    protected $fillable = [
        'vendor_id',
        'shipment_id',
        'order_status',
        'size',
        'weight',
        'width',
        'height',
        'length',
        'price',
        'deliver_location'
    ];

    protected $casts = [
        'deliver_location' => Point::class,
    ];
    public function itemOrder(){
        return $this->hasMany(ItemsOrders::class,'order_id');
    }

    public function shipment(){
        return $this->belongsTo(Shipment::class,'shipment_id');
    }

    public function vendor(){
        return $this->belongsTo(VendorShop::class,'vendor_id');
    }
    
}
