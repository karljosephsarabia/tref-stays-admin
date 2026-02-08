<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsPropertyView extends Model
{
    protected $table = 'rs_property_views';

    protected $fillable = [
        'property_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'source'
    ];

    /**
     * Get the property
     */
    public function property()
    {
        return $this->belongsTo(RsProperty::class, 'property_id');
    }

    /**
     * Get the user who viewed (if logged in)
     */
    public function user()
    {
        return $this->belongsTo(\App\RsUser::class, 'user_id');
    }

    /**
     * Record a view
     */
    public static function recordView($propertyId, $request)
    {
        // Prevent duplicate views from same IP in short period
        $recentView = self::where('property_id', $propertyId)
            ->where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if (!$recentView) {
            return self::create([
                'property_id' => $propertyId,
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'source' => self::determineSource($request)
            ]);
        }

        return null;
    }

    /**
     * Determine the source of the visit
     */
    protected static function determineSource($request)
    {
        $referrer = $request->header('referer');
        
        if (!$referrer) {
            return 'direct';
        }

        if (str_contains($referrer, 'google') || str_contains($referrer, 'bing')) {
            return 'search';
        }

        if (str_contains($referrer, 'facebook') || str_contains($referrer, 'instagram') || str_contains($referrer, 'twitter')) {
            return 'social';
        }

        return 'referral';
    }
}
