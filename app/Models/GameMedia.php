<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMedia extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'game_id',
        'type',
        'url',
        'thumbnail_url',
        'sort_order',
        'is_cover',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_cover'   => 'boolean',
        'sort_order'  => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * The game this media belongs to.
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
