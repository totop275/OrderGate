@extends('layouts.cms')

@section('page_title', 'Orders')
@section('page_subtitle', 'Manage your orders')
@section('page_actions')
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bx bx-plus"></i> Create New Order
    </a>
@endsection

@section('content')
<table class="table table-bordered table-hover mt-2 mb-2" id="main-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Order Number</th>
            <th>Customer</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#main-table').DataTable({
            theme: 'bootstrap5',
            processing: true,
            serverSide: true,
            ajax: "{{ route('orders.index') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row, meta) {
                        return `<div class="text-decoration-none fw-bold text-center">
                            ${meta.settings._iDisplayStart + meta.row + 1}
                        </div>`;
                    }
                },
                {
                    data: 'order_number',
                    name: 'order_number',
                    render: function(data, type, row) {
                        return `<div class="text-decoration-none">
                            <div class="fw-bold">${data}</div>
                        </div>`;
                    }
                },
                {
                    data: 'customer.name',
                    name: 'customer.name',
                    render: function(data, type, row) {
                        return `<div class="text-decoration-none">
                            <div class="fw-bold">${data}</div>
                        </div>`;
                    }
                },
                {
                    data: 'order_date',
                    name: 'order_date',
                    render: function(data, type, row) {
                        return `<div class="text-center">
                            ${data}
                        </div>`;
                    }
                },
                {
                    data: 'total_amount',
                    name: 'total_amount',
                    render: function(data) {
                        return `<div class="text-end">
                            Rp ${parseFloat(data).toLocaleString('id-ID')}
                        </div>`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function(data, type, row) {
                        const statusColors = {
                            'pending': 'warning',
                            'processing': 'info',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        return `<div class="text-center">
                            <span class="badge bg-${statusColors[data] || 'secondary'}">${data}</span>
                        </div>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                lengthMenu: "Show: _MENU_",
            },
            order: [[1, 'asc']]
        });

        $('#main-table').on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('orders.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            $('#main-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message || 'Order deleted successfully.',
                                icon: 'success',
                            });
                        },
                        error: function(xhr) {
                            $('#main-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while deleting the order.',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush 