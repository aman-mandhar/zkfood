<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodWebhookLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider','event','body','signature','status','processed_at',
    ];

    protected $casts = [
        'body' => 'array',
        'processed_at' => 'datetime',
    ];
}
