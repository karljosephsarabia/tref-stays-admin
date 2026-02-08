<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsConversation extends Model
{
    protected $table = 'rs_conversations';
    protected $guarded = [];
    
    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function userOne()
    {
        return $this->belongsTo(\App\RsUser::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(\App\RsUser::class, 'user_two_id');
    }

    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }

    public function messages()
    {
        return $this->hasMany(RsMessage::class, 'conversation_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(RsMessage::class, 'conversation_id')->latest();
    }

    /**
     * Get the other participant in the conversation
     */
    public function getOtherUser($userId)
    {
        if ($this->user_one_id == $userId) {
            return $this->userTwo;
        }
        return $this->userOne;
    }

    /**
     * Get unread message count for a user in this conversation
     */
    public function getUnreadCount($userId)
    {
        return $this->messages()
            ->where('recipient_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get or create a conversation between two users
     */
    public static function getOrCreate($userOneId, $userTwoId, $propertyId = null)
    {
        // Check both directions
        $conversation = self::where(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userOneId)->where('user_two_id', $userTwoId);
        })->orWhere(function($q) use ($userOneId, $userTwoId) {
            $q->where('user_one_id', $userTwoId)->where('user_two_id', $userOneId);
        });

        if ($propertyId) {
            $conversation->where('property_id', $propertyId);
        }

        $existing = $conversation->first();

        if ($existing) {
            return $existing;
        }

        return self::create([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
            'property_id' => $propertyId,
            'last_message_at' => now(),
        ]);
    }
}
