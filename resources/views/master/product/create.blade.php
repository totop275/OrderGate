@extends('layouts.cms')

@section('page_title', 'Add Product')
@section('page_subtitle', 'Create a new product')

@section('content')
<form action="{{ route('products.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="sku" class="form-label">SKU</label>
                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}">
                @error('sku')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control inputmask-currency @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="text" class="form-control inputmask-numeric @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Status</label>
        <div class="form-check form-switch form-switch-lg">
            <input type="hidden" name="status" value="{{ \App\Models\Product::STATUS_INACTIVE }}">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="{{ \App\Models\Product::STATUS_ACTIVE }}" {{ old('status', \App\Models\Product::STATUS_ACTIVE) == \App\Models\Product::STATUS_ACTIVE ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Product</button>
    </div>
</form>
@endsection 

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputmaskNumeric = new Inputmask('numeric', {
            digits: 0,
            digitsOptional: false,
            radixPoint: ',',
            groupSeparator: ' ',
            autoUnmask: true,
            rightAlign: false,
            showMaskOnHover: false,
            showMaskOnFocus: false,
            showMaskOnReadOnly: false,
            removeMaskOnSubmit: true,
            clearMaskOnLostFocus: true
        });
        inputmaskNumeric.mask(document.querySelectorAll('.inputmask-numeric'));

        const inputmaskCurrency = new Inputmask('currency', {
            digits: 2,
            digitsOptional: false,
            decimalSymbol: ',',
            groupSeparator: ' ',
            autoUnmask: true,
            rightAlign: false,
            showMaskOnHover: false,
            showMaskOnFocus: false,
            showMaskOnReadOnly: false,
            removeMaskOnSubmit: true,
            clearMaskOnLostFocus: true
        });
        inputmaskCurrency.mask(document.querySelectorAll('.inputmask-currency'));
    });
</script>
@endpush