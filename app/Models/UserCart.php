<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;

    protected $table = 'user_carts';

    public $timestamps = false;

    /**
     * Purpose: stores wanted games that had not been paid for yet, so that users can add games to their cart and then proceed 
     * to checkout when they are ready to purchase. 
     * The cart will store the game_id and the game_pricing_id (selected pricing plan for each item in cart)
     *  so that if the price changes later, live price change can be correctly applied to cart items which haven't been paid for yet.
     * The cart will also store the user_id so that we can associate the cart with a specific user and allow them to 
     * view and manage their cart items. The cart will also have a relationship with the Game model 
     * so that we can easily retrieve the game details for each item in the cart when displaying the cart page or during checkout.
     * 
     * Destination: Once paid for and verified, the cart items will be moved to the UserLibrary model to represent the user's owned games.
     */

    /**
     * Attributes:
     * - id: The unique identifier for the user cart
     * - user_id: The ID of the user this cart belongs to
     * - game_id: The ID of the game added to the cart
     * - game_pricing_id: The ID of the game pricing information for the item in the cart 
     * - price: The price of the game_pricing_id from the game_pricings table (responds to change from that table)
     * Relationships:
     * - A UserCart belongs to a User (the owner of the cart)
     * - A UserCart has many Game items (the games added to the cart, with their pricing information)
     */

    protected $fillable = [
        'user_id',
        'game_id',
        'game_pricing_id',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function gamePricing()
    {
        return $this->belongsTo(GamePricing::class, 'game_pricing_id');
    }
}
