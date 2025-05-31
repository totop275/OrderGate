@extends('layouts.cms')

@section('page_title', 'Add Role')
@section('page_subtitle', 'Create a new role')

@section('content')
<form action="{{ route('roles.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Permissions</label>
        @foreach($permissionGroups as $group => $permissions)
        <label class="form-label text-primary">{{ ucfirst($group) }}</label>
        <div class="row">
            @foreach($permissions as $permission)
            <div class="col-md-4 mb-3">
                <div class="form-check mb-2">
                    <input type="checkbox" 
                        class="form-check-input @error('permissions') is-invalid @enderror" 
                        id="permission_{{ $permission }}" 
                        name="permissions[]" 
                        value="{{ $permission }}"
                        {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="permission_{{ $permission }}">
                        {{ ucfirst(explode('.', $permission)[1] ?? $permission) }}
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Role</button>
    </div>
</form>
@endsection 