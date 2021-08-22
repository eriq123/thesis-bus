<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusRoute extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function getQuantityAttribute($value)
    {
        return $value / 100;
    }

    public function setQuantityAttribute($value)
    {
        $this->attributes['fare'] = $value * 100;
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

}
