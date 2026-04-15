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
     * - price: The base price of the game
     * - release_date: The date when the game was released
     * - is_featured: Whether the game is featured
     * - created_by: Admin who created the entry
     * - developer_id: FK to developers
     * - publisher_id: FK to publishers
     * - cover_image: URL to the game's cover image
     *
     * Relationships:
     * - A Game has many GameMedia items (images, videos, etc.)
     * - A Game has many GameReviews
     * - A Game has many GamePricing
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'release_date',
        'is_featured',
        'created_by',
        'developer_id',
        'publisher_id',
        'cover_image',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'release_date' => 'date',
        'is_featured'  => 'boolean',
        'price'        => 'decimal:2',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * Game hasMany GameMedia.
     */
    public function media()
    {
        return $this->hasMany(GameMedia::class);
    }

    /**
     * Game hasMany GameReviews.
     */
    public function getGamesReviews()
    {
        return $this->hasMany(\App\Models\GameReview::class);
    }

    /**
     * Game hasMany GamePricing.
     */
    public function getGamePricing()
    {
        return $this->hasMany(\App\Models\GamePricing::class);
    }
}
