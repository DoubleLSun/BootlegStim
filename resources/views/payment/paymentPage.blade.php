<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - BootlegStim</title>
<style>
    /* ---- PAYMENT PAGE STYLES ---- */
    .payment-page {
        background: #1b2838;
        min-height: calc(100vh - 60px);
        padding: 30px 20px;
        font-family: 'Motiva Sans', Arial, sans-serif;
    }

    .payment-container {
        max-width: 940px;
        margin: 0 auto;
    }

    .payment-page-title {
        font-size: 24px;
        font-weight: 300;
        color: #c6d4df;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin: 0 0 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #2a3f5f;
    }

    .payment-layout {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    /* ---- LEFT: ORDER SUMMARY ---- */
    .order-summary {
        flex: 1;
        min-width: 0;
    }

    .section-box {
        background: #16202d;
        border: 1px solid #2a3f5f;
        border-radius: 4px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .section-box-header {
        background: #2a3f5f;
        padding: 10px 16px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #c6d4df;
    }

    .section-box-body { padding: 16px; }

    /* Order items */
    .order-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .order-item:last-child { border-bottom: none; }

    .order-item-img {
        width: 96px;
        height: 45px;
        object-fit: cover;
        border-radius: 3px;
        background: #2a3f5f;
        flex-shrink: 0;
    }

    .order-item-info { flex: 1; min-width: 0; }

    .order-item-name {
        font-size: 14px;
        color: #c6d4df;
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-item-type {
        font-size: 11px;
        color: #8f98a0;
    }

    .order-item-price {
        font-size: 14px;
        color: #acdbf5;
        white-space: nowrap;
        font-weight: 700;
    }

    .order-item-remove {
        background: none;
        border: none;
        color: #8f98a0;
        font-size: 16px;
        cursor: pointer;
        padding: 4px;
        line-height: 1;
        transition: color 0.15s;
    }
    .order-item-remove:hover { color: #e85c4a; }

    /* Discount row */
    .discount-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 13px;
        color: #8f98a0;
    }

    .discount-tag {
        background: #4c6b22;
        color: #d2e885;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 2px;
        margin-left: 6px;
    }

    .discount-amount { color: #d2e885; }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0 4px;
        border-top: 1px solid #2a3f5f;
        margin-top: 8px;
    }

    .total-label {
        font-size: 13px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .total-value {
        font-size: 22px;
        font-weight: 700;
        color: #c6d4df;
    }

    /* Promo code */
    .promo-input-wrap {
        display: flex;
        gap: 8px;
    }

    .promo-input {
        flex: 1;
        background: #2a3f5f;
        border: 1px solid #1b2838;
        border-radius: 3px;
        color: #c6d4df;
        font-size: 13px;
        padding: 8px 12px;
        outline: none;
    }
    .promo-input::placeholder { color: #8f98a0; }
    .promo-input:focus { border-color: #66c0f4; }

    .btn-secondary {
        background: #2a3f5f;
        border: 1px solid #1b2838;
        color: #c6d4df;
        border-radius: 3px;
        padding: 8px 16px;
        font-size: 13px;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .btn-secondary:hover { background: #3d6f8e; }

    /* ---- RIGHT: PAYMENT FORM ---- */
    .payment-form-col {
        width: 340px;
        flex-shrink: 0;
    }

    /* Wallet balance */
    .wallet-box {
        background: linear-gradient(135deg, #1b3a4b 0%, #16202d 100%);
        border: 1px solid #2a3f5f;
        border-radius: 4px;
        padding: 14px 16px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .wallet-icon {
        width: 40px;
        height: 40px;
        background: #2a3f5f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .wallet-info { flex: 1; }

    .wallet-label {
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .wallet-balance {
        font-size: 20px;
        font-weight: 700;
        color: #57cbde;
    }

    .wallet-use-btn {
        background: #4c6b22;
        color: #d2e885;
        border: none;
        border-radius: 3px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
    }
    .wallet-use-btn:hover { background: #5c8226; }

    /* Payment method tabs */
    .payment-tabs {
        display: flex;
        gap: 0;
        margin-bottom: 16px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #2a3f5f;
    }

    .payment-tab {
        flex: 1;
        padding: 10px 6px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #8f98a0;
        background: #16202d;
        cursor: pointer;
        border: none;
        border-right: 1px solid #2a3f5f;
        transition: all 0.15s;
    }
    .payment-tab:last-child { border-right: none; }
    .payment-tab.active {
        background: #3d6f8e;
        color: #c6d4df;
    }
    .payment-tab:hover:not(.active) { background: #2a3f5f; }

    /* Tab panels */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Card form */
    .form-group { margin-bottom: 12px; }

    .form-label {
        display: block;
        font-size: 11px;
        color: #8f98a0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .form-input {
        width: 100%;
        background: #2a3f5f;
        border: 1px solid #1b2838;
        border-radius: 3px;
        color: #c6d4df;
        font-size: 13px;
        padding: 9px 12px;
        outline: none;
        box-sizing: border-box;
        transition: border-color 0.15s;
    }
    .form-input::placeholder { color: #5c7e96; }
    .form-input:focus { border-color: #66c0f4; }

    .form-row {
        display: flex;
        gap: 10px;
    }
    .form-row .form-group { flex: 1; }

    /* Card icons */
    .card-icons {
        display: flex;
        gap: 6px;
        margin-bottom: 12px;
    }

    .card-icon {
        height: 24px;
        background: #c6d4df;
        border-radius: 3px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: 900;
        display: flex;
        align-items: center;
        color: #1b2838;
    }
    .card-icon.visa { background: #1a1f71; color: #fff; }
    .card-icon.mc { background: #eb001b; color: #fff; }
    .card-icon.amex { background: #007bc1; color: #fff; }

    /* PayPal panel */
    .paypal-info {
        text-align: center;
        padding: 20px 16px;
    }

    .paypal-logo {
        font-size: 28px;
        font-weight: 900;
        margin-bottom: 10px;
    }
    .paypal-logo span:first-child { color: #003087; }
    .paypal-logo span:last-child { color: #009cde; }

    .paypal-desc {
        font-size: 12px;
        color: #8f98a0;
        margin-bottom: 16px;
    }

    /* Billing checkbox */
    .billing-agree {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-top: 14px;
        font-size: 11px;
        color: #8f98a0;
        line-height: 1.5;
    }

    .billing-agree input[type="checkbox"] {
        margin-top: 2px;
        accent-color: #66c0f4;
    }

    .billing-agree a { color: #66c0f4; text-decoration: none; }
    .billing-agree a:hover { text-decoration: underline; }

    /* Purchase button */
    .btn-purchase {
        display: block;
        width: 100%;
        margin-top: 16px;
        padding: 14px;
        background: linear-gradient(to bottom, #75b022 5%, #588a1b 95%);
        border: none;
        border-radius: 3px;
        color: #d2e885;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: filter 0.15s;
        text-align: center;
    }
    .btn-purchase:hover { filter: brightness(1.1); }
    .btn-purchase:active { filter: brightness(0.95); }

    .purchase-note {
        font-size: 10px;
        color: #8f98a0;
        text-align: center;
        margin-top: 8px;
        line-height: 1.5;
    }

    /* Security badges */
    .security-badges {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 12px;
        font-size: 10px;
        color: #5c7e96;
    }

    .security-badge {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Order confirmation modal overlay */
    .confirm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .confirm-overlay.show { display: flex; }

    .confirm-modal {
        background: #c6d4df;
        border-radius: 4px;
        padding: 30px;
        max-width: 400px;
        width: 90%;
        text-align: center;
        color: #1b2838;
    }

    .confirm-modal h2 {
        font-size: 20px;
        margin: 0 0 10px;
        color: #1b2838;
    }

    .confirm-modal p {
        font-size: 13px;
        color: #4b5d6e;
        margin: 0 0 20px;
    }

    .confirm-btns { display: flex; gap: 10px; justify-content: center; }

    .confirm-btn-yes {
        background: linear-gradient(to bottom, #75b022 5%, #588a1b 95%);
        color: #d2e885;
        border: none;
        border-radius: 3px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
    }

    .confirm-btn-no {
        background: #acbcca;
        color: #1b2838;
        border: none;
        border-radius: 3px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
    }

    /* Step indicator */
    .checkout-steps {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 24px;
        font-size: 11px;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #8f98a0;
    }

    .step.active { color: #c6d4df; }
    .step.done { color: #57cbde; }

    .step-num {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #2a3f5f;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
    }
    .step.active .step-num { background: #3d6f8e; color: #c6d4df; }
    .step.done .step-num { background: #57cbde; color: #1b2838; }

    .step-divider {
        flex: 1;
        height: 1px;
        background: #2a3f5f;
        margin: 0 8px;
        max-width: 40px;
    }
</style>

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
                        <div class="order-item">
                            <img class="order-item-img"
                                 src="{{ $item->game->thumbnail_url ?? asset('img/placeholder_game.jpg') }}"
                                 alt="{{ $item->game->name }}">
                            <div class="order-item-info">
                                <p class="order-item-name">{{ $item->game->name }}</p>
                                <span class="order-item-type">Standard Edition &mdash; Digital</span>
                            </div>
                            <div class="order-item-price">
                                @if($item->game->discount_percent > 0)
                                    <span style="text-decoration:line-through;color:#8f98a0;font-size:12px;font-weight:400;display:block;">
                                        RM {{ number_format($item->game->original_price, 2) }}
                                    </span>
                                @endif
                                RM {{ number_format($item->game->price, 2) }}
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
