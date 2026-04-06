<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    /** Relationships 
     * Game hasMany GameMedia
     * 
     * */ 
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'developer',
        'publisher',
        'genre',
        'platforms',
        'cover_image_url'
    ];

    protected $casts = [
        'release_date' => 'date',
        'platforms' => 'array'
    ];

    public function getMedia()
    {
        return $this->hasMany('App\Models\GameMedia');
    }

}
