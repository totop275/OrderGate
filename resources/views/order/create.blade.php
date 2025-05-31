@extends('layouts.cms')

@section('page_title', 'Create Order')
@section('page_subtitle', 'Create a new order')

@section('content')
<form action="javascript:void(0)" method="POST" id="order-form">
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
        <thead class="text-center">
            <tr>
                <th>Product</th>
                <th style="width: 200px;">Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th style="width: 50px;"></th>
            </tr>
        </thead>
        <tbody id="order-items-container">
            <tr>
                <td colspan="5" class="text-center">
                    <em class="text-muted">No products added yet</em>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td colspan="1" class="text-end fw-bold" id="total">0</td>
                <td colspan="1" class="text-end">
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="mt-3 row" id="add-product-container">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <input type="text" name="product_sku" id="input_product_sku" class="form-control" placeholder="Scan / Enter Product SKU and press Enter">
        </div>
        <div class="col-md-auto d-flex align-items-center justify-content-center">
            <span class="text-muted">or</span>
        </div>
        <div class="col-md-5">
            <button class="btn btn-primary" id="add-product" type="button">
                <i class="bx bx-search"></i> Browse
            </button>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-5">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="button" class="btn btn-primary" id="create-order">Create Order</button>
    </div>
</form>

<!-- Browse Product Modal -->
<div class="modal fade" id="browseProductModal" tabindex="-1" aria-labelledby="browseProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="browseProductModalLabel">Browse Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="products-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script>
        const products = {};
        let orderItems = [];
        let changedState = false;
        document.addEventListener('DOMContentLoaded', function() {
            $('#customer_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select Customer',
                ajax: {
                    url: '{{ route('api.customers.index') }}',
                    delay: 250,
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

            $('#add-product').click(function() {
                new bootstrap.Modal(document.getElementById('browseProductModal')).show();
            });

            $('#products-table').DataTable({
                theme: 'bootstrap5',
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('products.index') }}',
                    data: function(params) {
                        params.status = 'active';
                        return params;
                    },
                    complete: function(response) {
                        if (response.responseJSON?.data?.length > 0) {
                            response.responseJSON.data.forEach(product => {
                                products[product.id] = product;
                            });
                        }
                    }
                },
                columns: [
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
                        searchable: false,
                        render: function(data, type, row) {
                            return `<div class="text-end">
                                ${Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(data)}
                            </div>`;
                        }
                    },
                    { 
                        data: 'stock', 
                        name: 'stock',
                        searchable: false,
                        render: function(data, type, row) {
                            return `<div class="text-end">
                                ${Intl.NumberFormat().format(data)}
                            </div>`;
                        }
                    },
                    { 
                        data: 'sku', 
                        name: 'sku', 
                        orderable: false, 
                        searchable: true,
                        render: function(data, type, row) {
                            return `<div class="text-center">
                                <button class="btn btn-primary btn-sm add-product" data-id="${row.id}">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>`;
                        }
                    }
                ],
                language: {
                    lengthMenu: "Show: _MENU_",
                },
                order: [[1, 'asc']]
            });

            $('#products-table').on('click', '.add-product', function() {
                const productId = $(this).data('id');
                addProductToOrder(productId);
            });

            $('#input_product_sku').on('keydown', function(e) {
                if (e.key === 'Enter' && $('#input_product_sku').val().trim() !== '') {
                    e.preventDefault();
                    e.stopPropagation();
                    const productSku = $('#input_product_sku').val().trim();
                    const product = Object.values(products).find(product => product.sku === productSku);
                    if (product) {
                        addProductToOrder(product.id);
                    } else {
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Searching product...',
                            icon: 'info',
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        $.ajax({
                            url: '{{ route('api.products.show', ['product' => ':id']) }}'.replace(':id', productSku),
                            type: 'GET',
                            data: {
                                status: '{{ \App\Models\Product::STATUS_ACTIVE }}',
                            },
                            success: function(response) {
                                Swal.close();
                                products[response.data.id] = response.data;
                                addProductToOrder(response.data.id);
                            },
                            error: function(response) {
                                Swal.close();
                                toastr.error('Product not found');
                            }
                        });
                    }
                    $('#input_product_sku').val('');
                }
            });

            $('#create-order').click(function() {
                const form = $('#order-form');
                if (!form[0].checkValidity()) {
                    form[0].reportValidity();
                    return;
                }

                if (orderItems.length === 0) {
                    Swal.fire({
                        title: 'No products added to order',
                        text: 'Please add at least one product to the order!',
                        icon: 'warning',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will create a new order!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, create it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Creating order...',
                            icon: 'info',
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        const formData = new FormData(form[0]);
                        $.ajax({
                            url: '{{ route('api.orders.store') }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.close();
                                Swal.fire({
                                    title: 'Order created successfully',
                                    text: response.message,
                                    icon: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                    confirmButtonText: 'OK',
                                }).then(() => {
                                    window.location.href = '{{ route('orders.show', ['order' => ':id']) }}'.replace(':id', response.data.order_number);
                                });
                            },
                            error: function(response) {
                                Swal.close();
                                toastr.error(response.responseJSON.message || 'Something went wrong');
                            }
                        });
                    }
                });
            });
            
            refocusInput();
        });

        function addProductToOrder(productId) {
            if (orderItems.find(item => item.product_id === productId)) {
                if (orderItems.find(item => item.product_id === productId).quantity < products[productId].stock) {
                    orderItems.find(item => item.product_id === productId).quantity++;
                } else {
                    toastr.error('Product stock is not enough');
                    return;
                }
            } else {
                if (products[productId].stock > 0) {
                    orderItems.push({
                        product_id: productId,
                        quantity: 1,
                    });
                } else {
                    toastr.error('Product stock is not enough');
                    return;
                }
            }
            renderOrderItems();
            toastr.success('Product added to order');
            changedState = true;
        }

        function renderOrderItems() {
            const orderItemsContainer = $('#order-items-container');
            orderItemsContainer.empty();
            let total = 0;
            orderItems.forEach(item => {
                orderItemsContainer.append(`<tr>
                    <td>
                        <div class="text-decoration-none">
                            <div class="fw-bold">${products[item.product_id].name}</div>
                            <small class="text-muted">${products[item.product_id].sku}</small>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseOrderItemQuantity(${item.product_id})">
                                <i class="bx bx-minus"></i>
                            </button>
                            <input type="number" class="form-control w-50px" value="${item.quantity}" min="1" max="${products[item.product_id].stock}" onchange="updateOrderItemQuantity(${item.product_id}, this.value)" name="products[${item.product_id}]">
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseOrderItemQuantity(${item.product_id})">
                                <i class="bx bx-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="text-end">${Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(products[item.product_id].price)}</td>
                    <td class="text-end">${Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(products[item.product_id].price * item.quantity)}</td>
                    <td>
                        <button class="btn btn-outline-danger" type="button" onclick="removeOrderItem(${item.product_id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </td>
                </tr>`);
                total += products[item.product_id].price * item.quantity;
            });

            if (orderItems.length === 0) {
                orderItemsContainer.append(`<tr>
                    <td colspan="5" class="text-center">
                        <em class="text-muted">No products added yet</em>
                    </td>
                </tr>`);
            }

            $('#total').text(Intl.NumberFormat(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(total));
        }

        function decreaseOrderItemQuantity(productId) {
            const item = orderItems.find(item => item.product_id === productId);
            if (item.quantity > 1) {
                item.quantity--;
                renderOrderItems();
                toastr.success('Product quantity decreased');
            } else {
                toastr.error('Product quantity cannot be less than 1');
                if (item.quantity < 1) {
                    item.quantity = 1;
                    renderOrderItems();
                }
            }
            refocusInput();
        }

        function updateOrderItemQuantity(productId, quantity) {
            const item = orderItems.find(item => item.product_id === productId);
            if (quantity > 0) {
                if (quantity > products[productId].stock) {
                    quantity = products[productId].stock;
                    toastr.error('Product stock is not enough');
                }
                item.quantity = quantity;
                renderOrderItems();
                toastr.success('Product quantity updated');
            } else {
                item.quantity = 1;
                renderOrderItems();
                toastr.error('Product quantity cannot be less than 1');
            }
            refocusInput();
        }

        function increaseOrderItemQuantity(productId) {
            const item = orderItems.find(item => item.product_id === productId);
            if (item.quantity < products[item.product_id].stock) {
                item.quantity++;
                renderOrderItems();
                toastr.success('Product quantity increased');
            } else {
                toastr.error('Product stock is not enough');
                if (item.quantity > products[productId].stock) {
                    item.quantity = products[productId].stock;
                    renderOrderItems();
                }
            }
            refocusInput();
        }

        function removeOrderItem(productId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This product will be removed from the order!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    orderItems = orderItems.filter(item => item.product_id !== productId);
                    renderOrderItems();
                    toastr.success('Product removed from order');
                    refocusInput();
                }
            });
        }

        function refocusInput() {
            $('#input_product_sku').focus();
        }

        window.addEventListener('beforeunload', function (e) {
            if (changedState) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave this page?';
            }
        });
    </script>
@endpush