@extends('layouts.app')

@section('main-class', '')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment/paymentPage.css') }}">
@endpush

@section('content')

<div class="payment-page">
    <div class="payment-container">

        <h1 class="payment-page-title">Complete Your Purchase</h1>

        {{-- Checkout Steps --}}
        <div class="checkout-steps">
            <div class="step done">
                <span class="step-num">✓</span>
                <span>Cart</span>
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <span class="step-num">2</span>
                <span>Payment</span>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <span class="step-num">3</span>
                <span>Confirmation</span>
            </div>
        </div>

        <div class="payment-layout">

            {{-- LEFT: ORDER SUMMARY --}}
            <div class="order-summary">

                {{-- Items --}}
                <div class="section-box">
                    <div class="section-box-header">Order Summary</div>
                    <div class="section-box-body">

                        @forelse($cartItems as $item)
                        @php
                            $pricing = $item->gamePricing;
                            $game = $pricing ? $pricing->getGame : null;
                            $coverMedia = $game ? $game->media->firstWhere('is_cover', true) : null;
                            $thumbnail = optional($coverMedia)->thumbnail_url
                                ?? optional($coverMedia)->url
                                ?? ($game->cover_image ?? asset('img/placeholder_game.jpg'));
                            $gameTitle = $game->title ?? 'Unknown Game';
                        @endphp
                        <div class="order-item">
                            <img class="order-item-img"
                                 src="{{ $thumbnail }}"
                                 alt="{{ $gameTitle }}">
                            <div class="order-item-info">
                                <p class="order-item-name">{{ $gameTitle }}</p>
                                <span class="order-item-type">Standard Edition &mdash; Digital</span>
                            </div>
                            <div class="order-item-price">
                                @if($pricing && (float) $pricing->discount_percentage > 0)
                                    <span style="text-decoration:line-through;color:#8f98a0;font-size:12px;font-weight:400;display:block;">
                                        RM {{ number_format($item->price / max(0.01, (1 - ((float) $pricing->discount_percentage / 100))), 2) }}
                                    </span>
                                @endif
                                RM {{ number_format($item->price, 2) }}
                            </div>
                            <button class="order-item-remove"
                                    onclick="removeItem({{ $item->id }})"
                                    title="Remove">×</button>
                        </div>
                        @empty
                        <p style="color:#8f98a0;font-size:13px;text-align:center;padding:16px 0;">Your cart is empty.</p>
                        @endforelse

                        {{-- Price Breakdown --}}
                        @if($cartItems->isNotEmpty())
                        <div style="margin-top:14px;">
                            <div class="discount-row">
                                <span>Subtotal</span>
                                <span style="color:#c6d4df;">RM {{ number_format($subtotal, 2) }}</span>
                            </div>

                            @if($discount > 0)
                            <div class="discount-row">
                                <span>Discount <span class="discount-tag">-{{ $discountPercent }}%</span></span>
                                <span class="discount-amount">-RM {{ number_format($discount, 2) }}</span>
                            </div>
                            @endif

                            @if($walletApplied > 0)
                            <div class="discount-row">
                                <span>BootlegStim Wallet</span>
                                <span class="discount-amount">-RM {{ number_format($walletApplied, 2) }}</span>
                            </div>
                            @endif

                            <div class="total-row">
                                <span class="total-label">Total</span>
                                <span class="total-value">RM {{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Promo Code --}}
                <div class="section-box">
                    <div class="section-box-header">Promo Code</div>
                    <div class="section-box-body">
                        <form action="{{ route('payment.promo') }}" method="POST" onsubmit="return applyPromo(event)">
                            @csrf
                            <div class="promo-input-wrap">
                                <input class="promo-input" type="text" name="promo_code"
                                       placeholder="Enter promo code"
                                       value="{{ session('promo_code') ?? '' }}">
                                <button type="submit" class="btn-secondary">Apply</button>
                            </div>
                            @if(session('promo_error'))
                                <p style="color:#e85c4a;font-size:11px;margin:6px 0 0;">{{ session('promo_error') }}</p>
                            @endif
                            @if(session('promo_success'))
                                <p style="color:#d2e885;font-size:11px;margin:6px 0 0;">{{ session('promo_success') }}</p>
                            @endif
                        </form>
                    </div>
                </div>

            </div>

            {{-- RIGHT: PAYMENT FORM --}}
            <div class="payment-form-col">

                {{-- Wallet --}}
                <div class="wallet-box">
                    <div class="wallet-icon">💰</div>
                    <div class="wallet-info">
                        <div class="wallet-label">BootlegStim Wallet</div>
                        <div class="wallet-balance">RM {{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</div>
                    </div>
                    <button class="wallet-use-btn" id="walletBtn" onclick="toggleWallet()">
                        {{ $walletApplied > 0 ? 'Remove' : 'Use' }}
                    </button>
                </div>

                {{-- Payment Method Tabs --}}
                <div class="payment-tabs">
                    <button class="payment-tab active" onclick="switchTab('card', this)">💳 Card</button>
                    <button class="payment-tab" onclick="switchTab('paypal', this)">PayPal</button>
                    <button class="payment-tab" onclick="switchTab('mobile', this)">📱 e-Wallet</button>
                </div>

                <form id="paymentForm" action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="card">

                    {{-- Card Tab --}}
                    <div class="section-box tab-panel active" id="tab-card">
                        <div class="section-box-header">Card Details</div>
                        <div class="section-box-body">
                            <div class="card-icons">
                                <span class="card-icon visa">VISA</span>
                                <span class="card-icon mc">MC</span>
                                <span class="card-icon amex">AMEX</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Name on Card</label>
                                <input class="form-input" type="text" name="card_name"
                                       placeholder="John Doe" autocomplete="cc-name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Card Number</label>
                                <input class="form-input" type="text" name="card_number"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="19" autocomplete="cc-number"
                                       oninput="formatCard(this)">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Expiry Date</label>
                                    <input class="form-input" type="text" name="card_expiry"
                                           placeholder="MM / YY" maxlength="7"
                                           autocomplete="cc-exp"
                                           oninput="formatExpiry(this)">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">CVV</label>
                                    <input class="form-input" type="password" name="card_cvv"
                                           placeholder="•••" maxlength="4"
                                           autocomplete="cc-csc">
                                </div>
                            </div>

                            <div class="billing-agree">
                                <input type="checkbox" name="billing_agree" id="billingAgree" required>
                                <label for="billingAgree">
                                    I have read and agree to the
                                    <a href="#">Subscriber Agreement</a> and
                                    <a href="#">Refund Policy</a> of BootlegStim.
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- PayPal Tab --}}
                    <div class="section-box tab-panel" id="tab-paypal">
                        <div class="section-box-body">
                            <div class="paypal-info">
                                <div class="paypal-logo">
                                    <span>Pay</span><span>Pal</span>
                                </div>
                                <p class="paypal-desc">
                                    You will be redirected to PayPal to complete your purchase securely.
                                </p>
                                <div class="billing-agree" style="text-align:left;">
                                    <input type="checkbox" name="paypal_agree" id="paypalAgree" required>
                                    <label for="paypalAgree">
                                        I agree to the <a href="#">Subscriber Agreement</a>.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- e-Wallet Tab --}}
                    <div class="section-box tab-panel" id="tab-mobile">
                        <div class="section-box-header">Select e-Wallet</div>
                        <div class="section-box-body">
                            @php
                                $ewallets = [
                                    ['name' => 'Touch \'n Go eWallet', 'code' => 'tng', 'color' => '#00a0e9'],
                                    ['name' => 'GrabPay', 'code' => 'grabpay', 'color' => '#00b14f'],
                                    ['name' => 'Boost', 'code' => 'boost', 'color' => '#e21f26'],
                                    ['name' => 'MAE by Maybank', 'code' => 'mae', 'color' => '#f7a800'],
                                ];
                            @endphp

                            @foreach($ewallets as $ew)
                            <label style="display:flex;align-items:center;gap:12px;padding:10px;background:#2a3f5f;border-radius:3px;margin-bottom:8px;cursor:pointer;border:2px solid transparent;transition:border-color 0.15s;"
                                   onclick="selectEwallet(this, '{{ $ew['code'] }}')">
                                <input type="radio" name="ewallet" value="{{ $ew['code'] }}" style="display:none;">
                                <span style="width:10px;height:10px;border-radius:50%;background:{{ $ew['color'] }};flex-shrink:0;"></span>
                                <span style="font-size:13px;color:#c6d4df;">{{ $ew['name'] }}</span>
                            </label>
                            @endforeach

                            <div class="billing-agree">
                                <input type="checkbox" name="ewallet_agree" id="ewalletAgree" required>
                                <label for="ewalletAgree">
                                    I agree to the <a href="#">Subscriber Agreement</a>.
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn-purchase" onclick="showConfirm()">
                        Purchase &mdash; RM {{ number_format($total, 2) }}
                    </button>

                </form>

                <p class="purchase-note">
                    All transactions are secured and encrypted. By purchasing you agree to the BootlegStim Subscriber Agreement.
                </p>

                <div class="security-badges">
                    <span class="security-badge">🔒 SSL Secured</span>
                    <span class="security-badge">✓ PCI Compliant</span>
                    <span class="security-badge">🛡️ Fraud Protected</span>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Confirmation Modal --}}
<div class="confirm-overlay" id="confirmOverlay">
    <div class="confirm-modal">
        <h2>Confirm Purchase</h2>
        <p>
            You are about to be charged <strong>RM {{ number_format($total, 2) }}</strong>
            for {{ $cartItems->count() }} item(s). This purchase cannot be undone.
        </p>
        <div class="confirm-btns">
            <button class="confirm-btn-yes" onclick="submitPurchase()">Purchase Now</button>
            <button class="confirm-btn-no" onclick="hideConfirm()">Cancel</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tab switching
    function switchTab(tab, btn) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.payment-tab').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        btn.classList.add('active');
        document.getElementById('paymentMethodInput').value = tab;
    }

    // e-Wallet selection highlight
    function selectEwallet(label, code) {
        document.querySelectorAll('#tab-mobile label').forEach(l => l.style.borderColor = 'transparent');
        label.style.borderColor = '#66c0f4';
        label.querySelector('input[type="radio"]').checked = true;
    }

    // Card number formatting
    function formatCard(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 16);
        input.value = v.replace(/(.{4})/g, '$1 ').trim();
    }

    // Expiry formatting
    function formatExpiry(input) {
        let v = input.value.replace(/\D/g, '').substring(0, 4);
        if (v.length > 2) v = v.slice(0,2) + ' / ' + v.slice(2);
        input.value = v;
    }

    // Wallet toggle
    function toggleWallet() {
        fetch('{{ route("payment.wallet.toggle") }}', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json'},
        }).then(() => location.reload());
    }

    // Remove item
    function removeItem(id) {
        fetch(`/cart/${id}`, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        }).then(() => location.reload());
    }

    // Promo code (prevent full reload, optional)
    function applyPromo(e) { return true; }

    // Confirm modal
    function showConfirm() {
        // basic validation
        const activeTab = document.querySelector('.tab-panel.active');
        const checkbox = activeTab.querySelector('input[type="checkbox"]');
        if (checkbox && !checkbox.checked) {
            alert('Please agree to the Subscriber Agreement to continue.');
            return;
        }
        document.getElementById('confirmOverlay').classList.add('show');
    }

    function hideConfirm() {
        document.getElementById('confirmOverlay').classList.remove('show');
    }

    function submitPurchase() {
        document.getElementById('paymentForm').submit();
    }

    // Close overlay on outside click
    document.getElementById('confirmOverlay').addEventListener('click', function(e) {
        if (e.target === this) hideConfirm();
    });
</script>
</body>
</html>
@endsection

