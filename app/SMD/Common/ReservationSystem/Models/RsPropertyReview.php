<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsPropertyReview extends Model
{
    protected $table = 'rs_property_reviews';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\RsUser::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }

    public function reservation()
    {
        return $this->belongsTo(RsReservation::class, 'reservation_id');
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Get average rating for a property
     */
    public static function getAverageRating($propertyId)
    {
        return self::where('property_id', $propertyId)
            ->where('is_approved', true)
            ->avg('rating') ?? 0;
    }

    /**
     * Get review count for a property
     */
    public static function getReviewCount($propertyId)
    {
        return self::where('property_id', $propertyId)
            ->where('is_approved', true)
            ->count();
    }
}
