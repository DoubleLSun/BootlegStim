@extends('layouts.app')

@section('title', 'Purchase Successful - BootlegStim')

@section('styles')
<style>
    .success-page {
        background: #1b2838;
        min-height: calc(100vh - 60px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        font-family: 'Motiva Sans', Arial, sans-serif;
    }

    .success-card {
        background: #16202d;
        border: 1px solid #2a3f5f;
        border-radius: 4px;
        max-width: 560px;
        width: 100%;
        overflow: hidden;
    }

    .success-banner {
        background: linear-gradient(135deg, #1a3a2a 0%, #243d2e 100%);
        border-bottom: 1px solid #2a3f5f;
        padding: 30px 30px 24px;
        text-align: center;
    }

    .success-icon {
        font-size: 56px;
        margin-bottom: 12px;
        display: block;
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes popIn {
        from { transform: scale(0); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .success-title {
        font-size: 22px;
        font-weight: 300;
        color: #d2e885;
        margin: 0 0 8px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .success-subtitle {
        font-size: 13px;
        color: #8f98a0;
        margin: 0;
    }

    .success-body { padding: 24px 30px; }

    .order-ref {
        background: #1b2838;
        border: 1px solid #2a3f5f;
        border-radius: 3px;
        padding: 10px 14px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-ref-label {
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-ref-value {
        font-size: 13px;
        color: #57cbde;
        font-family: monospace;
        font-weight: 700;
    }

    .purchased-games { margin-bottom: 24px; }

    .purchased-game-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .purchased-game-row:last-child { border-bottom: none; }

    .pg-thumb {
        width: 72px;
        height: 34px;
        object-fit: cover;
        border-radius: 2px;
        background: #2a3f5f;
    }

    .pg-name {
        flex: 1;
        font-size: 13px;
        color: #c6d4df;
    }

    .pg-price {
        font-size: 13px;
        color: #8f98a0;
    }

    .success-actions {
        display: flex;
        gap: 10px;
    }

    .btn-library {
        flex: 1;
        padding: 12px;
        background: linear-gradient(to bottom, #75b022 5%, #588a1b 95%);
        border: none;
        border-radius: 3px;
        color: #d2e885;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: filter 0.15s;
    }
    .btn-library:hover { filter: brightness(1.1); }

    .btn-store {
        flex: 1;
        padding: 12px;
        background: #2a3f5f;
        border: 1px solid #1b2838;
        border-radius: 3px;
        color: #c6d4df;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: background 0.15s;
    }
    .btn-store:hover { background: #3d6f8e; }

    .confetti-bar {
        height: 4px;
        background: linear-gradient(to right, #57cbde, #75b022, #d2e885, #57cbde);
        background-size: 200% 100%;
        animation: shimmer 2s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endsection

@section('content')
<div class="success-page">
    <div class="success-card">
        <div class="confetti-bar"></div>

        <div class="success-banner">
            <span class="success-icon">🎮</span>
            <h1 class="success-title">Purchase Successful!</h1>
            <p class="success-subtitle">Your games have been added to your library.</p>
        </div>

        <div class="success-body">
            <div class="order-ref">
                <span class="order-ref-label">Order Reference</span>
                <span class="order-ref-value">#{{ $order->reference ?? strtoupper(substr(md5(time()), 0, 12)) }}</span>
            </div>

            <div class="purchased-games">
                @foreach($purchasedGames as $game)
                <div class="purchased-game-row">
                    <img class="pg-thumb"
                         src="{{ $game->thumbnail_url ?? asset('img/placeholder_game.jpg') }}"
                         alt="{{ $game->name }}">
                    <span class="pg-name">{{ $game->name }}</span>
                    <span class="pg-price">RM {{ number_format($game->price, 2) }}</span>
                </div>
                @endforeach
            </div>

            <div style="text-align:center;margin-bottom:20px;padding:12px;background:#1b2838;border-radius:3px;">
                <div style="font-size:12px;color:#8f98a0;margin-bottom:4px;">Total Charged</div>
                <div style="font-size:24px;font-weight:700;color:#c6d4df;">
                    RM {{ number_format($order->total ?? 0, 2) }}
                </div>
            </div>

            <div class="success-actions">
                <a href="{{ route('library.index') }}" class="btn-library">Go to Library</a>
                <a href="{{ route('products.index') }}" class="btn-store">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
