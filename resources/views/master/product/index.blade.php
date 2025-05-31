@extends('layouts.cms')

@section('page_title', 'Products')
@section('page_subtitle', 'Manage your products')
@section('page_actions')
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bx bx-plus"></i> Add Product
    </a>
@endsection

@section('content')
<div class="row mb-4" id="filters">
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <div class="form-group">
            <select class="form-select select2" id="status" data-placeholder="All">
                <option></option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
</div>
<table class="table table-bordered table-hover mt-2 mb-2" id="main-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
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
            scrollX: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('products.index') }}",
                data: function(d) {
                    d.status = $('#status').val();
                }
            },
            columns: [
                {
                    data: 'sku',
                    name: 'sku',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row, meta) {
                        return `<div class="text-decoration-none fw-bold text-center">
                            ${meta.settings._iDisplayStart + meta.row + 1}
                        </div>`;
                    }
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row) {
                        return `<div class="text-decoration-none">
                            <div class="fw-bold">${data}</div>
                            <small class="text-muted">${row.sku}</small>
                        </div>`;
                    }
                },
                {
                    data: 'price',
                    name: 'price',
                    render: function(data, type, row) {
                        return `<div class="text-end">
                            ${Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data)}
                        </div>`;
                    }
                },
                { 
                    data: 'stock', 
                    name: 'stock',
                    render: function(data, type, row) {
                        return `<div class="text-end">
                            ${Intl.NumberFormat().format(data)}
                        </div>`;
                    }
                },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data, type, row) {
                        return `<div class="text-center">
                            <span class="badge bg-${data == 'active' ? 'success' : 'danger'}">${data}</span>
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
                        url: "{{ route('products.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            $('#main-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message || 'Product deleted successfully.',
                                icon: 'success',
                            });
                        },
                        error: function(xhr, status, error) {
                            $('#main-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong!',
                                icon: 'error',
                            });
                        }
                    });
                }
            });
        });

        $('#status').on('change', function() {
            $('#main-table').DataTable().ajax.reload();
        });

        $('.select2').select2({
            theme: 'bootstrap-5',
            allowClear: true,
        });
    });
</script>
@endpush