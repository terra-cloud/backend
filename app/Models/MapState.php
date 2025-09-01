<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapState extends Model
{
    use HasFactory;

    protected $fillable = [
        'gid_1',
        'name',
        'geometry',
    ];

    protected $casts = [
        'geometry' => 'array'
    ];

    public function cities()
    {
        return $this->hasMany(MapCity::class);
    }
}
