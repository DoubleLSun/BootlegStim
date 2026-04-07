<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $game->title }} | Product Page</title>
	<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
	@php
		$mediaCollection = collect($mediaItems ?? []);
		$selectedImage = $selectedImage
			?? optional($mediaCollection->firstWhere('is_cover', true))->url
			?? optional($mediaCollection->first())->url
			?? $game->cover_image
			?? 'https://via.placeholder.com/1200x675?text=No+Image';
		$activeThumbId = $activeThumbId ?? null;
	@endphp

	<main class="page-wrap">
		<h1 class="page-title">{{ $game->title }}</h1>
		<p class="page-subtitle">{{ $game->description }}</p>

		<section class="media-section" aria-label="Game media gallery and showcase">
			<article class="primary-gallery">
				<!-- Selected image preview -->
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
					<img src="{{ $selectedImage }}" alt="Main cover image for {{ $game->title }}">
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
	</main>
</body>
</html>
