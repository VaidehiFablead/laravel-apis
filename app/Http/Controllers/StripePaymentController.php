<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Orders;
use Illuminate\Http\Request;
use PHPUnit\Metadata\Metadata;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Twilio\Rest\Client;

class StripePaymentController extends Controller
{
    // create PaymentIntent
    public function stripePaymentIntent(Request $request)
    {
        $amount = $request->amount;

        if ($amount < 1) {
            return response()->json([
                'error' => 'Amount must be at least ₹1.00'
            ], 422);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentIntent = PaymentIntent::create([

            'amount' => $amount * 100,
            'currency' => 'inr',
            'metadata' => [
                'name' => $request->name,
                'email' => $request->email,
                'order_id' => $request->order_id
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }


    public function show($order_id, Request $request)
    {
        $order = Orders::findOrFail($order_id);
        $orderItem = OrderItem::where('order_id', $order_id)->first();


        $productNames = json_decode($orderItem->product_name);
        $quantities = json_decode($orderItem->qty);
        $prices = json_decode($orderItem->price);

        $products = [];
        foreach ($productNames as $index => $name) {
            $products[] = [
                'name' => $name,
                'qty' => $quantities[$index],
                'price' => $prices[$index],
                'total' => $quantities[$index] * $prices[$index]
            ];
        }

        $customer = Customer::find($order->customer_id);
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $request->amount * 100,
            'currency' => 'inr',
            'metadata' => [
                'order_id' => $request->order_id,
                'name' => $request->name,
                'email' => $request->email
            ]
        ]);

        return view('stripe', [
            'order' => $order,
            'products' => $products,
            'qtys' => $quantities,
            'prices' => $prices,
            'customer' => $customer,
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }


    public function paymentSuccess(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:order,order_id'
            ]);

            $order = Orders::with('customer')->where('order_id', $request->order_id)->first();

            if (!$order || !$order->customer) {
                return response()->json(['error' => 'Customer not found.'], 404);
            }

            $customer = $order->customer;

            $phone = preg_replace('/[^0-9]/', '', $customer->contact_info);
            if (strlen($phone) == 10) {
                $phone = '+91' . $phone;
            }

            $client = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            $message = "Hello {$customer->name}, your payment for Order ID {$order->order_id} (₹{$order->subtotal}) was successful. Thank you!";

            $client->messages->create($phone, [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $message
            ]);

            return response()->json(['message' => 'SMS sent successfully.']);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Payment Success Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
