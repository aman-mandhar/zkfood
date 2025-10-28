<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','city_id','vehicle_type','online_status',
        'current_lat','current_lng','document_status',
    ];

    protected $casts = [
        'current_lat' => 'decimal:7',
        'current_lng' => 'decimal:7',
    ];

    public function user(){ return $this->belongsTo(User::class); }
    public function city(){ return $this->belongsTo(City::class); }
    public function assignments(){ return $this->hasMany(FoodOrderAssignment::class, 'delivery_partner_id'); }

    public function scopeOnline($q){ return $q->where('online_status','online'); }
    public function scopeInCity($q, int $cityId){ return $q->where('city_id',$cityId); }
}
