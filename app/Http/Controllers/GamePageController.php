<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GamePageController extends Controller
{
    public function show(Request $request, Game $game)
    {
        // Load the media items for the game, filtering for images and ordering them appropriately
        $game->load([
            'getMedia' => function ($query) {
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
        ]);

        // Determine the default image to display
        $mediaItems = $game->getMedia->values();
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

        $pricingRow = DB::table('game_pricings')
            ->where('game_id', $game->id)
            ->first();

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
}
