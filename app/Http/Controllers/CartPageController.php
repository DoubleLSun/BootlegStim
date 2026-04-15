<?php

namespace App\Http\Controllers;

use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CartPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the cart page
     */
    public function show(Request $request)
    {
        return view('cart.cartPage');
    }

    /**
     * Return cart snapshot for React page initialization.
     */
    public function data(Request $request)
    {
        return response()->json($this->buildSnapshot((int) $request->user()->id));
    }

    /**
     * Calculate cart totals (kept for compatibility)
     */
    public function calculateTotals(Request $request)
    {
        return response()->json($this->buildSnapshot((int) $request->user()->id));
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request)
    {
        $pricingId = (int) $request->get('pricing_id');

        UserCart::query()
            ->where('user_id', (int) $request->user()->id)
            ->where('game_pricing_id', $pricingId)
            ->delete();

        return response()->json($this->buildSnapshot((int) $request->user()->id));
    }

    /**
     * Clear entire cart
     */
    public function clearCart(Request $request)
    {
        UserCart::query()
            ->where('user_id', (int) $request->user()->id)
            ->delete();

        return response()->json($this->buildSnapshot((int) $request->user()->id));
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request)
    {
        $pricingId = (int) $request->get('pricing_id');
        $targetQuantity = max(1, (int) $request->get('quantity', 1));
        $userId = (int) $request->user()->id;

        $rows = UserCart::query()
            ->where('user_id', $userId)
            ->where('game_pricing_id', $pricingId)
            ->orderBy('id')
            ->get();

        $currentQuantity = $rows->count();

        if ($currentQuantity > $targetQuantity) {
            $deleteCount = $currentQuantity - $targetQuantity;
            $idsToDelete = $rows->take($deleteCount)->pluck('id');
            UserCart::query()->whereIn('id', $idsToDelete)->delete();
        }

        if ($currentQuantity < $targetQuantity) {
            $template = $rows->first();
            if ($template) {
                $insertCount = $targetQuantity - $currentQuantity;
                for ($i = 0; $i < $insertCount; $i++) {
                    UserCart::create([
                        'user_id' => $userId,
                        'game_id' => $template->game_id,
                        'game_pricing_id' => $template->game_pricing_id,
                        'price' => $template->price,
                    ]);
                }
            }
        }

        return response()->json($this->buildSnapshot($userId));
    }

    private function buildSnapshot(int $userId): array
    {
        $cartRows = UserCart::query()
            ->where('user_id', $userId)
            ->with(['gamePricing.getGame.getMedia'])
            ->get();

        $grouped = $cartRows->groupBy('game_pricing_id');
        $cartItems = $grouped->map(function (Collection $rows, $pricingId) {
            $row = $rows->first();
            $pricing = $row->gamePricing;
            $game = $pricing ? $pricing->getGame : null;
            $coverImage = $game ? $game->getMedia->firstWhere('is_cover', true) : null;

            $effectivePrice = $pricing ? (float) $pricing->price : (float) $row->price;
            $discountPercent = $pricing ? (float) $pricing->discount_percentage : 0;

            $originalPrice = $effectivePrice;
            if ($discountPercent > 0) {
                $originalPrice = round($effectivePrice / max(0.01, (1 - ($discountPercent / 100))), 2);
            }

            return [
                'pricing_id' => (int) $pricingId,
                'game_id' => $game ? (int) $game->id : null,
                'game_name' => $game ? $game->title : 'Unknown Game',
                'price' => $effectivePrice,
                'discount_percentage' => $discountPercent,
                'original_price' => $originalPrice,
                'cover_image' => $coverImage ? asset('storage/' . $coverImage->file_path) : asset('images/placeholder.jpg'),
                'quantity' => $rows->count(),
            ];
        })->values();

        $totalPrice = $cartItems->sum(function (array $item) {
            return ((float) $item['price']) * ((int) $item['quantity']);
        });
        $totalItems = $cartItems->sum(function (array $item) {
            return (int) $item['quantity'];
        });

        return [
            'cartItems' => $cartItems,
            'totalPrice' => round((float) $totalPrice, 2),
            'totalItems' => (int) $totalItems,
        ];
    }
}
