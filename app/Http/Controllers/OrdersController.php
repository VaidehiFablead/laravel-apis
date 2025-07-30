<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;

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
            'product_id' => 'required|array',
            'product_name' => 'required|array',
            'price' => 'required|array',
            'qty' => 'required|array',
            'total' => 'required|array',
            'subtotal' => 'required|numeric'
        ]);

        // Step 1: Create the order
        // $order = Orders::create([
        //     'customer_id' => $request->customer_id,
        //     'subtotal' => $request->subtotal,
        // ]);

        $order = new Orders();
        $order->customer_id = $request->customer_id;
        $order->subtotal = $request->subtotal;
        $order->save();

        // Convert arrays to comma-separated strings or JSON
        // $productIds = implode(',', $request->product_id);
        $productNames = json_encode($request->product_name);
        $quantities = json_encode($request->qty);
        $prices = json_encode($request->price);
        // dd( $order->order_id);
        // Insert single row into order_items
        OrderItem::create([
            'order_id' => $order->order_id,

            // 'product_id' => $productIds,
            'product_name' => $productNames,
            'qty' => $quantities,
            'price' => $prices,
            'subtotal' => $request->subtotal,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return response()->json(['message' => 'Order placed successfully with combined values']);
    }

    // public function index()
    // {
    //     $orders = OrderItem::with('customer')->get();

    //     return view('viewOrder', compact('orders'));
    // }

    public function viewOrder()
    {
        $orders = OrderItem::with('order.customer')->get(); // eager load nested relationship
        return view('viewOrder', compact('orders'));
    }
}
