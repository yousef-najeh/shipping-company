<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Client extends BaseModel
{
    protected $fillable=['user_id','salary','location'];

    public function vendors(){
        return $this->hasMany(VendorShop::class,'client_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    use HasFactory;
}
