<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    /** 
     * Attributes:
     * - id: The unique identifier for the game
     * - title: The title of the game
     * - description: A brief description of the game
     * - release_date: The date when the game was released
     * - developer: The name of the game's developer
     * - publisher: The name of the game's publisher
     * - genre: The genre of the game (e.g., Action, RPG, Strategy)
     * - platforms: An array of platforms the game is available on (e.g., PC
     * - cover_image_url: A URL to the game's cover image
     * Relationships:
     * - A Game has many GameMedia items (images, videos, etc.)
     * - A Game has many GameReviews
     * - A Game has many GamePricing 
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

    public function getGamesReviews()
    {
        return $this->hasMany('App\Models\GameReview');
    }

    public function getGamePricing()
    {
        // A Game has many GamePricing (the current pricing information for the game)
        return $this->hasMany('App\Models\GamePricing');
    }
}
