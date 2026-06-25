<?php

namespace App\Models\License;

use App\Models\Location\District;
use App\Models\Location\Region;
use App\Models\Location\Village;
use App\Models\Location\Ward;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BusinessLicense extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'business_licenses';

    protected $fillable = [
        'owner_name',
        'phone',
        'email',
        'license_number',
        'license_category_id',
        'license_type',
        'issue_date',
        'expiry_date',
        'payment_amount',
        'payment_status',
        'business_name',
        'business_description',
        'latitude',
        'longitude',
        'region_id',
        'district_id',
        'ward_id',
        'village_id',
        'street',
        'building',
        'hygiene_reminder_sent',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'payment_amount' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'hygiene_reminder_sent' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($license) {
            if (!$license->license_number) {
                $license->license_number = 'BML-' . strtoupper(uniqid());
            }
            if (!$license->expiry_date && $license->issue_date) {
                $license->expiry_date = $license->license_type === 'mid_year'
                    ? Carbon::parse($license->issue_date)->addMonths(6)
                    : Carbon::parse($license->issue_date)->addYear();
            }
        });

        static::updating(function ($license) {
            if ($license->isDirty('issue_date') || $license->isDirty('license_type')) {
                $license->expiry_date = $license->license_type === 'mid_year'
                    ? Carbon::parse($license->issue_date)->addMonths(6)
                    : Carbon::parse($license->issue_date)->addYear();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(LicenseCategory::class, 'license_category_id');
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

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now()->format('Y-m-d'));
    }

    public function scopeExpiringSoon($query, $days)
    {
        return $query->whereDate('expiry_date', '<=', now()->addDays($days))
            ->whereDate('expiry_date', '>=', now());
    }

    public function scopeExpiringToday($query)
    {
        return $query->whereDate('expiry_date', now());
    }

    public function scopeExpiringThisMonth($query)
    {
        return $query->whereMonth('expiry_date', now()->month)
            ->whereYear('expiry_date', now()->year);
    }

    public function scopeExpiringInThreeMonths($query)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addMonths(3)]);
    }

    public function scopeExpiringThisYear($query)
    {
        return $query->whereYear('expiry_date', now()->year);
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->expiry_date, false);
    }

    public function getStatusAttribute()
    {
        if ($this->expiry_date < now()) {
            return 'expired';
        }
        if ($this->days_until_expiry <= 30) {
            return 'expiring_soon';
        }
        return 'active';
    }

    public function getStatusBadgeAttribute()
    {
        $status = $this->status;
        $badges = [
            'expired' => '<span class="badge bg-danger">Expired</span>',
            'expiring_soon' => '<span class="badge bg-warning text-dark">Expiring Soon</span>',
            'active' => '<span class="badge bg-success">Active</span>',
        ];
        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
