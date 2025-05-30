@extends('layouts.cms')

@section('page_title', 'Create Order')
@section('page_subtitle', 'Create a new order')

@section('content')
<form action="{{ route('orders.store') }}" method="POST" id="order-form">
    @csrf
    <div class="row mb-5">
        <div class="col-md-6">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
            </select>
            @error('customer_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="order_date" class="form-label">Order Date</label>
            <input type="date" name="order_date" id="order_date" class="form-control @error('order_date') is-invalid @enderror" required readonly value="{{ now()->format('Y-m-d') }}" disabled>
            @error('order_date')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    <table class="table table-bordered table-hover mt-2 mb-2" id="main-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="text-center">
                    <em class="text-muted">No products added yet</em>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td colspan="1" class="text-end fw-bold">0</td>
                <td colspan="1" class="text-end">
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="mt-3" id="add-product-container">
        <button class="btn btn-primary" id="add-product" type="button">
            <i class="bx bx-plus"></i> Add Product
        </button>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-5">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Order</button>
    </div>
</form>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#customer_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select Customer',
                ajax: {
                    url: '{{ route('api.customers.index') }}',
                    data: function(params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.data.data.map(item => ({
                                id: item.id,
                                text: item.name,
                            })),
                        };
                    },
                },
            });
        });
    </script>
@endpush