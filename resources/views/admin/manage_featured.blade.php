@extends('layouts.app')

@section('title', 'Admin | Content Control')

@push('styles')
<link href="{{ asset('css/admin/manageFeatured.css') }}" rel="stylesheet">
@endpush

@section('content')
<div
    class="admin-container"
    id="admin-page-root"
    data-game-create-route="{{ route('admin.games.create') }}"
    data-genre-create-route="{{ route('admin.genres.create') }}"
>
    <div class="header">
        <div class="title-group">
            <h1>Admin Content Control</h1>
            <p>Manage games, genres, pricing tags, and media from one panel.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-primary" data-open-modal="gameModal" data-mode="create">+ New Game</button>
            <button type="button" class="btn" data-open-modal="genreModal" data-mode="create">+ New Genre</button>
            <a href="{{ route('store.index') }}" class="btn">Exit Admin</a>
        </div>
    </div>

    @if(session('success'))
        <div class="status-box">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Game Management</h2>
            <span style="color:var(--text-muted);font-size:13px;">No hard delete. Use delist to hide publicly.</span>
        </div>

        <div class="games-grid">
            @forelse($allGames as $game)
                <article class="game-card is-collapsed" data-game-card>
                    <button type="button" class="game-bar" data-game-toggle aria-expanded="false">
                        <span class="game-bar-title">{{ $game->title }}</span>
                        <span class="game-bar-toggle" aria-hidden="true">+</span>
                    </button>

                    <div class="game-body" data-game-body hidden>
                        <div class="game-top">
                            <div class="game-info">
                                @if($game->cover_image)
                                    <img class="game-thumb" src="{{ $game->cover_image }}" alt="{{ $game->title }}">
                                @else
                                    <div class="game-thumb" style="display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:11px;">No Cover</div>
                                @endif
                                <div class="game-meta">
                                    <h3>{{ $game->title }}</h3>
                                    <p>Game ID: {{ $game->id }} | Dev: {{ $game->developer_id }} | Publisher: {{ $game->publisher_id }}</p>
                                    <p>Genres:
                                        @forelse($game->genres as $genre)
                                            <span class="pill pill-muted">{{ $genre->name }}</span>
                                        @empty
                                            <span class="pill pill-muted">None</span>
                                        @endforelse
                                    </p>
                                    <div>
                                        <span class="pill {{ $game->is_featured ? 'pill-ok' : 'pill-muted' }}">{{ $game->is_featured ? 'Featured' : 'Not Featured' }}</span>
                                        <span class="pill {{ $game->is_delisted ? 'pill-danger' : 'pill-ok' }}">{{ $game->is_delisted ? 'Delisted' : 'Listed' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="inline-wrap">
                                <button
                                    type="button"
                                    class="btn"
                                    data-open-modal="gameModal"
                                    data-mode="edit"
                                    data-action="{{ route('admin.games.update', $game) }}"
                                    data-title="{{ $game->title }}"
                                    data-description="{{ $game->description }}"
                                    data-price="{{ $game->price }}"
                                    data-release_date="{{ optional($game->release_date)->format('Y-m-d') }}"
                                    data-developer_id="{{ $game->developer_id }}"
                                    data-publisher_id="{{ $game->publisher_id }}"
                                    data-cover_image="{{ $game->cover_image }}"
                                    data-genre_ids="{{ $game->genres->pluck('id')->implode(',') }}"
                                >Edit Game</button>

                                <form action="{{ route('admin.toggle', $game) }}" method="POST">
                                    @csrf
                                    <button class="btn" type="submit">Toggle Featured</button>
                                </form>

                                <form action="{{ route('admin.games.toggle-delist', $game) }}" method="POST">
                                    @csrf
                                    <button class="btn {{ $game->is_delisted ? '' : 'btn-danger' }}" type="submit">
                                        {{ $game->is_delisted ? 'Relist' : 'Delist' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="game-body-grid">
                            <div class="group pricing-segment">
                                <h4>Select Price Tag and Display</h4>
                                <form class="pricing-tag-form" action="{{ route('admin.games.pricing-tag', $game) }}" method="POST">
                                    @csrf
                                    <label class="pricing-tag-switch">
                                        <span>Use pricing tag on public page</span>
                                        <input class="admin-checkbox" type="checkbox" name="use_pricing_tag" value="1" {{ $game->use_pricing_tag ? 'checked' : '' }}>
                                    </label>

                                    <div class="pricing-tag-row">
                                        <select class="selection-form" name="selected_pricing_id">
                                            <option value="">No selected pricing</option>
                                            @foreach($game->getGamePricing as $pricing)
                                                <option class="selection-option" value="{{ $pricing->id }}" {{ (int) $game->selected_pricing_id === (int) $pricing->id ? 'selected' : '' }}>
                                                    #{{ $pricing->id }} {{ $pricing->currency }} {{ number_format((float) $pricing->price, 2) }}
                                                    @if(!is_null($pricing->discount_percentage))
                                                        ({{ (float) $pricing->discount_percentage }}% off)
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn">Save Pricing Tag</button>
                                    </div>
                                </form>
                            </div>

                            <div class="group pricing-segment">
                                <h4>Create New Price Tag</h4>
                                <form class="pricing-create-form" action="{{ route('admin.pricings.create', $game) }}" method="POST">
                                    @csrf
                                    <div class="form-grid">
                                        <input type="number" name="price" step="0.01" min="0" placeholder="Base Price" required>
                                        <input type="number" name="discount_percentage" step="0.01" min="0" max="100" placeholder="Discount %">
                                        <input type="number" name="discounted_price" step="0.01" min="0" placeholder="Discounted Price">
                                        <input type="text" name="currency" value="USD" maxlength="3" required>
                                    </div>
                                    <div class="inline-wrap pricing-create-actions">
                                        <button type="submit" class="btn btn-primary">Add Pricing</button>
                                    </div>
                                </form>
                            </div>

                            <div class="group pricing-segment pricing-list-segment">
                                <h4>Existing Pricing Entries</h4>
                                <div class="media-list">
                                    @forelse($game->getGamePricing as $pricing)
                                        <div class="media-row pricing-row">
                                            <form class="pricing-edit-form" action="{{ route('admin.pricings.update', [$game, $pricing]) }}" method="POST">
                                                @csrf
                                                <div class="form-grid pricing-edit-grid">
                                                    <input type="number" name="price" step="0.01" min="0" value="{{ $pricing->price }}" required>
                                                    <input type="number" name="discount_percentage" step="0.01" min="0" max="100" value="{{ $pricing->discount_percentage }}" placeholder="Discount %">
                                                    <input type="number" name="discounted_price" step="0.01" min="0" value="{{ $pricing->discounted_price }}" placeholder="Discounted Price">
                                                    <input type="text" name="currency" value="{{ $pricing->currency }}" maxlength="3" required>
                                                </div>
                                                <div class="inline-wrap pricing-edit-actions">
                                                    <button type="submit" class="btn">Update</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('admin.pricings.delete', [$game, $pricing]) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-danger" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    @empty
                                        <div style="color:var(--text-muted);font-size:13px;">No pricing entries yet.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="group media-segment">
                            <h4>Media Manager</h4>
                            <form action="{{ route('admin.media.create', $game) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-grid-2">
                                    <textarea name="image_links" placeholder="Image URLs (one per line)"></textarea>
                                    <textarea name="video_links" placeholder="Video URLs (one per line)"></textarea>
                                </div>
                                <div class="inline-wrap" style="margin-top:8px;">
                                    <input style="max-width:360px;" type="file" name="image_files[]" multiple accept="image/*" class="media-file-input" data-preview-target="preview-{{ $game->id }}">
                                    <button class="btn" type="submit">Add Media</button>
                                </div>
                                <div id="preview-{{ $game->id }}" class="preview-grid"></div>
                            </form>

                            <div class="media-list" style="margin-top:8px;">
                                @forelse($game->media as $media)
                                    <div class="media-row">
                                        <div class="inline-wrap" style="align-items:center;">
                                            @if($media->type === 'image')
                                                <img class="media-preview" src="{{ $media->thumbnail_url ?: $media->url }}" alt="media">
                                            @else
                                                <span class="pill pill-muted">VIDEO</span>
                                            @endif
                                            <span style="font-size:12px;color:var(--text-muted);max-width:380px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $media->url }}</span>
                                        </div>
                                        <form action="{{ route('admin.media.delete', [$game, $media]) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger" type="submit">Remove</button>
                                        </form>
                                    </div>
                                @empty
                                    <div style="color:var(--text-muted);font-size:13px;">No media entries yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div style="color:var(--text-muted);">No games found.</div>
            @endforelse
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <h2 class="section-title">Genre Management</h2>
            <span style="color:var(--text-muted);font-size:13px;">Visible genres appear in top navigation and search filters.</span>
        </div>
        <div class="genre-scroll-wrap" style="padding: 0 16px 16px; overflow:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Games</th>
                        <th>Visible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allGenres as $genre)
                        <tr>
                            <td>{{ $genre->name }}</td>
                            <td>{{ $genre->slug }}</td>
                            <td>{{ $genre->games_count }}</td>
                            <td>{{ $genre->display_flag ? 'Yes' : 'No' }}</td>
                            <td>
                                <div class="inline-wrap">
                                    <button
                                        type="button"
                                        class="btn"
                                        data-open-modal="genreModal"
                                        data-mode="edit"
                                        data-action="{{ route('admin.genres.update', $genre) }}"
                                        data-name="{{ $genre->name }}"
                                        data-slug="{{ $genre->slug }}"
                                        data-description="{{ $genre->description }}"
                                        data-display_flag="{{ $genre->display_flag ? 1 : 0 }}"
                                    >Edit</button>

                                    <form action="{{ route('admin.genres.toggle-display', $genre) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn">Toggle Visibility</button>
                                    </form>

                                    <form action="{{ route('admin.genres.delete', $genre) }}" method="POST" onsubmit="return confirm('Delete this genre?')">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="color:var(--text-muted);">No genres found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="modal" id="gameModal" aria-hidden="true">
        <div class="modal-card">
            <div class="modal-head">
                <strong id="gameModalTitle">Create Game</strong>
                <button type="button" class="btn" data-close-modal="gameModal">Close</button>
            </div>
            <div class="modal-body">
                <form id="gameForm" action="{{ route('admin.games.create') }}" method="POST">
                    @csrf
                    <div class="form-grid-2">
                        <div>
                            <label>Title</label>
                            <input type="text" name="title" id="game-title" required>
                        </div>
                        <div>
                            <label>Cover Image URL</label>
                            <input type="url" name="cover_image" id="game-cover_image">
                        </div>
                    </div>

                    <div style="margin-top:8px;">
                        <label>Description</label>
                        <textarea name="description" id="game-description" required></textarea>
                    </div>

                    <div class="form-grid" style="margin-top:8px;">
                        <div>
                            <label>Price</label>
                            <input type="number" step="0.01" min="0" name="price" id="game-price" required>
                        </div>
                        <div>
                            <label>Release Date</label>
                            <input type="date" name="release_date" id="game-release_date" required>
                        </div>
                        <div>
                            <label>Developer ID</label>
                            <input type="number" min="1" name="developer_id" id="game-developer_id" required>
                        </div>
                        <div>
                            <label>Publisher ID</label>
                            <input type="number" min="1" name="publisher_id" id="game-publisher_id" required>
                        </div>
                    </div>

                    <div style="margin-top:8px;">
                        <label>Genres</label>
                        <select id="game-genre_ids" name="genre_ids[]" multiple size="6">
                            @foreach($allGenres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="inline-wrap" style="margin-top:12px;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">Save Game</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="genreModal" aria-hidden="true">
        <div class="modal-card" style="max-width:620px;">
            <div class="modal-head">
                <strong id="genreModalTitle">Create Genre</strong>
                <button type="button" class="btn" data-close-modal="genreModal">Close</button>
            </div>
            <div class="modal-body">
                <form id="genreForm" action="{{ route('admin.genres.create') }}" method="POST">
                    @csrf
                    <div class="form-grid-2">
                        <div>
                            <label>Name</label>
                            <input type="text" name="name" id="genre-name" required>
                        </div>
                        <div>
                            <label>Slug</label>
                            <input type="text" name="slug" id="genre-slug" required>
                        </div>
                    </div>
                    <div style="margin-top:8px;">
                        <label>Description</label>
                        <textarea name="description" id="genre-description"></textarea>
                    </div>
                    <div style="margin-top:8px;">
                        <label style="display:flex;align-items:center;gap:8px;">
                            <input type="checkbox" name="display_flag" id="genre-display_flag" value="1" checked>
                            <span>Display in public navigation/search</span>
                        </label>
                    </div>
                    <div class="inline-wrap" style="margin-top:12px;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">Save Genre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/manageFeatured.js') }}" defer></script>
@endpush
