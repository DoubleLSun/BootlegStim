<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMedia extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'type',
        'url',
        'thumbnail_url',
        'sort_order',
        'is_cover'
    ];
    public function media()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }
}
