<?php

namespace App\Models\License;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LicenseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'license_categories';

    protected $fillable = [
        'name',
        'code',
        'description',
        'default_fee',
        'is_active',
    ];

    protected $casts = [
        'default_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function licenses()
    {
        return $this->hasMany(BusinessLicense::class, 'license_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
