<?php

namespace App\Models\Settings;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileAdvertisement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mobile_advertisements';

    protected $fillable = [
        'title',
        'description',
        'contact_person',
        'contact_phone',
        'contact_email',
        'business_type',
        'subscription_fee',
        'subscription_start',
        'subscription_end',
        'status',
        'image',
        'gallery',
        'view_count',
        'click_count',
        'is_featured',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'subscription_fee' => 'decimal:2',
        'subscription_start' => 'date',
        'subscription_end' => 'date',
        'gallery' => 'array',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
        'view_count' => 'integer',
        'click_count' => 'integer',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getIsExpiredAttribute()
    {
        return $this->subscription_end < now();
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">Pending</span>',
            'active' => '<span class="badge bg-success">Active</span>',
            'expired' => '<span class="badge bg-danger">Expired</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
