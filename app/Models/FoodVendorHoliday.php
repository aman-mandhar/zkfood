<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodVendorHoliday extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','holiday_date','reason'];

    protected $casts = [
        'holiday_date' => 'date',
    ];

    public function vendor(){ return $this->belongsTo(FoodVendor::class, 'vendor_id'); }
}
