<!-- resources/views/thank-you.blade.php -->
@extends('layout.app')

@section('content')
    <div class="container mt-5 text-center">
        <h2 class="text-success">Payment Successful!</h2>
        {{-- <p>Order ID: #{{ $order->order_id }}</p> --}}
        <p>Subtotal: ₹{{ $order->subtotal }}</p>
        <p>Thank you for your purchase. Your payment was processed successfully.</p>
        <a href="{{ url('/tables') }}" class="btn btn-primary mt-3">Back to Home</a>
    </div>
@endsection
