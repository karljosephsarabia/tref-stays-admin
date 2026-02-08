<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsProperty extends Model
{
    protected $table = 'rs_properties';
    protected $guarded = [];
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function owner()
    {
        return $this->belongsTo(RsUser::class, 'owner_id');
    }

    public function images()
    {
        return $this->hasMany(RsPropertyImage::class, 'property_id');
    }

    public function reservations()
    {
        return $this->hasMany(RsReservation::class, 'property_id');
    }
}
