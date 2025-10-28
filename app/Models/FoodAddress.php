<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','label','line1','line2','city_id','pincode',
        'lat','lng','is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function city(){ return $this->belongsTo(City::class); }

    public function scopeDefault($q){ return $q->where('is_default', true); }
}
