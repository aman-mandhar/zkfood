<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','item_id','variant_id','name_snapshot','qty','unit_price','line_total',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function order(){ return $this->belongsTo(FoodOrder::class, 'order_id'); }
    public function item(){ return $this->belongsTo(FoodItem::class, 'item_id'); }
    public function variant(){ return $this->belongsTo(FoodItemVariant::class, 'variant_id'); }
}
