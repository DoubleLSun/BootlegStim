@extends('layouts.app')

@section('title', 'Search | BootlegStim')
@section('main-class', '')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/search/searchPage.css') }}">
@endpush

@section('content')
<div class="search-page-wrap">
	<header class="search-header">
		<h1>Search</h1>
		<form method="GET" action="{{ route('search.index') }}" class="search-form-inline">
			<input type="search" name="q" value="{{ $queryText }}" placeholder="Search by game title..." aria-label="Search by title">
			@foreach($selectedGenreIds as $genreId)
				<input type="hidden" name="genres[]" value="{{ $genreId }}">
			@endforeach
			<button type="submit">Search</button>
		</form>
	</header>

	<div class="search-layout">
		<aside class="search-filters">
			<h2>Filter by Genre</h2>
			<form method="GET" action="{{ route('search.index') }}" id="genreFilterForm">
				<input type="hidden" name="q" value="{{ $queryText }}">
				<input type="search" id="genreFilterSearch" placeholder="Filter genres..." aria-label="Filter genre options">
				<div class="genre-filter-list" id="genreFilterList">
					@forelse($visibleGenres as $genre)
						<label class="genre-filter-option" data-name="{{ strtolower($genre->name) }}">
							<input
								type="checkbox"
								name="genres[]"
								value="{{ $genre->id }}"
								{{ $selectedGenreIds->contains($genre->id) ? 'checked' : '' }}
							>
							<span>{{ $genre->name }}</span>
							<small>{{ $genre->games_count }}</small>
						</label>
					@empty
						<p class="empty-text">No visible genres available.</p>
					@endforelse
				</div>
				<button type="submit" class="apply-filter-btn">Apply Filters</button>
			</form>
		</aside>

		<section class="search-results">
			<div class="results-topbar">
				<p>
					@if($results->total() > 0)
						{{ $results->total() }} result{{ $results->total() === 1 ? '' : 's' }} found
					@else
						No results found
					@endif
				</p>

				<div class="selected-filter-chips">
					@foreach($selectedGenres as $selectedGenre)
						@php
							$nextGenres = $selectedGenreIds->reject(fn($id) => (int) $id === (int) $selectedGenre->id)->values()->all();
						@endphp
						<a class="chip" href="{{ route('search.index', ['q' => $queryText, 'genres' => $nextGenres]) }}" title="Remove filter">
							{{ $selectedGenre->name }} ×
						</a>
					@endforeach
				</div>
			</div>

			@if($results->isNotEmpty())
				<div class="result-list">
					@foreach($results as $game)
						@php
							$pricingRows = $game->getGamePricing;
							$selectedPricing = null;
							if ($game->use_pricing_tag && !empty($game->selected_pricing_id)) {
								$selectedPricing = $pricingRows->firstWhere('id', (int) $game->selected_pricing_id);
							}
							$pricingRow = $selectedPricing ?: $pricingRows->first();
							$currency = $pricingRow->currency ?? 'USD';
							$basePrice = (float) ($pricingRow->price ?? $game->price);
							$hasDiscount = $pricingRow
								&& !is_null($pricingRow->discount_percentage)
								&& !is_null($pricingRow->discounted_price)
								&& (float) $pricingRow->discounted_price < (float) $pricingRow->price;
							$discountPct = $hasDiscount ? (float) $pricingRow->discount_percentage : null;
							$discountedPrice = $hasDiscount ? (float) $pricingRow->discounted_price : null;
						@endphp
						<a class="result-row" href="{{ route('games.show', ['game' => $game]) }}">
							<img src="{{ $game->cover_image ?: 'https://via.placeholder.com/160x90?text=Game' }}" alt="{{ $game->title }} cover">
							<div class="result-row-main">
								<h3>{{ $game->title }}</h3>
							</div>
							<div class="result-row-price">
								@if($hasDiscount)
									<span class="discount-badge">-{{ (int) round($discountPct) }}%</span>
									<div class="price-stack">
										<span class="price-original">{{ $currency }} {{ number_format($basePrice, 2) }}</span>
										<span class="price-final">{{ $currency }} {{ number_format($discountedPrice, 2) }}</span>
									</div>
								@else
									<span class="price-final">{{ $currency }} {{ number_format($basePrice, 2) }}</span>
								@endif
							</div>
						</a>
					@endforeach
				</div>

				<div class="pagination-wrap">
					{{ $results->links() }}
				</div>
			@else
				<div class="no-result-box">
					<h3>No matching games</h3>
					<p>Try another title keyword, or remove one of your selected genre filters.</p>
				</div>
			@endif
		</section>
	</div>
</div>

<script>
	(function () {
		const genreFilterSearch = document.getElementById('genreFilterSearch');
		if (!genreFilterSearch) return;

		genreFilterSearch.addEventListener('input', function () {
			const q = this.value.toLowerCase().trim();
			document.querySelectorAll('.genre-filter-option').forEach((option) => {
				option.style.display = option.dataset.name.includes(q) ? '' : 'none';
			});
		});
	})();
</script>
@endsection
