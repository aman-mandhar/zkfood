<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodVendorHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id','day_of_week','open_time','close_time','is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function vendor(){ return $this->belongsTo(FoodVendor::class, 'vendor_id'); }
}
