<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRouteDestination extends Model
{
    use HasFactory;

    public function bus_routes()
    {
        return $this->hasMany(BusRoute::class);
    }
}
