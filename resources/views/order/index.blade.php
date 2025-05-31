@extends('layouts.cms')

@section('page_title', 'Orders')
@section('page_subtitle', 'Manage your orders')
@section('page_actions')
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
        <i class="bx bx-plus"></i> Create New Order
    </a>
@endsection

@section('content')
<div class="row mb-4" id="filters">
    <div class="col-md-4">
        <label class="form-label">Date Start</label>
        <div class="form-group">
            <input type="date" class="form-control" id="start_date">
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Date End</label>
        <div class="form-group">
            <input type="date" class="form-control" id="end_date">
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <div class="form-group">
            <select class="form-select select2" id="status" multiple>
                <option value="">All</option>
                <option value="new">New</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>
</div>
<table class="table table-bordered table-hover mt-2 mb-2" id="main-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Order Number</th>
            <th>Customer</th>
            <th>Order Date</th>
            <th>Total Amount</th>
            <th>Status</th>
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
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('orders.index') }}",
                data: function(d) {
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.status = $('#status').val();
                }
            },
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
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
                        return `
                            <a href="${'{{ route('orders.show', ':id') }}'.replace(':id', data)}" class="text-decoration-none text-primary">
                                ${data}
                            </a>
                        `;
                    }
                },
                {
                    data: 'customer.name',
                    name: 'customer.name',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'order_date',
                    name: 'order_date',
                    searchable: false,
                    render: function(data, type, row) {
                        return Intl.DateTimeFormat(undefined).format(new Date(row.order_date));
                    }
                },
                {
                    data: 'total_amount',
                    name: 'total_amount',
                    searchable: false,
                    render: function(data) {
                        return Intl.NumberFormat(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }).format(data);
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row) {
                        const statusColors = {
                            'new': 'info',
                            'completed': 'success',
                            'cancelled': 'danger'
                        };
                        return `<div class="text-center">
                            <span class="badge bg-${statusColors[data] || 'secondary'}">${data}</span>
                        </div>`;
                    }
                },
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

        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'All',
        });

        $('#filters').on('change', '#start_date, #end_date, #status', function() {
            $('#main-table').DataTable().ajax.reload();
        });
    });
</script>
@endpush 