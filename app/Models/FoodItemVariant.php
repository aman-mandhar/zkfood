<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodItemVariant extends Model
{
    use HasFactory;

    protected $fillable = ['item_id','label','price_delta','is_active'];

    protected $casts = [
        'price_delta' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function item(){ return $this->belongsTo(FoodItem::class, 'item_id'); }
}
