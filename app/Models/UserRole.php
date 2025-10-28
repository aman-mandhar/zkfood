<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = ['name','description'];

    // FK column on users = 'user_role' (no rename)
    public function users()
    {
        return $this->hasMany(User::class, 'user_role');
    }
}
