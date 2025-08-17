<?php

namespace App\Models;

use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Model;


class BaseModel extends Model{
    use HasSpatial;

    protected $fillable = [
        'location',
    ];

    protected $casts = [
        'location' => Point::class,
    ];
}