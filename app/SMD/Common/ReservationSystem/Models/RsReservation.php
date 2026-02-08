<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;
use SMD\Common\ReservationSystem\Enums\RoleType;

class RsReservation extends Model
{
    protected $table = 'rs_reservations';
    protected $guarded = [];
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
    
    public function scopeForUser($query, $user)
    {
        if ($user->role_id == RoleType::BROKER) {
            return $query;
        } elseif ($user->role_id == RoleType::OWNER) {
            return $query->whereHas('property', function($q) use ($user) {
                $q->where('owner_id', $user->id);
            });
        } else {
            return $query->where('customer_id', $user->id);
        }
    }
    
    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(RsUser::class, 'customer_id');
    }
}
