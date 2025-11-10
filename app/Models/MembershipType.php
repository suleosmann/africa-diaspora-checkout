<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'description',
        'status',
    ];

    /**
     * ✅ Each membership type can have many users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
