<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SMD\Common\ReservationSystem\Enums\RoleType;

class RsUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'rs_users';
    protected $guarded = [];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOwners($query)
    {
        return $query->where('role_id', RoleType::OWNER);
    }

    public function scopeCustomers($query)
    {
        return $query->where('role_id', RoleType::CUSTOMER);
    }

    public function scopeBrokers($query)
    {
        return $query->where('role_id', RoleType::BROKER);
    }

    public function scopeRenters($query)
    {
        return $query->where('role_id', RoleType::RENTER);
    }

    public function properties()
    {
        return $this->hasMany(RsProperty::class, 'owner_id');
    }

    public function reservations()
    {
        return $this->hasMany(RsReservation::class, 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(RsTransaction::class, 'user_id');
    }

    public function incomingReports()
    {
        return $this->hasMany(RsIncomingReport::class, 'user_id');
    }

    public function getIsBrokerAttribute()
    {
        return $this->role_id === RoleType::BROKER;
    }

    public function getIsOwnerAttribute()
    {
        return $this->role_id === RoleType::OWNER;
    }

    public function getIsCustomerAttribute()
    {
        return $this->role_id === RoleType::CUSTOMER;
    }

    public function getBalanceAttribute()
    {
        return $this->transactions()->where('active', true)->sum('amount') ?? 0;
    }
}
