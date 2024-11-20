@extends('layouts.admin')

@section('content')

    <h2 class="text-xl font-bold">Create New User</h2>
    <p class="mb-4 text-gray-600">Fill in the details below to create a new user account.</p>

    <!-- Display success or error message -->
    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Please fix the following errors:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="form-group mb-4">
            <label for="name" class="block font-semibold">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-control mt-1" placeholder="Enter the user's name">
        </div>
        <div class="form-group mb-4">
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control mt-1" placeholder="Enter the user's email">
        </div>
        <div class="form-group mb-4">
            <label for="password" class="block font-semibold">Password</label>
            <input type="password" name="password" id="password" required class="form-control mt-1" placeholder="Enter a password">
        </div>
        <div class="form-group mb-4">
            <label for="password_confirmation" class="block font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control mt-1" placeholder="Confirm the password">
        </div>
        <div class="form-group mb-4">
            <label for="user_type" class="block font-semibold">User Type</label>
            <select name="user_type" id="user_type" required class="form-control mt-1">
                <option value="client" {{ old('user_type') == 'client' ? 'selected' : '' }}>Client</option>
                <option value="inspector" {{ old('user_type') == 'inspector' ? 'selected' : '' }}>Inspector</option>
                <option value="supervisor" {{ old('user_type') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
            </select>
        </div>
        
        <!-- Buttons Section -->
        <div class="flex items-center space-x-4">
            <!-- Create User Button -->
            <button type="submit" class="btn btn-success flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Create User
            </button>

            <!-- Cancel Button (Link) -->
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Cancel
            </a>
        </div>
    </form>

@endsection
