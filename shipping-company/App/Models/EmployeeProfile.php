<?php

namespace App\Models;

use App\Models\BaseModel;

class EmployeeProfile extends BaseModel
{
    protected $fillable=['user_id','salary','location'];

    public function employee(){
        return $this->belongsTo(User::class,'user_id');
    }
}
