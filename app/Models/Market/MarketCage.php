<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketCage extends Model
{
    use HasFactory;

    protected $table = 'market_cages';

    protected $fillable = [
        'market_id',
        'cage_number',
        'cost',
        'rent_cost',
        'status',
        'occupied_by',
        'occupied_date',
        'description',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'rent_cost' => 'decimal:2',
        'occupied_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => '<span class="badge bg-success">Available</span>',
            'occupied' => '<span class="badge bg-danger">Occupied</span>',
            'maintenance' => '<span class="badge bg-warning text-dark">Maintenance</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
