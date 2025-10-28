<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FoodItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id','category_id','name','slug','desc','price','mrp',
        'is_veg','is_available','prep_minutes','image','tax_rate',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_veg' => 'boolean',
        'is_available' => 'boolean',
        'prep_minutes' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $item) {
            if (blank($item->slug) && filled($item->name)) {
                $item->slug = Str::slug($item->name);
            }
        });
    }

    public function vendor(){ return $this->belongsTo(FoodVendor::class, 'vendor_id'); }
    public function category(){ return $this->belongsTo(FoodCategory::class, 'category_id'); }
    public function variants(){ return $this->hasMany(FoodItemVariant::class, 'item_id'); }

    public function scopeAvailable($q){ return $q->where('is_available', true); }
    public function scopeVeg($q){ return $q->where('is_veg', true); }
    public function scopeForVendor($q, int $vendorId){ return $q->where('vendor_id', $vendorId); }
}
