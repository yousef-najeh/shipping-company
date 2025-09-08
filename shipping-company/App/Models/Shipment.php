<?php

namespace App\Models;

use App\Models\BaseModel;

class Shipment extends BaseModel
{
    protected $fillable = [
        'user_id',
        'status',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];
    public function user(){
        return $this->hasOne(User::class,'user_id');
    }
    
    public function order(){
        return $this->hasMany(Order::class,'shipment_id');
    }

    public function driver(){
        return $this->hasOneThrough(
            User::class,
            DriverProfile::class,
            'id',
            'id',
            'user_id',
            'user_id'
        );
    }
}
