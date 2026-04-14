<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameReview extends Model
{
    use HasFactory;

    /**
     * Each game review belongs to a user and a game, and has the following attributes:
    * - id: The unique identifier for the review
     * - user_id: The ID of the user who wrote the review
     * - game_id: The ID of the game being reviewed
     * - is_recommended: A boolean indicating whether the user recommends the game
     * - review_content: The content of the review
     * - review_date: The date when the review was submitted
     * Relationships:
     * - A GameReview belongs to a User (the reviewer)
     * - A GameReview belongs to a Game (the game being reviewed)
     * - A Game can have many GameReviews
     * - A User can have many GameReviews
     */

    protected $fillable = [
        "user_id",
        "game_id",
        "is_recommended",
        "review_content",
        "review_date",
        "rating",
        "hours_played",
        "helpful_votes",
    ];

    protected $casts = [
        "is_recommended" => "boolean",
        "review_date" => "datetime",
        "rating" => "integer",
        "hours_played" => "integer",
        "helpful_votes" => "integer",
    ];

    public function getUser()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getGame()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }
    
}
