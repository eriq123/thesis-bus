<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id', 'id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function starting_point()
    {
        return $this->belongsTo(BusRoute::class, 'starting_point_id', 'id');
    }

    public function destination()
    {
        return $this->belongsTo(BusRoute::class, 'destination_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
