<?php

namespace App\Http\Controllers;

use App\Models\GamePricing;
use App\Models\UserCart;
use Illuminate\Http\Request;

class TestDataController extends Controller
{
    /**
     * Populate cart with test data for development/testing
     * 
     * Usage: Visit /test/populate-cart
     */
    public function populateCart(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Login required for DB-backed cart test data.',
            ], 401);
        }

        $userId = (int) $request->user()->id;
        UserCart::query()->where('user_id', $userId)->delete();

        $pricings = GamePricing::query()->orderBy('id')->take(3)->get();
        if ($pricings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No game_pricings records found to seed test cart.',
            ], 422);
        }

        foreach ($pricings as $pricing) {
            UserCart::create([
                'user_id' => $userId,
                'game_id' => $pricing->game_id,
                'game_pricing_id' => $pricing->id,
                'price' => $pricing->price,
            ]);
        }

        if ($pricings->count() >= 3) {
            UserCart::create([
                'user_id' => $userId,
                'game_id' => $pricings[2]->game_id,
                'game_pricing_id' => $pricings[2]->id,
                'price' => $pricings[2]->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Test user_carts data populated',
            'nextStep' => 'Visit /cart to see the shopping cart page',
        ]);
    }

    /**
     * Clear all test data
     * 
     * Usage: Visit /test/clear-cart
     */
    public function clearTestCart(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Login required for DB-backed cart test data.',
            ], 401);
        }

        UserCart::query()
            ->where('user_id', (int) $request->user()->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Test user_carts cleared',
        ]);
    }

    /**
     * Get current cart data
     * 
     * Usage: Visit /test/cart-status
     */
    public function cartStatus(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Login required for DB-backed cart status.',
            ], 401);
        }

        $cart = UserCart::query()
            ->where('user_id', (int) $request->user()->id)
            ->orderBy('id')
            ->get(['id', 'game_id', 'game_pricing_id', 'price']);

        $countsByPricing = $cart->groupBy('game_pricing_id')->map->count();

        return response()->json([
            'cartItems' => $cart,
            'itemCount' => count($cart),
            'totalQuantity' => count($cart),
            'countsByPricingId' => $countsByPricing,
        ]);
    }
}
