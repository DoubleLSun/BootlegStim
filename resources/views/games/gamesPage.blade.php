@extends('layouts.app')

@section('title', $game->title . ' | Product Page')
@section('main-class', '')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/games/gamesStorePage.css') }}">
@endpush

@section('content')
@php
    $mediaCollection = collect($mediaItems ?? []);
    $reviewCollection = collect($reviews ?? []);
    $selectedImage = $selectedImage
        ?? optional($mediaCollection->firstWhere('is_cover', true))->url
        ?? optional($mediaCollection->first())->url
        ?? $game->cover_image
        ?? 'https://via.placeholder.com/1200x675?text=No+Image';
    $priceCurrency = $pricing['currency'] ?? 'USD';
    $basePrice = $pricing['base_price'] ?? $game->price ?? 0;
    $hasDiscount = (bool) ($pricing['has_discount'] ?? false);
    $discountPercent = $pricing['discount_percent'] ?? null;
    $discountedPrice = $pricing['discounted_price'] ?? null;
    $activeThumbId = $activeThumbId ?? null;
@endphp

<main class="page-wrap">
    <h1 class="page-title">{{ $game->title }}</h1>
    <p class="page-subtitle">{{ $game->description }}</p>

    <section class="media-section" aria-label="Game media gallery and showcase">
        <article class="primary-gallery">
            <div class="selected-image-shell {{ $activeThumbId ? 'is-highlighted' : '' }}" id="selectedImageShell">
                <img
                    id="selectedImage"
                    src="{{ $selectedImage }}"
                    alt="Selected media preview for {{ $game->title }}"
                >
                <span class="selected-state" id="selectedState">Selected</span>
            </div>

            @if ($mediaCollection->isNotEmpty())
                <div class="thumb-strip" id="thumbStrip" aria-label="Scrollable image options">
                    @foreach ($mediaCollection as $media)
                        <a
                            href="{{ route('games.show', ['game' => $game, 'toggle_media' => $media->id, 'active_thumb_id' => $activeThumbId]) }}"
                            class="thumb-button {{ (string) $activeThumbId === (string) $media->id ? 'is-active' : '' }}"
                            aria-label="Select image {{ $loop->iteration }}"
                        >
                            <img src="{{ $media->thumbnail_url ?? $media->url }}" alt="Game thumbnail {{ $loop->iteration }}">
                        </a>
                    @endforeach
                </div>
            @else
                <p class="empty-note">No images in the game_media table for this game yet.</p>
            @endif
        </article>

        <aside class="showcase-panel" aria-label="Game details summary panel">
            <h2 class="showcase-title">Game Summary</h2>

            <section class="showcase-section" aria-label="Main cover image">
                <img src="{{ $game->cover_image }}" alt="Main cover image for {{ $game->title }}">
            </section>

            <section class="showcase-section" aria-label="Game description">
                <h3>Description</h3>
                <p>{{ $game->description ?: 'No description available yet.' }}</p>
            </section>

            <section class="showcase-section" aria-label="Review summary">
                <h3>Review Summary</h3>
                <p>Placeholder: Review summary is not implemented yet.</p>
            </section>

            <section class="showcase-section" aria-label="Release date">
                <h3>Release Date</h3>
                <p>{{ $game->release_date ? \Illuminate\Support\Carbon::parse($game->release_date)->format('F j, Y') : 'TBA' }}</p>
            </section>

            <section class="showcase-section" aria-label="Developer and publisher">
                <h3>Developer / Publisher</h3>
                <p>Developer ID: {{ $game->developer_id ?? 'N/A' }}</p>
                <p>Publisher ID: {{ $game->publisher_id ?? 'N/A' }}</p>
            </section>

            <section class="showcase-section" aria-label="Genres and tags">
                <h3>Genres / Tags</h3>
                <p>Placeholder: Genres and tags are not implemented yet.</p>
            </section>
        </aside>
    </section>

    <section class="pricing-section" aria-label="Pricing section placeholder">
        <div class="section-heading-wrap">
            <h2>Section 2: Pricing Placeholder</h2>
            <p>This section is a placeholder for a future <strong>game_pricings</strong> table integration.</p>
        </div>

        <div class="pricing-card">
            <div class="price-block" aria-label="Current pricing preview">
                <p class="price-label">Store Price</p>
                @if ($hasDiscount && $discountedPrice !== null)
                    <div class="price-stack">
                        <span class="price-old">{{ $priceCurrency }} {{ number_format((float) $basePrice, 2) }}</span>
                        <span class="price-new">{{ $priceCurrency }} {{ number_format((float) $discountedPrice, 2) }}</span>
                        @if ($discountPercent !== null)
                            <span class="price-discount">-{{ (int) $discountPercent }}%</span>
                        @endif
                    </div>
                @else
                    <div class="price-stack">
                        <span class="price-new">{{ $priceCurrency }} {{ number_format((float) $basePrice, 2) }}</span>
                        <span class="price-tag">No Discount Active</span>
                    </div>
                @endif
                @if (!empty($pricing['pricing_id']))
                    <p class="metric-sub">Pricing row ID: {{ $pricing['pricing_id'] }}</p>
                @endif
            </div>

            <div class="pricing-actions" aria-label="Shopping actions placeholder">
                <button type="button" class="btn-cart" data-game-id="{{ $game->id }}" data-pricing-id="{{ $pricing['pricing_id'] ?? '' }}">Add To Cart</button>
                <p class="hint-text">Planned: button will post the selected pricing row to the cart flow.</p>
            </div>
        </div>
    </section>

    <section class="reviews-section" aria-label="Game reviews and analytics section">
        <div class="section-heading-wrap">
            <h2>Section 3: Player Reviews</h2>
            <p>Steam-style lightweight analytics from <strong>game_reviews</strong> seed data.</p>
        </div>

        <div class="reviews-analytics">
            <article class="metric-card">
                <p class="metric-label">Review Summary</p>
                <p class="metric-value">{{ $reviewSummary ?? 'No user reviews yet' }}</p>
                <p class="metric-sub">{{ $totalReviews ?? 0 }} total reviews</p>
            </article>

            <article class="metric-card">
                <p class="metric-label">Recommended</p>
                <p class="metric-value">{{ $recommendedPercent ?? 0 }}%</p>
                <div class="recommend-meter" role="img" aria-label="{{ $recommendedPercent ?? 0 }} percent recommended">
                    <span style="width: {{ $recommendedPercent ?? 0 }}%;"></span>
                </div>
                <p class="metric-sub">{{ $recommendedCount ?? 0 }} positive / {{ ($totalReviews ?? 0) - ($recommendedCount ?? 0) }} negative</p>
            </article>
        </div>

        <div class="review-list">
            @if ($reviewCollection->isNotEmpty())
                @foreach ($reviewCollection->take(4) as $review)
                    <article class="review-item">
                        <header>
                            <strong>{{ optional($review->getUser)->name ?? 'Anonymous Player' }}</strong>
                            <span class="review-badge {{ $review->is_recommended ? 'is-positive' : 'is-negative' }}">
                                {{ $review->is_recommended ? 'Recommended' : 'Not Recommended' }}
                            </span>
                        </header>
                        <p>{{ $review->review_content ?: 'No written comment provided.' }}</p>
                        <footer>
                            <span>{{ (int) ($review->hours_played ?? 0) }} hrs played</span>
                            <span>{{ (int) ($review->helpful_votes ?? 0) }} found this helpful</span>
                        </footer>
                    </article>
                @endforeach
            @else
                <p class="empty-note">No review rows found yet. Seed using: php artisan db:seed --class=GameReviewSeeder</p>
            @endif
        </div>
    </section>
</main>
@endsection
