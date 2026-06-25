<?php

namespace App\Models\Fishery;

use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fisherman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fishermen';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'id_number',
        'registration_date',
        'region_id',
        'district_id',
        'ward_id',
        'village_id',
        'address',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function boats()
    {
        return $this->hasMany(FishingBoat::class, 'fisherman_id');
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
}
