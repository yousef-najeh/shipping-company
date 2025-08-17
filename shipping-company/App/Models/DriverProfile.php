<?php

namespace App\Models;

use App\Models\BaseModel;

class DriverProfile extends BaseModel
{
    protected $fillable=['user_id','salary','location','license_type','license_number'];

    public function driver(){
        return $this->belongsTo(User::class,'user_id');
    }

}
