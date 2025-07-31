@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Products -->
            <div class="col-md-6">
                <h4>Order Summary</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productNames as $i => $name)
                            <tr>
                                <td>{{ $name }}</td>
                                <td>{{ $qtys[$i] }}</td>
                                <td>₹{{ $prices[$i] }}</td>
                                <td>₹{{ $qtys[$i] * $prices[$i] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="submit" class="btn btn-primary">Pay ₹{{ $order->subtotal }}</div>

            </div>

            <!-- Stripe Form -->
            <div class="col-md-6">
                <h4>Pay Now</h4>
                <form id="payment-form">
                    <input type="hidden" id="order_id" value="{{ $order->order_id }}">
                    <input type="hidden" id="amount" value="{{ $order->subtotal }}">
                    <div class="mb-3">
                        <input type="text" id="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <input type="email" id="email" placeholder="Email" class="form-control">
                    </div>
                    <div id="card-element" class="form-control mb-3"></div>
                    <div id="card-errors" class="text-danger mb-2"></div>
                    <button id="submit" class="btn btn-primary">Pay ₹{{ $order->subtotal }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const amount = document.getElementById('amount').value;
            const order_id = document.getElementById('order_id').value;

            // ✅ Validation
            if (name === "") {
                document.getElementById('card-errors').textContent = "Please enter your name.";
                return;
            }

            if (email === "") {
                document.getElementById('card-errors').textContent = "Please enter your email.";
                return;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('card-errors').textContent = "Please enter a valid email.";
                return;
            }

            // Optional: show loading state
            document.getElementById('card-errors').textContent = "Processing...";

            const response = await fetch("{{ route('stripe.intent') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    name,
                    email,
                    amount,
                    order_id
                })
            });

            const data = await response.json();

            const {
                error,
                paymentIntent
            } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: name,
                        email: email
                    }
                }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                // Payment success
                window.location.href = '/thank-you/' + order_id;

            }
        });
    </script>
@endpush
