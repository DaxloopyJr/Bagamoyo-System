<?php

namespace App\Models\Market;

use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'markets';

    protected $fillable = [
        'name',
        'description',
        'latitude',
        'longitude',
        'region_id',
        'district_id',
        'ward_id',
        'village_id',
        'street',
        'total_cages',
        'occupied_cages',
        'market_type',
        'facilities',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'facilities' => 'array',
    ];

    public function cages()
    {
        return $this->hasMany(MarketCage::class, 'market_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getAvailableCagesAttribute()
    {
        return $this->total_cages - $this->occupied_cages;
    }
}
