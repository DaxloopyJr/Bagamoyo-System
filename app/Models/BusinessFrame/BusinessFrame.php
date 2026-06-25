<?php

namespace App\Models\BusinessFrame;

use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessFrame extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'business_frames';

    protected $fillable = [
        'frame_number',
        'frame_name',
        'latitude',
        'longitude',
        'region_id',
        'district_id',
        'ward_id',
        'village_id',
        'street',
        'area_description',
        'status',
        'rent_cost',
        'rented_to',
        'rented_to_phone',
        'rent_start_date',
        'rent_end_date',
        'description',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rent_cost' => 'decimal:2',
        'rent_start_date' => 'date',
        'rent_end_date' => 'date',
        'is_active' => 'boolean',
    ];

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

    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }

    public function scopeNotRented($query)
    {
        return $query->where('status', 'not_rented');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'rented' => '<span class="badge bg-success">Rented</span>',
            'not_rented' => '<span class="badge bg-danger">Not Rented</span>',
            'under_maintenance' => '<span class="badge bg-warning text-dark">Under Maintenance</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
