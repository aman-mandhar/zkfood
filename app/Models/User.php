<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'mobile_number',
        'ref_mobile_number',
        'ref_id',
        'ref_code',
        'email',
        'password',
        'user_role',       // NOTE: no rename
        'address_line1',
        'address_line2',
        'city',            // NOTE: FK -> cities.id (no rename)
        'gst_no',
        'location_lat',
        'location_lng',
        'session_token',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'location_lat' => 'decimal:7',
        'location_lng' => 'decimal:7',
    ];

    // --- Events ---
    protected static function booted(): void
    {
        static::creating(function (self $user) {
            // referral code only if empty
            if (blank($user->ref_code)) {
                do {
                    $code = strtoupper(Str::random(8));
                } while (self::where('ref_code', $code)->exists());
                $user->ref_code = $code;
            }

            // default ref_id = 1 if empty
            if (blank($user->ref_id)) {
                $user->ref_id = 1;
            }
        });
    }

    // --- Relationships ---
    public function referrer()    { return $this->belongsTo(self::class, 'ref_id'); }
    public function referrals()   { return $this->hasMany(self::class, 'ref_id'); }
    public function role()        { return $this->belongsTo(UserRole::class, 'user_role'); }
    public function cityRef()     { return $this->belongsTo(City::class, 'city'); } // alias to avoid method name clash

    // convenience: vendor profile (if any)
    public function vendor()      { return $this->hasOne(FoodVendor::class, 'user_id'); }

    // --- Scopes/Helpers ---
    public function scopeRole($q, int $roleId)     { return $q->where('user_role', $roleId); }
    public function scopeInCity($q, int $cityId)   { return $q->where('city', $cityId); }

    public function isAdmin(): bool    { return (int)$this->user_role === 1; }
    public function isCustomer(): bool { return (int)$this->user_role === 2; }
    public function isVendor(): bool   { return (int)$this->user_role === 5; }
    public function isDelivery(): bool { return (int)$this->user_role === 7; }

    // Normalise mobile (optional)
    public function setMobileNumberAttribute($val)
    {
        $digits = preg_replace('/\D+/', '', (string)$val);
        $this->attributes['mobile_number'] = substr($digits, -10); // keep last 10
    }
}
