<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','vendor_id','items_count','subtotal','delivery_fee','tax','grand_total','status',
    ];

    protected $casts = [
        'items_count' => 'integer',
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function vendor(){ return $this->belongsTo(FoodVendor::class, 'vendor_id'); }
    public function items(){ return $this->hasMany(FoodCartItem::class, 'cart_id'); }

    public function scopeActive($q){ return $q->where('status','active'); }

    public function recalcTotals(): void
    {
        $subtotal = $this->items()->sum('line_total');
        $this->subtotal = $subtotal;
        $this->grand_total = $subtotal + (float)$this->delivery_fee + (float)$this->tax;
        $this->items_count = $this->items()->sum('qty');
        $this->save();
    }
}
