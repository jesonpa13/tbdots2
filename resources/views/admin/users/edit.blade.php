@extends('layouts.admin')

@section('content')

    <h2 class="text-xl font-bold">Edit User: {{ $user->name }}</h2>
    <p class="mb-4 text-gray-600">Update the details below.</p>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-4">
            <label for="name" class="block font-semibold">Name</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required class="form-control mt-1">
        </div>
        <div class="form-group mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" required class="form-control mt-1">
        </div>
        <div class="form-group mb-4">
            <label for="password" class="block font-semibold">New Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password" class="form-control mt-1" placeholder="New password">
        </div>
        <div class="form-group mb-4">
            <label for="password_confirmation" class="block font-semibold">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control mt-1" placeholder="Confirm password">
        </div>
        <div class="form-group mb-4">
            <label for="user_type" class="block font-semibold">User Type</label>
            <select name="user_type" id="user_type" required class="form-control mt-1">
                <option value="client" {{ $user->user_type === 'client' ? 'selected' : '' }}>Client</option>
                <option value="inspector" {{ $user->user_type === 'inspector' ? 'selected' : '' }}>Inspector</option>
                <option value="supervisor" {{ $user->user_type === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
            </select>
        </div>

        <!-- Buttons Section -->
        <div class="flex items-center space-x-4">
            <!-- Update User Button -->
            <button type="submit" class="btn btn-success flex items-center">
                <i class="fas fa-save mr-2"></i>
                Update User
            </button>

            <!-- Cancel Button (Link) -->
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Cancel
            </a>
        </div>
    </form>

@endsection
