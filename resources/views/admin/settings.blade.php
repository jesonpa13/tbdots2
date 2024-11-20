@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold">System Settings</h2>
    <p class="text-gray-600 mb-4">Configure global settings for user accounts, security policies, and role permissions.</p>

    <!-- System Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Password Policy Section -->
        <div class="form-group mb-4">
            <label for="password_policy" class="font-semibold">Password Policy</label>
            <select name="password_policy" id="password_policy" class="form-control mt-1">
                <option value="default">Default (8 characters minimum)</option>
                <option value="strict">Strict (12 characters, mixed characters)</option>
            </select>
        </div>

        <!-- Role-based Permissions -->
        <div class="form-group mb-4">
            <label class="font-semibold">Role Permissions</label>
            <p class="text-sm text-gray-600">Manage permissions for different roles to control access levels.</p>
            <div>
                <input type="checkbox" name="roles[inspector][view]" id="inspector_view" checked>
                <label for="inspector_view">Inspector - View Only</label>
            </div>
            <div>
                <input type="checkbox" name="roles[supervisor][edit]" id="supervisor_edit">
                <label for="supervisor_edit">Supervisor - View and Edit</label>
            </div>
        </div>

        <!-- Notification Settings -->
        <div class="form-group mb-4">
            <label for="notifications" class="font-semibold">Notifications</label>
            <input type="checkbox" name="notifications[account_activation]" checked>
            <label for="notifications">Account Activation Notifications</label>
        </div>

        <!-- Save Button -->
        <button type="submit" class="btn btn-success flex items-center">
            <i class="fas fa-save mr-2"></i>
            Update Settings
        </button>
    </form>
@endsection
