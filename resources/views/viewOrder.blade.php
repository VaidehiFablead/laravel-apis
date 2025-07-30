@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <h4>Customer Ordered List</h4>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Products</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $index => $orderItem)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $orderItem->order->customer->name ?? 'N/A' }}
                        </td>
                        <td>
                            @php
                                $products = json_decode($orderItem->product_name, true);
                            @endphp
                            <ul>
                                @foreach ($products as $product)
                                    <li>{{ $product }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>₹{{ number_format($orderItem->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection
