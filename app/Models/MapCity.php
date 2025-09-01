<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'gid_2',
        'name',
        'geometry',
        'map_state_id',
        'merge_on'
    ];

    protected $casts = [
        'geometry' => 'array',
        'merge_on' => 'array'
    ];

    public function state()
    {
        return $this->belongsTo(MapState::class);
    }

    public function barangays()
    {
        return $this->hasMany(MapBarangay::class);
    }
}
