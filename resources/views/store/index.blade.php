@extends('layouts.app')

@section('title', 'Game Store | Home')
@section('main-class', '')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/store/index.css') }}">
@endpush

@section('content')
<main class="store-page">
    <section>
        <form action="{{ route('store.index') }}" method="GET">
            <input type="text" name="search" placeholder="Search games..." 
                    >
            <button type="submit">Search</button>
        </form>
    </section>

    <section>
        <h2>Featured Games</h2>
        <div class="featured-grid">
            @foreach($featuredGames as $game)
                <div class="featured-card">
                    <img src="{{ $game->cover_image }}">
                    <div class="featured-overlay">
                        <h3>{{ $game->title }}</h3>
                        <a href="{{ route('games.show', $game->id) }}">View Game</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section>
        <h2>All Games</h2>
        <div class="all-grid">
            @foreach($allGames as $game)
                <a href="{{ route('games.show', $game->id) }}" class="all-item">
                    <img src="{{ $game->cover_image }}">
                    <p>{{ $game->title }}</p>
                    <p class="price">${{ $game->price }}</p>
                </a>
            @endforeach
        </div>
    </section>
</main>

@endsection
