<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class FoodCategory extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id','name','slug','sort_order','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $cat) {
            if (blank($cat->slug) && filled($cat->name)) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }

    public function vendor(){ return $this->belongsTo(FoodVendor::class, 'vendor_id'); }
    public function items(){ return $this->hasMany(FoodItem::class, 'category_id'); }

    public function scopeActive($q){ return $q->where('is_active', true); }
    public function scopeForVendor($q, int $vendorId){ return $q->where('vendor_id', $vendorId); }
}
