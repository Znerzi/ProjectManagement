<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectHistory extends Model
{
    protected $table = 'project_histories';

    protected $fillable = [
        'project_id', 'user_id', 'action', 'description', 'changes', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public $timestamps = true;

    // Relationships
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
