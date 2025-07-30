@extends('layout.app')

@section('content')
    <style>
        .StripeElement {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>

    <div class="container mt-5">
        <div class="row">

            <div class="col-md-6">
                <h4>Order Summery</h4>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>SubTotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['qty'] }}</td>
                                <td>{{ $product['price'] }}</td>
                                <td>{{ $product['price'] * $product['qty'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <h5>Total: ₹<span id="total-amount">{{ $total }}</span></h5>
            </div>
        </div>


        {{-- stripe payment from --}}
        <div class="col-lg-6">
            <h4>Payment</h4>
            <form id="payment-form">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Cardholder Name</label>
                    <input type="text" class="form-control" id="name" required>
                </div>

                <div id="card-element" class="mb-3"></div>
                <div id="card-errors" class="text-danger mb-3"></div>

                <button class="btn btn-primary" type="submit">Pay ₹{{ $total }}</button>
            </form>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ env('STRIPE_KEY') }}'
            const element = stripe.elements();
            const cards = element.create('card'); card.mount("#card-element");

            const form = document.getElementById('payment-form');

            form.addEventListener('submit', async (e) => {
                e.preventdefault();

                const {
                    paymentMethod,
                    error
                } = await stripe.createpaymentMethod({
                    type: 'card',
                    card: card,
                    billing_details: {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value
                    },
                    if (error) {
                        document.getElementById('card-errors').textContent = error.message;
                    } else {
                        fetch('/payment-intent', {
                            method: "POST",
                            header: {
                                'Content-Type': 'application/json',
                                'X-CSRF_TOKEN': document.querySelector('input[name="_token"]')
                                    .value
                            },
                            body: JSON.stringify({
                                    payment_method: paymentMethod.id,
                                    amount: {{ $total * 100 }}, // In paise
                                    email: document.getElementById('email').value,
                                    name: document.getElementById('name').value
                                })
                                .then(res => res.json())
                                .then(async data => {
                                    const result = await stripe.confirmCardPayment(data
                                        .client_secret);
                                    if (result.error) {
                                        document.getElementById('card-errors')
                                            .textContent = result.error.message;
                                    } else if (result.paymentIntent.status ===
                                        'succeeded') {
                                        alert('Payment successful!');
                                        window.location.href = '/thank-you';
                                    }
                                });
                        });
                    }

                });
            })
        )
    </script>
@endsection
