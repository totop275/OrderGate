@extends('layouts.cms')

@section('page_title', 'Edit Role')
@section('page_subtitle', 'Update role information')

@section('content')
<form action="{{ route('roles.update', $role) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Permissions</label>
        @if ($role->name !== 'Admin')
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
                        {{ in_array($permission, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}>
                    <label class="form-check-label" for="permission_{{ $permission }}">
                        {{ ucfirst(explode('.', $permission)[1] ?? $permission) }}
                    </label>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        @else
        <div class="alert alert-info">
            <p>Admin role automatically has all permissions and cannot be edited.</p>
        </div>
        @endif
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
        @if ($role->name !== 'Admin')
        <button type="submit" class="btn btn-primary">Update Role</button>
        @endif
    </div>
</form>
@endsection 