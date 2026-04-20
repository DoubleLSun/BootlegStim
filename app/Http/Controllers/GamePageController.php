<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GamePricing;
use App\Models\GameReview;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamePageController extends Controller
{
    public function show(Request $request, Game $game)
    {
        if ($game->is_delisted && (!auth()->check() || (string) (auth()->user()->role ?? '') !== 'admin')) {
            abort(404);
        }

        // Load the media items and reviews for the game
        $game->load([
            'media' => function ($query) {
                $query->where('type', 'image')
                    ->orderByDesc('is_cover')
                    ->orderBy('sort_order')
                    ->orderBy('id');
            },
            'getGamesReviews' => function ($query) {
                $query->with('getUser')
                    ->orderByDesc('review_date')
                    ->orderByDesc('helpful_votes')
                    ->orderByDesc('id');
            },
            'genres' => function ($query) {
                $query->where('display_flag', true)->orderBy('name');
            },
        ]);

        // Determine the default image to display
        $mediaItems = $game->media->values();
        // First try to find the cover image, if not found, use the first media item, if still not found, use a placeholder
        $defaultMedia = $mediaItems->firstWhere('is_cover', true) ?? $mediaItems->first();
        // Use the URL of the default media if available, otherwise fall back to the game's cover image or a placeholder
        $defaultImage = optional($defaultMedia)->url
            ?? $game->cover_image
            ?? 'https://via.placeholder.com/1200x675?text=No+Image';

        // Handle thumbnail toggle logic
        $toggleMediaId = $request->query('toggle_media');
        $currentActiveThumbId = $request->query('active_thumb_id');
        // Determine the active thumbnail ID based on the toggle logic
        $activeThumbId = null;

        // If toggleMediaId is provided, toggle the active thumbnail ID
        if ($toggleMediaId !== null) {
            // If the clicked thumbnail is already active, deactivate it; otherwise, activate the clicked thumbnail
            $activeThumbId = (string) $currentActiveThumbId === (string) $toggleMediaId
                ? null
                : (int) $toggleMediaId;
        }

        // Find the active media item based on the active thumbnail ID, if any
        $activeMedia = $activeThumbId ? $mediaItems->firstWhere('id', $activeThumbId) : null;
        // Use the URL of the active media if available, otherwise fall back to the default image
        $selectedImage = optional($activeMedia)->url ?? $defaultImage;

        $pricingRow = null;
        if ($game->use_pricing_tag) {
            if (!empty($game->selected_pricing_id)) {
                $pricingRow = DB::table('game_pricings')
                    ->where('id', $game->selected_pricing_id)
                    ->where('game_id', $game->id)
                    ->first();
            }

            if (!$pricingRow) {
                $pricingRow = DB::table('game_pricings')
                    ->where('game_id', $game->id)
                    ->orderBy('id')
                    ->first();
            }
        }

        $originalPrice = $pricingRow->price ?? $game->price ?? 0;
        $discountedPrice = $pricingRow->discounted_price ?? null;
        $hasDiscount = $pricingRow && $discountedPrice !== null && (float) $discountedPrice < (float) $originalPrice;

        $reviews = $game->getGamesReviews;
        $totalReviews = $reviews->count();
        $recommendedCount = $reviews->where('is_recommended', true)->count();
        $recommendedPercent = $totalReviews > 0
            ? (int) round(($recommendedCount / $totalReviews) * 100)
            : 0;

        $reviewSummary = 'No user reviews yet';
        if ($totalReviews > 0) {
            if ($recommendedPercent >= 85) {
                $reviewSummary = 'Overwhelmingly Positive';
            } elseif ($recommendedPercent >= 70) {
                $reviewSummary = 'Very Positive';
            } elseif ($recommendedPercent >= 40) {
                $reviewSummary = 'Mixed';
            } else {
                $reviewSummary = 'Mostly Negative';
            }
        }

        $pricing = [
            'pricing_id' => $pricingRow->id ?? null,
            'currency' => $pricingRow->currency ?? 'USD',
            'base_price' => (float) $originalPrice,
            'has_discount' => $hasDiscount,
            'discount_percent' => $pricingRow->discount_percentage ?? null,
            'discounted_price' => $hasDiscount ? (float) $discountedPrice : null,
            'is_applied' => (bool) $game->use_pricing_tag,
        ];
        
        // Pass the game, media items, selected image, and active thumbnail ID to the view
        return view('games.gamesPage', [
            'game' => $game,
            'mediaItems' => $mediaItems,
            'selectedImage' => $selectedImage,
            'activeThumbId' => $activeThumbId,
            'pricing' => $pricing,
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'recommendedCount' => $recommendedCount,
            'recommendedPercent' => $recommendedPercent,
            'reviewSummary' => $reviewSummary,
        ]);
    }

    public function addToCart(Request $request, Game $game)
    {
        $validated = $request->validate([
            'pricing_id' => 'nullable|integer|exists:game_pricings,id',
        ]);

        $pricing = null;

        if (!empty($validated['pricing_id'])) {
            $pricing = GamePricing::query()
                ->where('id', $validated['pricing_id'])
                ->where('game_id', $game->id)
                ->first();
        }

        if (!$pricing) {
            $pricing = $game->getGamePricing()->orderBy('id')->first();
        }

        if (!$pricing) {
            return back()->withErrors([
                'pricing_id' => 'No pricing is available for this game yet.',
            ]);
        }

        UserCart::create([
            'user_id' => $request->user()->id,
            'game_id' => $game->id,
            'game_pricing_id' => $pricing->id,
            'price' => $pricing->discounted_price ?? $pricing->price ?? $game->price ?? 0,
        ]);

        return back()->with('success', 'Game added to your cart.');
    }

    public function storeComment(Request $request, Game $game)
    {
        $validated = $request->validate([
            'review_content' => 'required|string|max:1000',
            'is_recommended' => 'required|boolean',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = GameReview::firstOrNew([
            'user_id' => $request->user()->id,
            'game_id' => $game->id,
        ]);

        if (!$review->exists) {
            $review->hours_played = 0;
            $review->helpful_votes = 0;
        }

        $review->review_content = $validated['review_content'];
        $review->is_recommended = (bool) $validated['is_recommended'];
        $review->rating = (int) $validated['rating'];
        $review->review_date = now();
        $review->save();

        return back()->with('success', 'Your comment was posted.');
    }

    public function updateComment(Request $request, Game $game, GameReview $review)
    {
        if ((int) $review->game_id !== (int) $game->id) {
            abort(404);
        }

        if (!$this->canManageReview($request, $review)) {
            abort(403);
        }

        $validated = $request->validate([
            'review_content' => 'required|string|max:1000',
            'is_recommended' => 'required|boolean',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->review_content = $validated['review_content'];
        $review->is_recommended = (bool) $validated['is_recommended'];
        $review->rating = (int) $validated['rating'];
        $review->review_date = now();
        $review->save();

        return back()->with('success', 'Review updated successfully.');
    }

    public function deleteComment(Request $request, Game $game, GameReview $review)
    {
        if ((int) $review->game_id !== (int) $game->id) {
            abort(404);
        }

        if (!$this->canManageReview($request, $review)) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }

    public function markHelpful(Request $request, Game $game, GameReview $review)
    {
        if ((int) $review->game_id !== (int) $game->id) {
            abort(404);
        }

        $review->increment('helpful_votes');

        return back()->with('success', 'Thanks for your feedback. Helpful vote recorded.');
    }

    private function canManageReview(Request $request, GameReview $review): bool
    {
        $user = $request->user();
        if (!$user) {
            return false;
        }

        $isOwner = (int) $review->user_id === (int) $user->id;
        $isAdmin = (string) ($user->role ?? '') === 'admin';

        return $isOwner || $isAdmin;
    }
}
