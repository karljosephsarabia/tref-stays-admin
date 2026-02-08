<?php

namespace App\SMD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsCustomAmenity extends Model
{
    use HasFactory;

    protected $table = 'rs_custom_amenities';

    protected $fillable = [
        'name',
        'slug',
        'icon_path',
        'icon_class',
        'type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the icon HTML (either SVG or Font Awesome)
     */
    public function getIconHtmlAttribute()
    {
        if ($this->icon_path) {
            return '<img src="' . asset('storage/' . $this->icon_path) . '" class="custom-amenity-svg" alt="' . $this->name . '">';
        }
        
        if ($this->icon_class) {
            return '<i class="' . $this->icon_class . '"></i>';
        }
        
        return '<i class="fas fa-star"></i>';
    }

    /**
     * Scope for general amenities
     */
    public function scopeGeneral($query)
    {
        return $query->where('type', 'general');
    }

    /**
     * Scope for kosher amenities
     */
    public function scopeKosher($query)
    {
        return $query->where('type', 'kosher');
    }

    /**
     * Scope for active amenities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
