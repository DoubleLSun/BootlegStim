@extends('layouts.app')

@section('title', '403 | Access Restricted')

@push('styles')
<style>
    /* 1. FIX WHITE BACKGROUND ON SCROLL & LOCK THEME */
    html, body {
        background-color: #0f172a !important; 
        margin: 0;
        padding: 0;
        height: 100%;
        overflow-x: hidden;
    }

    .error-wrapper {
        min-height: 100vh; 
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at center, #1e1b4b 0%, #0f172a 100%);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        padding: 20px;
    }

    /* 2. GLASSMORPHISM CARD */
    .error-card {
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(239, 68, 68, 0.3);
        padding: 60px 40px;
        border-radius: 32px;
        max-width: 500px;
        width: 100%;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
    }

    /* 3. CSS WARNING DIAMOND (No SVG) */
    .status-icon-container {
        width: 100px;
        height: 100px;
        margin: 0 auto 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .warning-diamond {
        width: 65px;
        height: 65px;
        background: rgba(239, 68, 68, 0.15);
        border: 4px solid #ef4444;
        transform: rotate(45deg);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
        animation: diamond-pulse 2s infinite ease-in-out;
    }

    /* Placing the "!" upright */
    .warning-diamond::after {
        content: '!';
        transform: rotate(-45deg);
        color: #ffffff;
        font-size: 40px;
        font-weight: 900;
        display: block;
        text-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
    }

    @keyframes diamond-pulse {
        0%, 100% { 
            transform: rotate(45deg) scale(1); 
            box-shadow: 0 0 25px rgba(239, 68, 68, 0.5); 
        }
        50% { 
            transform: rotate(45deg) scale(1.1); 
            box-shadow: 0 0 45px rgba(239, 68, 68, 0.8); 
            border-color: #ff6b6b;
        }
    }

    /* 4. TYPOGRAPHY */
    h1 {
        color: #ffffff;
        font-size: 36px;
        font-weight: 900;
        letter-spacing: -1.5px;
        margin-bottom: 16px;
    }

    p {
        color: #94a3b8;
        line-height: 1.6;
        margin-bottom: 40px;
        font-size: 17px;
    }

    b {
        color: #f87171;
    }

    /* 5. BUTTONS */
    .action-btns {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .btn-main {
        background: #ffffff;
        color: #0f172a;
        text-decoration: none;
        padding: 18px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 16px;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .btn-main:hover {
        background: #f1f5f9;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
    }

    .btn-ghost {
        color: #64748b;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: 0.2s;
        padding: 10px;
    }

    .btn-ghost:hover {
        color: #f87171;
    }

    /* 6. SYSTEM LABEL - BIG & GLOWING */
    .system-label {
        margin-top: 60px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 18px; /* Bigger */
        font-weight: 800;
        color: #ef4444; 
        text-transform: uppercase;
        letter-spacing: 6px; 
        text-shadow: 0 0 15px rgba(239, 68, 68, 0.7);
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="error-wrapper">
    <div class="error-card">
        
        <div class="status-icon-container">
            <div class="warning-diamond"></div>
        </div>

        <h1>Access Restricted</h1>
        
        <p>
           Authentication failed. Your account does not have the required administrative privileges to access this <b>Admin Dashboard</b>.
        </p>

        <div class="action-btns">
            <a href="{{ url('/') }}" class="btn-main">
                Return to Storefront
            </a>
            <a href="mailto:admin@yourstore.com" class="btn-ghost">
                Request Admin Clearance
            </a>
        </div>

        <div class="system-label">
            Security Protocol: 403_FORBIDDEN
        </div>
    </div>
</div>
@endsection