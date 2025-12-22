<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The primary key type.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot function from Laravel.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->id)) {
                do {
                    $id = \Illuminate\Support\Str::random(32);
                } while (self::where('id', $id)->exists());
                
                $user->id = $id;
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'email',
        'password',
        'avatar',
        'first_name',
        'last_name',
        'phone',
        'bio',
        'status',
        'country',
        'city_state',
        'postal_code',
        'tax_id',
        'facebook',
        'x_link',
        'linkedin',
        'instagram',
        'google_id',
        'google_token',
        'google_refresh_token',
        'github_id',
        'github_token',
        'github_refresh_token',
        'last_login_at',
        'last_login_provider',
        'last_login_ip',
        'last_login_user_agent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->last_name ?? ''));
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Check if user is customer
     */
    public function isCustomer(): bool
    {
        return $this->role && $this->role->name === 'customer';
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
