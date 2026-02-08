<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsPropertyImage extends Model
{
    protected $table = 'rs_property_images';
    protected $guarded = [];

    /**
     * Get the property this image belongs to
     */
    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }
}
