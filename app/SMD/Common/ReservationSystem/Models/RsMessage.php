<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsMessage extends Model
{
    protected $table = 'rs_messages';
    protected $guarded = [];
    
    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(\App\RsUser::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(\App\RsUser::class, 'recipient_id');
    }

    public function conversation()
    {
        return $this->belongsTo(RsConversation::class, 'conversation_id');
    }

    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Send a new message
     */
    public static function sendMessage($senderId, $recipientId, $message, $propertyId = null, $conversationId = null)
    {
        // Get or create conversation
        if (!$conversationId) {
            $conversation = RsConversation::getOrCreate($senderId, $recipientId, $propertyId);
            $conversationId = $conversation->id;
        }

        $newMessage = self::create([
            'sender_id' => $senderId,
            'recipient_id' => $recipientId,
            'property_id' => $propertyId,
            'conversation_id' => $conversationId,
            'message' => $message,
            'is_read' => false,
        ]);

        // Update conversation last message time
        RsConversation::where('id', $conversationId)->update([
            'last_message_at' => now()
        ]);

        return $newMessage;
    }
}
