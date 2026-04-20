@php
	$featuredRows = collect($featuredGames ?? \App\Models\Game::query()
		->where('is_featured', true)
		->latest('id')
		->take(3)
		->get());

	$displayUserName = strtoupper(optional(auth()->user())->name ?? 'USER_NAME');
@endphp

<nav class="steam-nav" id="steamNav" aria-label="Main navigation">
	<div class="steam-nav-inner">
		<div class="steam-nav-upper">
			<a class="steam-nav-link" href="{{ route('store.index') }}">Store</a>
			<a class="steam-nav-link" href="{{ route('library.libraryPage') }}">Library</a>
			<!-- dropdown profile menu -->
			<div class="steam-profile-menu-dropdown" id="profileMenuDropdown">
				<span class="steam-profile-label">{{ $displayUserName }}</span>
				<div class="steam-profile-menu-panel" role="menu" aria-label="Profile menu">
					<button class="steam-nav-user" onclick="window.location.href='{{ route('profile') }}'">Profile</button>
					<form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to log out?');">
						@csrf
						<button type="submit" class="nav-logout">Log Out</button>
					</form>
				</div>
			</div>
		</div>

		<div class="steam-nav-lower-wrap" id="steamNavLowerWrap">
			<div class="steam-nav-lower">
				<button type="button" class="steam-nav-btn" id="recommendationsBtn" aria-expanded="false" aria-controls="recommendationsDropdown">
					Recommendations
				</button>
				<a class="steam-nav-link" href=#>Categories</a>

				<form class="steam-nav-search" role="search" action="#" method="get">
					<input type="search" name="q" placeholder="search" aria-label="Search games">
				</form>

				<a class="steam-nav-link" href=#>Wishlist</a>
				<a class="steam-nav-link" href="{{ route('cart.show') }}">Cart</a>
			</div>

			<div class="steam-nav-dropdown" id="recommendationsDropdown" role="region" aria-label="Recommended for you">
				<p class="steam-nav-dropdown-title">Recommended for you</p>

				@if ($featuredRows->isNotEmpty())
					<div class="steam-featured-list">
						@foreach ($featuredRows as $featured)
							<a class="steam-featured-row" href="{{ route('games.show', ['game' => $featured]) }}">
								<img src="{{ $featured->cover_image ?: 'https://via.placeholder.com/460x215?text=Game+Image' }}" alt="{{ $featured->title }} cover image">
								<div class="steam-featured-meta">
									<strong>{{ $featured->title }}</strong>
									<span>Featured game</span>
								</div>
								<span class="steam-featured-price">${{ number_format((float) ($featured->price ?? 0), 2) }}</span>
							</a>
						@endforeach
					</div>
				@else
					<p class="steam-featured-empty">No featured games found yet.</p>
				@endif
			</div>
		</div>
	</div>
</nav>

<div class="steam-nav-spacer" aria-hidden="true"></div>
