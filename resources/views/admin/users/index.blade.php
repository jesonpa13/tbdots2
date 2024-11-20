@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold">Manage Users</h2>

    @if (session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by Name" value="{{ request()->get('search') }}" style="max-width: 300px;">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Reset
            </a>
        </div>
    </form>

    <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-4">
        <i class="fas fa-plus"></i> Create User
    </a>

    <table class="table table-striped bg-white shadow rounded">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>User Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->user_type) }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                        <!-- Toggle Activation Status -->
                        @if($user->status === 'active')
                            <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-user-slash"></i> Deactivate
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.activate', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-user-check"></i> Activate
                                </button>
                            </form>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
