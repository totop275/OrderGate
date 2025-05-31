@extends('layouts.cms')

@section('page_title', 'Customers')
@section('page_subtitle', 'Manage your customers')
@section('page_actions')
    <a href="{{ route('customers.create') }}" class="btn btn-primary">
        <i class="bx bx-plus"></i> Add Customer
    </a>
@endsection

@section('content')
<table class="table table-bordered table-hover mt-2 mb-2" id="main-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
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
            ajax: "{{ route('customers.index') }}",
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
                    },
                    type: 'text'
                },
                {
                    data: 'name',
                    name: 'name',
                    render: function(data, type, row) {
                        return `<div class="text-decoration-none">
                            <div class="fw-bold">${data}</div>
                        </div>`;
                    }
                },
                {
                    data: 'email',
                    name: 'email',
                    type: 'text'
                },
                {
                    data: 'phone',
                    name: 'phone',
                    type: 'text'
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
                        url: "{{ route('customers.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            $('#main-table').DataTable().ajax.reload();
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message || 'Customer deleted successfully.',
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
    });
</script>
@endpush 