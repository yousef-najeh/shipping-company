<?php
namespace App\Models;



use App\Models\BaseModel;


class VendorShop extends BaseModel{

    protected $fillable = [
        'client_id',
        'shop_name',
        'location',
        'shop_phone',
        'shop_email',
        'shop_website',
        'shop_description',
        'created_at',
        'updated_at'
    ];


    public function user(){
        return  $this->belongsTo(User::class,'user_id');
    }
    
    public function order(){
        return $this->hasMany(Order::class,'vendor_id');
    }

    public function client(){
        return $this->hasOneThrough(
            Client::class,
            User::class,
            "id",
            "id",
            "user_id",
            "client_id"
        );
    }
}
