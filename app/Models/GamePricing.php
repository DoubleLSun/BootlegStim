<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePricing extends Model
{
    use HasFactory;
    protected $table = 'game_pricings';
    /**
     * Attributes:
     * - id: The unique identifier for the game pricing
     * - game_id: The ID of the game this pricing information belongs to
     * - price: The current price of the game
     * - discount_percentage: The percentage of discount applied to the game (if any)
     * - discounted_price: The price of the game after applying the discount (if any)
     * - currency: The currency in which the price is listed (e.g., USD, EUR)
     * Relationships:
     * - A Game_Pricing belongs to a Game (the game this pricing information is associated with)
     * - A Game can have many Game_Pricing (the available pricing information for the game)    
     */

    protected $fillable = [
        "game_id",
        "price",
        "discount_percentage",
        "discounted_price",
        "currency"
    ];

    protected $casts = [
        "price" => "decimal:2",
        "discount_percentage" => "decimal:2",
        "discounted_price" => "decimal:2",
    ];

    public function getGame()
    {
        return $this->belongsTo('App\Models\Game', 'game_id');
    }
}
