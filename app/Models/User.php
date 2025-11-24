<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Models\Enums\RegisterType;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'member_uuid',
        'phone',
        'industry_affiliation',
        'region',
        'membership_type_id',
        'agreed_to_terms',
        'register_type',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'agreed_to_terms' => 'boolean',
        'register_type' => RegisterType::class,
    ];

    /**
     * Auto-generate a member_uuid for new users if missing.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->member_uuid)) {
                $user->member_uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * ✅ Relationship: A user belongs to a membership type.
     */
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class);
    }

    /**
     * ✅ Relationship: A user can have many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
