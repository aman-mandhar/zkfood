<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','provider','provider_order_id','provider_payment_id','status','amount','currency','payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payload' => 'array',
    ];

    public function order(){ return $this->belongsTo(FoodOrder::class, 'order_id'); }
}
