<?php

namespace App\Models;

use App\Models\License\BusinessLicense;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HygieneReminder extends Model
{
    use HasFactory;

    protected $table = 'hygiene_reminders';

    protected $fillable = [
        'license_id',
        'message',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function license()
    {
        return $this->belongsTo(BusinessLicense::class, 'license_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'sent' => '<span class="badge bg-info">Sent</span>',
            'delivered' => '<span class="badge bg-success">Delivered</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
