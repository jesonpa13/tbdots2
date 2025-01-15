@extends('layouts.admin')

@section('content')
    <!-- Dashboard Content Goes Here -->
    <div class="mb-4">
        <h2 class="text-xl font-semibold">Welcome to the Health System Admin Dashboard</h2>
        <p class="mt-2 text-gray-600">Manage your users, roles, and system settings efficiently.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Total Users Card -->
        <div class="p-4 rounded-lg shadow flex items-center" style="background-color: rgba(255, 255, 255, 0.85);">
            <div class="mr-4 text-blue-500">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Total Users</h3>
                <p class="text-2xl">{{ $userCount }}</p>
            </div>
        </div>

        <!-- Active Roles Card -->
        <div class="p-4 rounded-lg shadow flex items-center" style="background-color: rgba(255, 255, 255, 0.85);">
            <div class="mr-4 text-green-500">
                <i class="fas fa-user-shield fa-2x"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">Active Roles</h3>
                <p class="text-2xl">{{ $activeRolesCount }}</p>
            </div>
        </div>

        <!-- System Settings Card -->
        <div class="p-4 rounded-lg shadow flex items-center" style="background-color: rgba(255, 255, 255, 0.85);">
            <div class="mr-4 text-gray-500">
                <i class="fas fa-cog fa-2x"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">System Settings</h3>
                <p class="text-sm text-gray-600">Configure your system settings here.</p>
            </div>
        </div>
    </div>
@endsection
