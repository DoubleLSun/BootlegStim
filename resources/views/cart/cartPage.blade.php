@extends('layouts.app')

@section('title', 'Cart | BootlegStim')
@section('main-class', '')

<!-- force use css/cart/cartPage.css-->
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cart/cartPage.css') }}">
@endpush

@section('content')
<div class="container-fluid px-0">
    <div id="cart-page-root"></div>
</div>
@endsection
