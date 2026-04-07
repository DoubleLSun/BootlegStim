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
		$defaultMedia = $mediaCollection->firstWhere('is_cover', true)
			?? $mediaCollection->first();
		$defaultImage = optional($defaultMedia)->url ?? $game->cover_image ?? 'https://via.placeholder.com/1200x675?text=No+Image';
	@endphp

	<main class="page-wrap">
		<h1 class="page-title">{{ $game->title }}</h1>
		<p class="page-subtitle">{{ $game->description }}</p>

		<section class="media-section" aria-label="Game media gallery and showcase">
			<article class="primary-gallery">
				<div class="selected-image-shell" id="selectedImageShell">
					<img
						id="selectedImage"
						src="{{ $defaultImage }}"
						alt="Selected media preview for {{ $game->title }}"
					>
					<span class="selected-state" id="selectedState">Selected</span>
				</div>

				@if ($mediaCollection->isNotEmpty())
					<div class="thumb-strip" id="thumbStrip" aria-label="Scrollable image options">
						@foreach ($mediaCollection as $media)
							<button
								type="button"
								class="thumb-button"
								data-image-url="{{ $media->url }}"
								data-thumb-id="{{ $media->id }}"
								aria-label="Select image {{ $loop->iteration }}"
							>
								<img src="{{ $media->thumbnail_url ?? $media->url }}" alt="Game thumbnail {{ $loop->iteration }}">
							</button>
						@endforeach
					</div>
				@else
					<p class="empty-note">No images in the game_media table for this game yet.</p>
				@endif
			</article>

			<aside class="showcase-panel" aria-label="Small horizontal image showcase">
				<h2 class="showcase-title">Image Showcase</h2>

				@if ($mediaCollection->isNotEmpty())
					<div class="showcase-track">
						@foreach ($mediaCollection as $media)
							<figure class="showcase-item" style="animation-delay: {{ $loop->index * 70 }}ms;">
								<img src="{{ $media->thumbnail_url ?? $media->url }}" alt="Showcase image {{ $loop->iteration }}">
								<span>{{ $game->title }} - #{{ $loop->iteration }}</span>
							</figure>
						@endforeach
					</div>
				@else
					<p class="empty-note">No showcase items available.</p>
				@endif
			</aside>
		</section>
	</main>

	<script>
		(function () {
			const selectedImage = document.getElementById('selectedImage');
			const selectedShell = document.getElementById('selectedImageShell');
			const thumbButtons = Array.from(document.querySelectorAll('.thumb-button'));

			if (!selectedImage || thumbButtons.length === 0) {
				return;
			}

			const defaultImageUrl = selectedImage.getAttribute('src');
			let activeThumbId = null;

			thumbButtons.forEach((button) => {
				button.addEventListener('click', () => {
					const imageUrl = button.getAttribute('data-image-url');
					const thumbId = button.getAttribute('data-thumb-id');
					const isSameActive = activeThumbId === thumbId;

					thumbButtons.forEach((item) => item.classList.remove('is-active'));

					if (isSameActive) {
						activeThumbId = null;
						selectedImage.setAttribute('src', defaultImageUrl);
						selectedShell.classList.remove('is-highlighted');
						return;
					}

					activeThumbId = thumbId;
					selectedImage.setAttribute('src', imageUrl || defaultImageUrl);
					button.classList.add('is-active');
					selectedShell.classList.add('is-highlighted');
				});
			});
		})();
	</script>
</body>
</html>
