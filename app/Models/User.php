<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'status', 'avatar', 'bio',
        'company_name', 'company_address', 'company_phone', 'company_website', 'industry', 'total_budget',
        'skills', 'experience_level', 'hourly_rate', 'completed_projects', 'completed_tasks', 'average_rating',
        'last_login_at', 'last_login_ip'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'skills' => 'array',
        ];
    }

    // Relationships
    public function clientProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function developerAssignments(): HasMany
    {
        return $this->hasMany(ProjectAssignment::class, 'developer_id');
    }

    public function developerTasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class, 'developer_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ProjectHistory::class);
    }

    // Helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}

