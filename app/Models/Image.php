<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'imageable_id',
        'imageable_type',
        'path',
        'caption',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}

