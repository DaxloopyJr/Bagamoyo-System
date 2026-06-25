<?php

namespace App\Models\Settings;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileOpportunity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mobile_opportunities';

    protected $fillable = [
        'title',
        'description',
        'opportunity_type',
        'organization',
        'contact_email',
        'contact_phone',
        'deadline',
        'image',
        'attachment',
        'link_url',
        'is_featured',
        'status',
        'created_by',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_featured' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('opportunity_type', $type);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'published' => '<span class="badge bg-success">Published</span>',
            'archived' => '<span class="badge bg-warning text-dark">Archived</span>',
        ];
        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}
