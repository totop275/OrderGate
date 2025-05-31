@extends('layouts.cms')

@section('page_title', 'Add User')
@section('page_subtitle', 'Create a new user')

@section('content')
<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Roles</label>
        @foreach($roles as $role)
        <div class="form-check mb-2">
            <input type="checkbox" 
                class="form-check-input @error('roles') is-invalid @enderror" 
                id="role_{{ $role->name }}" 
                name="roles[]" 
                value="{{ $role->name }}"
                {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
            <label class="form-check-label" for="role_{{ $role->name }}">
                {{ $role->name }}
            </label>
        </div>
        @endforeach
        @error('roles')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label d-block">Status</label>
        <div class="form-check form-switch form-switch-lg">
            <input type="hidden" name="status" value="{{ \App\Models\User::STATUS_INACTIVE }}">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="{{ \App\Models\User::STATUS_ACTIVE }}" {{ old('status', \App\Models\User::STATUS_ACTIVE) == \App\Models\User::STATUS_ACTIVE ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save User</button>
    </div>
</form>
@endsection 