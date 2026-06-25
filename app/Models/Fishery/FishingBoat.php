<?php

namespace App\Models\Fishery;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FishingBoat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fishing_boats';

    protected $fillable = [
        'fisherman_id',
        'owner_name',
        'boat_number',
        'capacity_kg',
        'length_m',
        'boat_type',
        'engine_power',
        'year_built',
        'registration_status',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'capacity_kg' => 'decimal:2',
        'length_m' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function fisherman()
    {
        return $this->belongsTo(Fisherman::class, 'fisherman_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRegistered($query)
    {
        return $query->where('registration_status', 'registered');
    }
}
