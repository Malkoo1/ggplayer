<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'status',
        'is_edited',
        'edited_at'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime'
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(SharedMedia::class, 'media_id');
    }

    public function markAsDelivered()
    {
        $this->update(['status' => 'delivered']);
    }

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    public function edit($newMessage)
    {
        $this->update([
            'message' => $newMessage,
            'is_edited' => true,
            'edited_at' => now()
        ]);
    }
}
