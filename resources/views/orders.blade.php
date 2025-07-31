@extends('layout.app')

@section('content')
    <div class="container mt-5">
        <h2>Create New Order</h2>

        <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
            @csrf

            {{-- Customer Dropdown --}}
            <div class="mb-4">
                <label for="customer_id" class="form-label">Select Customer</label>
                <select name="customer_id" id="customer_id" class="form-select w-50">
                    <option value="">-- Select Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->customer_id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Product Rows --}}
            <div id="productRows">
                <div class="row align-items-end product-row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="product_id[]" class="form-select product_id">
                            <option value="">-- Select Product --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" class="product_name" name="product_name[]">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control price" name="price[]" readonly>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number" class="form-control qty" name="qty[]" min="1">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label">Total</label>
                        <input type="text" class="form-control total" readonly>
                        <input type="hidden" class="total-hidden" name="total[]">
                    </div>

                    <div class="col-md-2 mb-3 button-col">
                        <label class="form-label">Action</label><br>
                        <button type="button" class="btn btn-danger addNew">
                            <i class="fa-solid fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Subtotal --}}
            <div class="row mt-3">
                <div class="col-md-3">
                    <label class="form-label">Sub Total</label>
                    <input type="text" class="form-control" id="subtotal" readonly>
                    <input type="hidden" id="subtotal-hidden" name="subtotal">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Place Order</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function calculateTotal(row) {
            const price = parseFloat(row.find('.price').val()) || 0;
            const qty = parseFloat(row.find('.qty').val()) || 0;
            const total = price * qty;
            row.find('.total').val(total.toFixed(2));
            row.find('.total-hidden').val(total.toFixed(2));
            updateSubTotal();
        }

        function updateSubTotal() {
            let subtotal = 0;
            $('.total').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });
            $('#subtotal').val(subtotal.toFixed(2));
            $('#subtotal-hidden').val(subtotal.toFixed(2));
        }

        $(document).on('change', '.product_id', function() {
            const currentSelect = $(this);
            const selectedValue = currentSelect.val();
            const currentRow = currentSelect.closest('.product-row');

            // Check for duplicate product selection
            let duplicateFound = false;

            $('.product_id').not(this).each(function() {
                if ($(this).val() === selectedValue) {
                    duplicateFound = true;

                    // Find the corresponding qty input and increment it
                    const existingRow = $(this).closest('.product-row');
                    const qtyInput = existingRow.find('.qty');
                    const existingQty = parseInt(qtyInput.val()) || 1;
                    qtyInput.val(existingQty + 1);

                    // Recalculate total for the existing row
                    calculateTotal(existingRow);

                    // Clear current select and reset row
                    currentSelect.val('');
                    currentRow.find('.price').val('');
                    currentRow.find('.product_name').val('');
                    currentRow.find('.qty').val('');
                    currentRow.find('.total').val('');
                    currentRow.find('.total-hidden').val('');

                    Swal.fire('Note', 'Product already selected. Increased its quantity.', 'info');
                    return false; // break the loop
                }
            });

            if (!duplicateFound && selectedValue) {
                const selected = currentSelect.find(':selected');
                const price = selected.data('price');
                const name = selected.text();

                currentRow.find('.price').val(price);
                currentRow.find('.product_name').val(name);
                currentRow.find('.qty').val(1); // default to 1
                calculateTotal(currentRow);
            }
        });


        $(document).on('input', '.qty', function() {
            const row = $(this).closest('.product-row');
            calculateTotal(row);
        });

        $(document).on('click', '.addNew', function() {
            const clone = $('.product-row:first').clone();
            clone.find('select, input').val('');
            clone.find('.button-col').html(`
            <label class="form-label">Action</label><br>
            <button type="button" class="btn btn-secondary removeRow">
                <i class="fa-solid fa-xmark"></i>
            </button>
        `);
            $('#productRows').append(clone);
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('.product-row').remove();
            updateSubTotal();
        });


        $('#orderForm').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: "{{ route('orders.store') }}",
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.redirect_url) {
                        window.location.href = res.redirect_url;
                    } else {
                        Swal.fire('Error', 'No redirect URL found!', 'error');
                    }
                },
                error: function(xhr) {
                    let msg = 'Something went wrong!';
                    if (xhr.responseJSON?.errors) {
                        const firstKey = Object.keys(xhr.responseJSON.errors)[0];
                        msg = xhr.responseJSON.errors[firstKey][0];
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    </script>
@endpush
