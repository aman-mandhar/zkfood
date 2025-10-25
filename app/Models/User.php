<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

use App\Models\UserRole;
use App\Models\City;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'mobile_number',
        'ref_mobile_number',
        'ref_id',
        'ref_code',
        'email',
        'password',
        'user_role',
        'address_line1',
        'address_line2',
        'city',
        'gst_no',
        'location_lat',
        'location_lng',
        'session_token', // New field for session token
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ password hashing cast
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Generate unique referral code
            do {
                $code = strtoupper(Str::random(8));
            } while (User::where('ref_code', $code)->exists());

            $user->ref_code = $code;

            // Set referral logic: if ref_id is not set, use user_id = 1
            if (empty($user->ref_id)) {
                $user->ref_id = 1;
            }
        });
    }

    // 🔁 Relationship: Referrer (the one who referred this user)
    public function referrer()
    {
        return $this->belongsTo(User::class, 'ref_id');
    }

    // 🔁 Relationship: Users referred by this user
    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_id');
    }

    // 🔁 Relationship: User role
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'user_role');
    }

    // 🔁 Relationship: City
    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }

    // 🔑 Required by Laravel for authentication (mobile-based login)
    public function getAuthPassword()
    {
        return $this->password;
    }

    // 🔎 Custom finder: Get referrer by mobile number
    public static function getReferralByMobile($mobile_number)
    {
        return self::where('mobile_number', $mobile_number)->first();
    }


}
