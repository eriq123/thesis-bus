<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusRoute extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

}
