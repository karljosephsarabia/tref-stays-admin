<?php

namespace SMD\Common\ReservationSystem\Models;

use Illuminate\Database\Eloquent\Model;

class RsZipCode extends Model
{
    protected $table = 'rs_zip_codes';
    protected $guarded = [];

    /**
     * Scope a query to only include active zip codes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
