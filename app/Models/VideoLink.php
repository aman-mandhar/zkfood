<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'videoable_id',
        'videoable_type',
        'link',
        'caption',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function videoable()
    {
        return $this->morphTo();
    }

    public function getEmbedLinkAttribute()
    {
        return str_replace('watch?v=', 'embed/', $this->link);
    }
}
