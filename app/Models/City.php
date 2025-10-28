<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // users.city (no rename)
    public function users()
    {
        return $this->hasMany(User::class, 'city');
    }

    // vendors.city_id
    public function vendors()
    {
        return $this->hasMany(FoodVendor::class, 'city_id');
    }
}
