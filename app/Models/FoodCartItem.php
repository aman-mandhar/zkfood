<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodCartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id','item_id','variant_id','qty','unit_price','line_total','notes',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function cart(){ return $this->belongsTo(FoodCart::class, 'cart_id'); }
    public function item(){ return $this->belongsTo(FoodItem::class, 'item_id'); }
    public function variant(){ return $this->belongsTo(FoodItemVariant::class, 'variant_id'); }
}
