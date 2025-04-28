<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'name',
        'is_archived',
        'is_muted'
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'is_muted' => 'boolean'
    ];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_participants')
            ->select('users.id', 'users.name', 'conversation_participants.*')
            ->withPivot(['is_favorite', 'is_muted', 'is_blocked', 'last_read_at', 'is_creator'])
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function sharedMedia(): HasMany
    {
        return $this->hasMany(SharedMedia::class);
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    public function getUnreadCountAttribute()
    {
        return $this->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('created_at', '>', $this->participants()
                ->where('user_id', auth()->id())
                ->value('last_read_at') ?? '1970-01-01')
            ->count();
    }

    public function getLastMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    public function markAsRead()
    {
        $this->participants()
            ->where('user_id', auth()->id())
            ->update(['last_read_at' => now()]);
    }
}
