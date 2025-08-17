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


    public function client(){
        return  $this->belongsTo(Client::class,'client_id');
    }
    
    public function order(){
        return $this->hasMany(Order::class,'vendor_id');
    }

    public function user(){
        return $this->hasOneThrough(
            User::class,
            Client::class,
            "id",
            "id",
            "client_id",
            "user_id"
        );
    }
}
