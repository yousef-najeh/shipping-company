<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable {
    
    use HasApiTokens,HasFactory, Notifiable;
    
    protected $fillable = ['name', 'email','first_name','last_name', 'password','phone_number', 'role', 'status'];


    public function client(){
        return $this->hasOne(Client::class,'user_id');
    }
     public function driver(){
        return $this->hasOne(DriverProfile::class,'user_id');
    }
     public function employee(){
        return $this->hasOne(EmployeeProfile::class,'user_id');
    }
     public function manager(){
        return $this->hasOne(ManagerProfile::class,'user_id');
    }
     public function admin(){
        return $this->hasOne(AdminProfile::class,'user_id');
    }
     public function shipment(){
        return $this->hasOne(Shipment::class,'user_id');
    }
}
