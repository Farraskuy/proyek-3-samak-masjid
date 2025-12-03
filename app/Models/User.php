<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'username',
        'full_name',
        'phone_number',
        'image_url',
        'email',
        'email_verified_at',
        'password',
        'notifications_email',
        'newsletter',
        'public_profile',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission($permission): bool
    {
        if (!$this->role) {
            return false;
        }

        // Admin role usually has all permissions, but let's stick to explicit permissions for now
        // unless we want to hardcode 'Admin' bypass.
        // The spec says "Hanya Admin yang dapat membuat role...".
        // If we want to allow Admin everything, we can do it here or in Gate.
        // Let's rely on the role's permissions.

        return $this->role->permissions()->where('name', $permission)->exists();
    }

    /**
     * Check if user has a specific role (by name).
     */
    public function hasRole($roleName): bool
    {
        return $this->role && $this->role->name == $roleName;
    }

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
            'notifications_email' => 'boolean',
            'newsletter' => 'boolean',
            'public_profile' => 'boolean',
        ];
    }
}
