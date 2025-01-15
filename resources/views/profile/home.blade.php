@extends('layouts.userlayout')

@section('content')
    <h2 class="mb-4">Edit Your Profile</h2>

    <div class="container">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="province_city" class="form-label">Province</label>
                        <input type="text" name="province_city" class="form-control" value="{{ old('province_city', auth()->user()->additionalInformation->province_city ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()->additionalInformation->city ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="head_of_unit" class="form-label">Head of Unit</label>
                        <input type="text" name="head_of_unit" class="form-control" value="{{ old('head_of_unit', auth()->user()->additionalInformation->head_of_unit ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="facility" class="form-label">Facility</label>
                        <input type="text" name="facility" class="form-control" value="{{ old('facility', auth()->user()->additionalInformation->facility ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->additionalInformation->address ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', auth()->user()->additionalInformation->contact_number ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="designation" class="form-label">Designation</label>
                        <input type="text" name="designation" class="form-control" value="{{ old('designation', auth()->user()->additionalInformation->designation ?? '') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </div>
        </form>
    </div>
@endsection
