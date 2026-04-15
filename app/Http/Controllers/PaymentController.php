<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display the checkout / payment page.
     */
    public function paymentPage()
    {
        $user = Auth::user();

        // Fetch active cart items with their related game
        $cartItems = $user->cartItems()->with('game')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')
                ->with('info', 'Your cart is empty.');
        }

        // Price calculations
        $subtotal      = $cartItems->sum(fn($item) => $item->game->price);
        $discountPercent = session('promo_discount_percent', 0);
        $discount      = round($subtotal * ($discountPercent / 100), 2);
        $walletApplied = session('wallet_applied', 0);
        $total         = max(0, $subtotal - $discount - $walletApplied);

        return view('payment.paymentPage', compact(
            'cartItems',
            'subtotal',
            'discount',
            'discountPercent',
            'walletApplied',
            'total'
        ));
    }

    /**
     * Process the payment and create an order.
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:card,paypal,mobile',
        ]);

        $user      = Auth::user();
        $cartItems = $user->cartItems()->with('game')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal      = $cartItems->sum(fn($item) => $item->game->price);
        $discountPercent = session('promo_discount_percent', 0);
        $discount      = round($subtotal * ($discountPercent / 100), 2);
        $walletApplied = session('wallet_applied', 0);
        $total         = max(0, $subtotal - $discount - $walletApplied);

        DB::beginTransaction();

        try {
            // Create the order record
            $order = Order::create([
                'user_id'        => $user->id,
                'reference'      => strtoupper(Str::random(12)),
                'payment_method' => $request->payment_method,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'wallet_applied' => $walletApplied,
                'total'          => $total,
                'status'         => 'completed',
            ]);

            // Attach each purchased game to the order and to the user's library
            foreach ($cartItems as $item) {
                $order->games()->attach($item->game_id, [
                    'price_paid' => $item->game->price,
                ]);

                // Add to user library (pivot: user_games)
                $user->games()->syncWithoutDetaching([
                    $item->game_id => [
                        'hours_played'  => 0,
                        'is_installed'  => false,
                        'last_played'   => null,
                        'purchased_at'  => now(),
                    ],
                ]);
            }

            // Deduct wallet balance if used
            if ($walletApplied > 0) {
                $user->decrement('wallet_balance', $walletApplied);
            }

            // Clear the cart and session state
            $user->cartItems()->delete();
            session()->forget(['promo_code', 'promo_discount_percent', 'wallet_applied']);

            DB::commit();

            // Load purchased games for the success page
            $purchasedGames = $order->games;

            return view('payment.success', compact('order', 'purchasedGames'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Apply a promo code.
     */
    public function applyPromo(Request $request)
    {
        $request->validate(['promo_code' => 'required|string|max:50']);

        // Example promo codes — replace with a DB lookup as needed
        $promos = [
            'BOOTLEG10' => 10,
            'STIM20'    => 20,
            'WELCOME15' => 15,
        ];

        $code    = strtoupper(trim($request->promo_code));
        $percent = $promos[$code] ?? null;

        if ($percent) {
            session([
                'promo_code'             => $code,
                'promo_discount_percent' => $percent,
            ]);

            return back()->with('promo_success', "Code applied! {$percent}% discount.");
        }

        session()->forget(['promo_code', 'promo_discount_percent']);

        return back()->with('promo_error', 'Invalid or expired promo code.');
    }

    /**
     * Toggle wallet balance usage.
     */
    public function toggleWallet()
    {
        $user      = Auth::user();
        $cartItems = $user->cartItems()->with('game')->get();

        $subtotal      = $cartItems->sum(fn($item) => $item->game->price);
        $discountPercent = session('promo_discount_percent', 0);
        $discount      = round($subtotal * ($discountPercent / 100), 2);
        $afterDiscount = $subtotal - $discount;

        if (session('wallet_applied', 0) > 0) {
            session()->forget('wallet_applied');
        } else {
            $walletApply = min($user->wallet_balance, $afterDiscount);
            session(['wallet_applied' => $walletApply]);
        }

        return response()->json(['success' => true]);
    }
}
