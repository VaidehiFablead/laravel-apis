<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPUnit\Metadata\Metadata;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function show()
    {
        return view('stripe');
    }

    public function stripePaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $amount = $request->amount * 100;

        $paymentIntent=PaymentIntent::create([
            'amount'=>$amount,
            'currency'=>'inr',
            'metadata'=>[
                'name'=>$request->name,
                'email'=>$request->email
            ],
        ]);

        return response()->json([
            'clientSecret'=>$paymentIntent->client_secret,
        ]);
    }
}
