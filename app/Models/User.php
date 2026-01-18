<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Task;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ================= RBAC HELPERS ================= */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperUser(): bool
    {
        return $this->role === 'super_user';
    }

    public function isStandardUser(): bool
    {
        return $this->role === 'standard';
    }

    public function canManageTasks(): bool
    {
        // Admin and Super User can create/manage tasks
        return in_array($this->role, ['admin', 'super_user'], true);
    }

    public function canAssignTo(User $target): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isSuperUser()) {
            return $target->isStandardUser();
        }

        return false;
    }

    /* ================= RELATIONSHIPS ================= */

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
