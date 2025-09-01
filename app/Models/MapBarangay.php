<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapBarangay extends Model
{
    use HasFactory;

    protected $fillable = [
        'gid_3',
        'name',
        'geometry',
        'map_city_id'
    ];

    protected $casts = [
        'geometry' => 'array'
    ];

    public function city()
    {
        return $this->belongsTo(MapCity::class);
    }
}
