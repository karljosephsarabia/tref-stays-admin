<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsSavedProperty extends Model
{
    protected $table = 'rs_saved_properties';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(\App\RsUser::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }

    /**
     * Check if a property is saved by a user
     */
    public static function isSaved($userId, $propertyId)
    {
        return self::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->exists();
    }

    /**
     * Toggle save status
     */
    public static function toggleSave($userId, $propertyId)
    {
        $existing = self::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->first();

        if ($existing) {
            $existing->delete();
            return false; // unsaved
        }

        self::create([
            'user_id' => $userId,
            'property_id' => $propertyId
        ]);
        return true; // saved
    }
}
