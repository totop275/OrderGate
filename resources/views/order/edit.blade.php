@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Order</h3>
                </div>
                <form action="{{ route('orders.update', $order->id) }}" method="POST" id="order-form">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror" required>
                                        <option value="">Select Customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $order->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('customer_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_date">Order Date</label>
                                    <input type="date" name="order_date" id="order_date" class="form-control @error('order_date') is-invalid @enderror" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required>
                                    @error('order_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h4>Products</h4>
                                <div id="products-container">
                                    @foreach($order->products as $index => $product)
                                    <div class="row product-item mb-3">
                                        <div class="col-md-5">
                                            <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                                                <option value="">Select Product</option>
                                                @foreach($products as $p)
                                                    <option value="{{ $p->id }}" 
                                                        data-price="{{ $p->price }}"
                                                        {{ $product->id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" 
                                                name="products[{{ $index }}][quantity]" 
                                                class="form-control quantity-input" 
                                                placeholder="Quantity" 
                                                min="1" 
                                                value="{{ $product->pivot->quantity }}"
                                                required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control subtotal" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger remove-product">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success" id="add-product">
                                    <i class="bx bx-plus"></i> Add Product
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Amount</label>
                                    <input type="text" id="total-amount" class="form-control" readonly value="Rp {{ number_format($order->total_amount, 0, ',', '.') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Order</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        let productCount = {{ count($order->products) }};

        function calculateSubtotal(row) {
            const price = row.find('.product-select option:selected').data('price') || 0;
            const quantity = row.find('.quantity-input').val() || 0;
            const subtotal = price * quantity;
            row.find('.subtotal').val('Rp ' + subtotal.toLocaleString('id-ID'));
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            $('.product-item').each(function() {
                const price = $(this).find('.product-select option:selected').data('price') || 0;
                const quantity = $(this).find('.quantity-input').val() || 0;
                total += price * quantity;
            });
            $('#total-amount').val('Rp ' + total.toLocaleString('id-ID'));
        }

        // Calculate initial subtotals
        $('.product-item').each(function() {
            calculateSubtotal($(this));
        });

        $(document).on('change', '.product-select, .quantity-input', function() {
            calculateSubtotal($(this).closest('.product-item'));
        });

        $('#add-product').click(function() {
            const newRow = $('.product-item:first').clone();
            newRow.find('select').attr('name', `products[${productCount}][product_id]`).val('');
            newRow.find('input[type="number"]').attr('name', `products[${productCount}][quantity]`).val('');
            newRow.find('.subtotal').val('');
            $('#products-container').append(newRow);
            productCount++;
        });

        $(document).on('click', '.remove-product', function() {
            if ($('.product-item').length > 1) {
                $(this).closest('.product-item').remove();
                calculateTotal();
            }
        });

        $('#order-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success(response.message);
                    window.location.href = "{{ route('orders.index') }}";
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        toastr.error(errors[key][0]);
                    });
                }
            });
        });
    });
</script>
@endpush 