<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Twilio\Rest\Client as RestClient;
use Twilio\Rest\Client;

class OrdersController extends Controller
{


    public function showOrder()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders', compact('customers', 'products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customer,customer_id',
            'product_name' => 'required|array',
            'price' => 'required|array',
            'qty' => 'required|array',
            'total' => 'required|array',
            'subtotal' => 'required|numeric'
        ]);

        // Create the order
        $order = new Orders();
        $order->customer_id = $request->customer_id;
        $order->subtotal = $request->subtotal;
        $order->save();

        // Save order items
        OrderItem::create([
            'order_id' => $order->order_id,
            'product_name' => json_encode($request->product_name),
            'qty' => json_encode($request->qty),
            'price' => json_encode($request->price),
            'subtotal' => $request->subtotal,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect to stripe page with order_id
        return response()->json([
            'redirect_url' => route('stripe.checkout', ['order_id' => $order->order_id])
        ]);
    }


    public function viewOrder()
    {
        $orders = OrderItem::with('order.customer')->get();
        return view('viewOrder', compact('orders'));
    }

    public function stripeCheckout($order_id)
    {
        $order = Orders::with('customer')->findOrFail($order_id);
        $orderItem = OrderItem::where('order_id', $order_id)->first();

        $productNames = json_decode($orderItem->product_name);
        $qtys = json_decode($orderItem->qty);
        $prices = json_decode($orderItem->price);

        $customerName = $order->customer->name;

        return view('stripe', compact('order', 'productNames', 'qtys', 'prices', 'customerName'));
    }
}
