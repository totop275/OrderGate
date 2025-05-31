@extends('layouts.cms')

@section('page_title', 'Order Details')
@section('page_subtitle', 'View order details')

@section('content')
<div class="row mb-2">
    <div class="col-md-6">
        <label class="fw-bold text-primary">Order Information</label>
        <div class="p-3 pt-0">
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Order Number</label>
                <div class="col-md-8 p-2">{{ $order->order_number }}</div>
            </div>
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Order Date</label>
                <div class="col-md-8 p-2">{{ $order->created_at->format('d-m-Y') }}</div>
            </div>
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Created By</label>
                <div class="col-md-8 p-2">{{ $order->creator?->name }}</div>
            </div>
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Status</label>
                <div class="col-md-8 p-2">
                    <span class="badge bg-{{ ['new' => 'info', 'completed' => 'success', 'cancelled' => 'danger'][strtolower($order->status)] }}">{{ $order->status }}</span>
                </div>
            </div>
            @if ($order->verifier)
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">{{ ucfirst($order->status) }} By</label>
                <div class="col-md-8 p-2">{{ $order->verifier?->name }}</div>
            </div>
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">{{ ucfirst($order->status) }} At</label>
                <div class="col-md-8 p-2">{{ $order->verified_at?->format('D, d M Y @ H:i') }}</div>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <label class="fw-bold text-primary">Customer</label>
        <div class="p-3 pt-0">
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Name</label>
                <div class="col-md-8 p-2">{{ $order->customer->name }}</div>
            </div>
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Email</label>
                <div class="col-md-8 p-2">{{ $order->customer->email }}</div>
            </div>
            <div class="row">
                <label class="col-md-4 py-2 fw-bold">Phone</label>
                <div class="col-md-8 p-2">{{ $order->customer->phone }}</div>
            </div>
        </div>
    </div>
</div>

<label class="fw-bold text-primary">Order Products</label>
<table class="table table-bordered table-hover mt-2 mb-2">
    <thead class="text-center">
        <tr>
            <th>Product</th>
            <th style="width: 200px;">Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($order->orderProducts as $orderProduct)
            <tr>
                <td>
                    <div class="text-decoration-none">
                        <div class="fw-bold">{{ $orderProduct->product->name }}</div>
                        <small class="text-muted">{{ $orderProduct->product->sku }}</small>
                    </div>
                </td>
                <td class="text-center">{{ $orderProduct->quantity }}</td>
                <td class="text-end">{{ number_format($orderProduct->price, 2) }}</td>
                <td class="text-end">{{ number_format($orderProduct->price * $orderProduct->quantity, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">
                    <em class="text-muted">No products in this order</em>
                </td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" class="text-end fw-bold">Total</td>
            <td class="text-end fw-bold">{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </tfoot>
</table>
@if($order->status == 'new')
<label class="fw-bold text-primary mt-2">Action</label>
<div class="d-flex justify-content-start gap-2 mt-2">
    <button type="button" class="btn btn-success verify-btn" data-status="completed">Complete Order</button>
    <button type="button" class="btn btn-danger verify-btn" data-status="cancelled">Cancel Order</button>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('.verify-btn').click(function() {
            const status = $(this).data('status');
            const text = status === 'completed' ? 'complete' : 'cancel';
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to ' + text + ' this order?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, ' + text + ' it!',
                cancelButtonText: 'No!',
                reverseButtons: false,
                showCancelButton: true,
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('api.orders.update', $order->id) }}',
                        type: 'POST',
                        data: {
                            status: status,
                            _method: 'PUT',
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message || 'Order ' + text + ' successfully.',
                                icon: 'success',
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong.',
                                icon: 'error',
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
