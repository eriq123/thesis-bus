<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table = 'status';

    protected $fillable = [
        'title',
        'class',
        'following_id',
    ];

    public function following()
    {
        return $this->belongsTo(Status::class, 'following_id', 'id');
    }

}
