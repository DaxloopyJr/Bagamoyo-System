<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $table = 'villages';

    protected $fillable = [
        'village',
        'ward_id',
    ];

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }
}
