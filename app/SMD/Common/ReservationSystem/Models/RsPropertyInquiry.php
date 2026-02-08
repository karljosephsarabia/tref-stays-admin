<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsPropertyInquiry extends Model
{
    protected $table = 'rs_property_inquiries';

    protected $fillable = [
        'property_id',
        'owner_id',
        'name',
        'email',
        'phone',
        'message',
        'check_in_date',
        'check_out_date',
        'guests',
        'status',
        'owner_notes',
        'read_at',
        'responded_at',
        'active'
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'read_at' => 'datetime',
        'responded_at' => 'datetime',
        'active' => 'boolean'
    ];

    /**
     * Get the property this inquiry is for
     */
    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }

    /**
     * Get the owner of the property
     */
    public function owner()
    {
        return $this->belongsTo(\App\RsUser::class, 'owner_id');
    }

    /**
     * Scope for new/unread inquiries
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Mark as responded
     */
    public function markAsResponded()
    {
        $this->update([
            'status' => 'responded',
            'responded_at' => now()
        ]);
    }
}
