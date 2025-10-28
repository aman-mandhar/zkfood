<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FoodVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status','token_id','user_id','ref_id','name','slug','address','city_id',
        'mobile_no','email','aadhar_no','fssai_code','gst_no','pan_no','upi_id',
        'bank_name','branch_name','ifsc_code','account_no','account_holder_name',
        'account_type','fssai_certificate','aadhar_front','aadhar_back','pan_card',
        'cancel_cheque','photo',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    // --- Slugging ---
    protected static function booted(): void
    {
        static::creating(function (self $vendor) {
            if (blank($vendor->slug) && filled($vendor->name)) {
                $vendor->slug = self::generateUniqueSlug($vendor->name);
            }
        });

        static::updating(function (self $vendor) {
            if ($vendor->isDirty('slug') && filled($vendor->slug)) {
                $vendor->slug = self::generateUniqueSlug($vendor->slug, true, $vendor->id);
            } elseif ($vendor->isDirty('name') && blank($vendor->slug)) {
                $vendor->slug = self::generateUniqueSlug($vendor->name, false, $vendor->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $source, bool $isSlug = false, ?int $ignoreId = null): string
    {
        $base = Str::slug($source);
        $slug = $base; $i = 2;

        while (self::query()
            ->when($ignoreId, fn($q) => $q->where('id','<>',$ignoreId))
            ->where('slug',$slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }

    // --- Relations ---
    public function user()     { return $this->belongsTo(User::class, 'user_id'); }
    public function referrer() { return $this->belongsTo(User::class, 'ref_id'); }
    public function city()     { return $this->belongsTo(City::class, 'city_id'); }

    // keep as placeholder if you have this table
    public function token()    { return $this->belongsTo(VendorTokenSystem::class, 'token_id'); }

    // --- Helpers ---
    public function isActive(): bool { return strtolower((string)$this->status) === 'active'; }

    public function fullAddress(): string
    {
        return $this->address
            ? $this->address . ', ' . optional($this->city)->name
            : (optional($this->city)->name ?? '');
    }

    public function getRouteKeyName(): string { return 'slug'; }
}
