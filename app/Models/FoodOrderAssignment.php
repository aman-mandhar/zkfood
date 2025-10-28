<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodOrderAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','delivery_partner_id','status','eta_minutes','assigned_at','picked_at','delivered_at',
    ];

    protected $casts = [
        'eta_minutes' => 'integer',
        'assigned_at' => 'datetime',
        'picked_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order(){ return $this->belongsTo(FoodOrder::class, 'order_id'); }
    public function deliveryPartner(){ return $this->belongsTo(DeliveryPartner::class, 'delivery_partner_id'); }

    public function scopeWaiting($q){ return $q->where('status','waiting'); }
    public function scopeAssigned($q){ return $q->where('status','assigned'); }
}
