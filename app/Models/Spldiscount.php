<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spldiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_id',
        'order_id',
        'user_coupon_id',
        'user_id',
        'issued',
        'used',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function userCoupon()
    {
        return $this->belongsTo(UserCoupon::class, 'user_coupon_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ip()
    {
        return $this->belongsTo(InvestmentPackage::class, 'ip_id');
    }
}
