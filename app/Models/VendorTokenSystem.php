<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorTokenSystem extends Model
{
    use HasFactory;

    protected $table = 'vendor_token_systems';

    protected $fillable = [
        'name', 'total', 'discount', 'activity', 'vip_activity', 'direct_ref',
        'ref_1', 'ref_2', 'ref_3', 'vendor_ref', 'admin_comm'
    ];

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'token_id');
    }
}