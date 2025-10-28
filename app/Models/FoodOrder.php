<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no','user_id','vendor_id','address_id','payment_status','order_status',
        'subtotal','delivery_fee','tax','grand_total','paid_at','cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'tax' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function vendor(){ return $this->belongsTo(FoodVendor::class, 'vendor_id'); }
    public function address(){ return $this->belongsTo(FoodAddress::class, 'address_id'); }

    public function items(){ return $this->hasMany(FoodOrderItem::class, 'order_id'); }
    public function assignment(){ return $this->hasOne(FoodOrderAssignment::class, 'order_id'); }
    public function payment(){ return $this->hasOne(FoodPayment::class, 'order_id'); }

    public function scopePlaced($q){ return $q->where('order_status','placed'); }
    public function scopeForVendor($q, int $vendorId){ return $q->where('vendor_id',$vendorId); }

    public function markPaid(): void
    {
        $this->payment_status = 'paid';
        $this->paid_at = now();
        $this->save();
    }
}
